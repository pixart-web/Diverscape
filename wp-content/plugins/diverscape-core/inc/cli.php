<?php
/**
 * WP-CLI: importação idempotente e verificação.
 *
 *   wp diverscape seed            cria o que falta (não altera conteúdos já editados no painel)
 *   wp diverscape seed --force    reaplica os conteúdos originais da proposta
 *   wp diverscape seed --only=media|catalog|pages|offers|faq|menus|settings
 *   wp diverscape status          resumo + verificação de cobertura das 49 linhas de origem
 */

defined( 'ABSPATH' ) || exit;

/** Resolve valores traduzidos ($T) num array de atributos. */
function dvs_seed_localize( $value, string $lang ) {
	if ( is_array( $value ) ) {
		if ( ! empty( $value['__i18n'] ) ) {
			return $value[ $lang ] ?? $value['en'];
		}
		return array_map( static fn( $v ) => dvs_seed_localize( $v, $lang ), $value );
	}
	return $value;
}

function dvs_seed_block( string $name, array $attrs = array(), array $inner = array(), string $open = '', string $close = '' ): array {
	$content = array();
	if ( $inner ) {
		$content[] = $open;
		foreach ( $inner as $i => $b ) {
			$content[] = null;
		}
		$content[] = $close;
	} elseif ( $open ) {
		$content[] = $open;
	}
	return array(
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => $inner,
		'innerHTML'    => $open . $close,
		'innerContent' => $content,
	);
}

/** Converte a estrutura «prose» em blocos nucleares válidos. */
function dvs_seed_prose( array $items ): string {
	$inner = array();
	foreach ( $items as $it ) {
		list( $type, $val ) = $it;
		if ( 'h2' === $type ) {
			$inner[] = dvs_seed_block( 'core/heading', array(), array(), '<h2 class="wp-block-heading">' . esc_html( $val ) . '</h2>' );
		} elseif ( 'p' === $type ) {
			$inner[] = dvs_seed_block( 'core/paragraph', array(), array(), '<p>' . esc_html( $val ) . '</p>' );
		} elseif ( 'ul' === $type ) {
			$lis = array();
			foreach ( $val as $li ) {
				$lis[] = dvs_seed_block( 'core/list-item', array(), array(), '<li>' . esc_html( $li ) . '</li>' );
			}
			$inner[] = dvs_seed_block( 'core/list', array(), $lis, '<ul class="wp-block-list">', '</ul>' );
		}
	}
	return serialize_block( dvs_seed_block( 'core/group', array( 'className' => 'dvs-prose' ), $inner, '<div class="wp-block-group dvs-prose">', '</div>' ) );
}

function dvs_seed_paragraphs( array $paras ): string {
	$out = array();
	foreach ( $paras as $p ) {
		$out[] = serialize_block( dvs_seed_block( 'core/paragraph', array(), array(), '<p>' . esc_html( $p ) . '</p>' ) );
	}
	return implode( "\n\n", $out );
}

/** Conteúdo de uma página num idioma. */
function dvs_seed_page_content( array $page, string $lang ): string {
	$out = array();
	foreach ( $page['blocks'] as $b ) {
		if ( 'prose' === $b[0] ) {
			$out[] = dvs_seed_prose( $b[1][ $lang ] ?? $b[1]['en'] );
			continue;
		}
		$attrs = dvs_seed_localize( $b[1], $lang );
		$attrs = array_filter( $attrs, static fn( $v ) => '' !== $v && null !== $v );
		$out[] = serialize_block( dvs_seed_block( $b[0], $attrs ) );
	}
	return implode( "\n\n", $out );
}

/** Procura um conteúdo pelo grupo e idioma (qualquer estado). */
function dvs_seed_find( string $type, string $group, string $lang ): int {
	$ids = get_posts(
		array(
			'post_type'        => $type,
			'post_status'      => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'numberposts'      => 1,
			'fields'           => 'ids',
			'suppress_filters' => true,
			'meta_query'       => array( // phpcs:ignore
				array( 'key' => '_dvs_group', 'value' => $group ),
				array( 'key' => '_dvs_lang', 'value' => $lang ),
			),
		)
	);
	return $ids ? (int) $ids[0] : 0;
}

/** Cria ou atualiza um conteúdo traduzível. Devolve [id, criado?]. */
function dvs_seed_upsert( string $type, string $group, string $lang, array $post, array $meta, bool $force ): array {
	$id = dvs_seed_find( $type, $group, $lang );
	dvs_set_lang( $lang );
	if ( $id && ! $force ) {
		dvs_set_lang( null );
		return array( $id, false );
	}
	$post['post_type'] = $type;
	if ( $id ) {
		$post['ID'] = $id;
		$res        = wp_update_post( wp_slash( $post ), true );
	} else {
		$res = wp_insert_post( wp_slash( $post ), true );
	}
	dvs_set_lang( null );
	if ( is_wp_error( $res ) ) {
		WP_CLI::warning( "{$group} [{$lang}]: " . $res->get_error_message() );
		return array( 0, false );
	}
	$id = (int) $res;
	update_post_meta( $id, '_dvs_lang', $lang );
	update_post_meta( $id, '_dvs_group', $group );
	foreach ( $meta as $k => $v ) {
		update_post_meta( $id, $k, $v );
	}
	return array( $id, ! isset( $post['ID'] ) );
}

class DVS_CLI {

	/**
	 * Importa media, catálogo, páginas, ofertas, perguntas, menus e definições.
	 *
	 * ## OPTIONS
	 *
	 * [--force]
	 * : Reaplica os conteúdos originais por cima de edições locais.
	 *
	 * [--only=<parts>]
	 * : Partes separadas por vírgula: media,catalog,pages,offers,faq,menus,settings.
	 */
	public function seed( $args, $assoc ) {
		$force = ! empty( $assoc['force'] );
		$only  = isset( $assoc['only'] ) ? array_map( 'trim', explode( ',', $assoc['only'] ) ) : array( 'media', 'catalog', 'pages', 'offers', 'faq', 'menus', 'settings' );
		wp_suspend_cache_invalidation( false );
		foreach ( $only as $part ) {
			$m = 'seed_' . $part;
			if ( method_exists( $this, $m ) ) {
				WP_CLI::log( "→ {$part}" );
				$this->$m( $force );
			}
		}
		flush_rewrite_rules( false );
		WP_CLI::success( 'Importação concluída.' );
	}

	private function seed_media( bool $force ): void {
		$dir   = DVS_CORE_DIR . '/content/media/';
		$media = require DVS_CORE_DIR . '/content/media.php';
		$n     = 0;
		foreach ( $media as $file => $meta ) {
			$existed = dvs_media_id( $file );
			$id      = dvs_import_media( $dir . $file, $file, $meta );
			if ( ! $id ) {
				WP_CLI::warning( "Imagem não importada: {$file}" );
				continue;
			}
			$n += $existed ? 0 : 1;
		}
		WP_CLI::log( sprintf( '   %d imagens novas, %d no total.', $n, count( $media ) ) );
	}

	private function seed_catalog( bool $force ): void {
		$catalog = require DVS_CORE_DIR . '/content/catalog.php';
		$offers  = require DVS_CORE_DIR . '/content/offers.php';
		$new     = 0;
		foreach ( $catalog as $order => $item ) {
			$existing = get_page_by_path( $item['key'], OBJECT, 'dvs_item' );
			if ( $existing && ! $force ) {
				continue;
			}
			$data             = $item;
			$data['image_id'] = dvs_media_id( $item['image'] );
			$data['status']   = $item['status'] ?? 'source';
			unset( $data['key'] );
			$data   = dvs_sanitize_item( $data );
			$postarr = array(
				'post_type'   => 'dvs_item',
				'post_status' => 'publish',
				'post_title'  => $offers[ $item['key'] ]['en'][0] ?? $item['key'],
				'post_name'   => $item['key'],
				'menu_order'  => (int) $item['order'],
			);
			if ( $existing ) {
				$postarr['ID'] = $existing->ID;
				$id            = wp_update_post( $postarr );
			} else {
				$id = wp_insert_post( $postarr );
				++$new;
			}
			update_post_meta( $id, '_dvs_item', $data );
		}
		WP_CLI::log( sprintf( '   %d itens novos, %d no total.', $new, count( $catalog ) ) );
	}

	private function seed_pages( bool $force ): void {
		$pages = require DVS_CORE_DIR . '/content/pages.php';
		$count = 0;
		foreach ( array_keys( dvs_languages() ) as $lang ) {
			// A página inicial do idioma primeiro (é a mãe das restantes, exceto em inglês).
			$home_id = 0;
			foreach ( $pages as $key => $page ) {
				$parent = 0;
				if ( 'home' !== $key && 'en' !== $lang ) {
					$parent = $home_id ?: dvs_seed_find( 'page', 'page:home', $lang );
				}
				list( $id, $created ) = dvs_seed_upsert(
					'page',
					'page:' . $key,
					$lang,
					array(
						'post_status'  => 'publish',
						'post_title'   => dvs_seed_localize( $page['title'], $lang ),
						'post_name'    => dvs_seed_localize( $page['slug'], $lang ),
						'post_content' => dvs_seed_page_content( $page, $lang ),
						'post_parent'  => $parent,
						'menu_order'   => (int) $page['order'],
						'comment_status' => 'closed',
						'ping_status'  => 'closed',
					),
					array(
						'_dvs_seo_title' => isset( $page['seo_title'] ) ? dvs_seed_localize( $page['seo_title'], $lang ) : '',
						'_dvs_seo_desc'  => isset( $page['seo_desc'] ) ? dvs_seed_localize( $page['seo_desc'], $lang ) : '',
					),
					$force
				);
				if ( 'home' === $key ) {
					$home_id = $id;
				}
				$count += $created ? 1 : 0;
			}
		}
		WP_CLI::log( sprintf( '   %d páginas novas (%d por idioma).', $count, count( $pages ) ) );
	}

	private function seed_offers( bool $force ): void {
		$offers  = require DVS_CORE_DIR . '/content/offers.php';
		$catalog = require DVS_CORE_DIR . '/content/catalog.php';
		$cats    = array_column( $catalog, 'category', 'key' );
		$count   = 0;
		foreach ( $offers as $key => $langs ) {
			foreach ( $langs as $lang => $o ) {
				list( $title, $slug, $summary, $audience, $paras ) = $o;
				$faq = $o[5] ?? array();
				list( , $created ) = dvs_seed_upsert(
					'dvs_offer',
					'offer:' . $key,
					$lang,
					array(
						'post_status'  => 'publish',
						'post_title'   => $title,
						'post_name'    => $slug,
						'post_excerpt' => $summary,
						'post_content' => dvs_seed_paragraphs( $paras ),
					),
					array(
						'_dvs_item'     => $key,
						'_dvs_category' => $cats[ $key ] ?? 'courses',
						'_dvs_audience' => $audience,
						'_dvs_faq'      => $faq,
						'_dvs_seo_desc' => $summary,
					),
					$force
				);
				$count += $created ? 1 : 0;
			}
		}
		WP_CLI::log( sprintf( '   %d páginas de oferta novas (%d ofertas × 4 idiomas).', $count, count( $offers ) ) );
	}

	private function seed_faq( bool $force ): void {
		$faqs  = require DVS_CORE_DIR . '/content/faq.php';
		$count = 0;
		foreach ( $faqs as $i => $f ) {
			list( $key, $topic, $status, $q, $a ) = $f;
			foreach ( array_keys( dvs_languages() ) as $lang ) {
				list( , $created ) = dvs_seed_upsert(
					'dvs_faq',
					'faq:' . $key,
					$lang,
					array(
						'post_status'  => $status,
						'post_title'   => $q[ $lang ],
						'post_name'    => $key,
						'post_content' => dvs_seed_paragraphs( array( $a[ $lang ] ) ),
						'menu_order'   => $i,
					),
					array( '_dvs_topic' => $topic ),
					$force
				);
				$count += $created ? 1 : 0;
			}
		}
		WP_CLI::log( sprintf( '   %d perguntas novas.', $count ) );
	}

	private function seed_menus( bool $force ): void {
		$menus = require DVS_CORE_DIR . '/content/menus.php';
		$locs  = get_theme_mod( 'nav_menu_locations', array() );
		foreach ( $menus as $loc => $items ) {
			foreach ( dvs_languages() as $lang => $cfg ) {
				$name = 'DiversCape · ' . $loc . ' · ' . $cfg['short'];
				$menu = wp_get_nav_menu_object( $name );
				if ( $menu && ! $force ) {
					$locs[ "{$loc}_{$lang}" ] = $menu->term_id;
					continue;
				}
				if ( $menu ) {
					wp_delete_nav_menu( $menu->term_id );
				}
				$menu_id = wp_create_nav_menu( $name );
				$this->menu_items( $menu_id, $items, $lang, 0 );
				$locs[ "{$loc}_{$lang}" ] = $menu_id;
			}
		}
		set_theme_mod( 'nav_menu_locations', $locs );
		WP_CLI::log( '   Menus atribuídos às localizações do tema.' );
	}

	private function menu_items( int $menu_id, array $items, string $lang, int $parent ): void {
		foreach ( $items as $pos => $it ) {
			$label = $it[0][ $lang ];
			$url   = $it[1];
			$args  = array(
				'menu-item-title'     => $label,
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
				'menu-item-position'  => $pos + 1,
			);
			$plain = (bool) preg_match( '/^dvs:([a-z-]+)$/', $url, $m );
			$pid   = $plain ? dvs_seed_find( 'page', 'page:' . $m[1], $lang ) : 0;
			if ( $pid ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $pid;
			} else {
				$args['menu-item-type'] = 'custom';
				$args['menu-item-url']  = $url;
			}
			$id = wp_update_nav_menu_item( $menu_id, 0, $args );
			if ( ! empty( $it[2] ) && ! is_wp_error( $id ) ) {
				$this->menu_items( $menu_id, $it[2], $lang, (int) $id );
			}
		}
	}

	private function seed_settings( bool $force ): void {
		$home = dvs_seed_find( 'page', 'page:home', 'en' );
		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
		}
		update_option( 'blogname', 'DiversCape' );
		update_option( 'blogdescription', 'Diving in Sagres, Portugal' );
		update_option( 'default_comment_status', 'closed' );
		update_option( 'default_ping_status', 'closed' );
		if ( false === get_option( 'dvs_form_mode', false ) ) {
			update_option( 'dvs_form_mode', 'demo' );
		}
		if ( false === get_option( 'dvs_price_mode', false ) ) {
			update_option( 'dvs_price_mode', 'published' );
		}
		// Conteúdos de exemplo do WordPress.
		foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
			$p = get_page_by_path( $slug, OBJECT, $type );
			if ( $p && ! dvs_post_lang( $p->ID ) ) {
				wp_delete_post( $p->ID, true );
			}
		}
		$privacy = (int) get_option( 'wp_page_for_privacy_policy' );
		if ( $privacy && ! dvs_post_lang( $privacy ) ) {
			wp_delete_post( $privacy, true );
		}
		$en_privacy = dvs_seed_find( 'page', 'page:privacy', 'en' );
		if ( $en_privacy ) {
			update_option( 'wp_page_for_privacy_policy', $en_privacy );
		}
		WP_CLI::log( '   Página inicial, nome do site e opções definidos.' );
	}

	/**
	 * Resumo do conteúdo e verificação de cobertura do catálogo de origem.
	 */
	public function status( $args, $assoc ) {
		$rows = array();
		foreach ( dvs_languages() as $lang => $cfg ) {
			$count = static function ( $type, $status = 'publish' ) use ( $lang ) {
				return count(
					get_posts(
						array(
							'post_type'   => $type,
							'post_status' => $status,
							'numberposts' => -1,
							'fields'      => 'ids',
							'meta_query'  => array( array( 'key' => '_dvs_lang', 'value' => $lang ) ), // phpcs:ignore
						)
					)
				);
			};
			$rows[] = array(
				'idioma'          => $cfg['name'],
				'páginas'         => $count( 'page' ),
				'ofertas'         => $count( 'dvs_offer' ),
				'faq publicadas'  => $count( 'dvs_faq' ),
				'faq a validar'   => $count( 'dvs_faq', 'draft' ),
			);
		}
		WP_CLI\Utils\format_items( 'table', $rows, array_keys( $rows[0] ) );

		$expected = array();
		foreach ( array( 'exp' => 2, 'cur' => 6, 'efr' => 6, 'esp' => 12, 'tec' => 11, 'sai' => 12 ) as $p => $n ) {
			for ( $i = 1; $i <= $n; $i++ ) {
				$expected[] = "{$p}-{$i}";
			}
		}
		$covered  = array();
		$variants = 0;
		$missing_pages = array();
		foreach ( dvs_catalog() as $item ) {
			foreach ( $item['variants'] as $v ) {
				++$variants;
				foreach ( $v['rows'] as $r ) {
					$covered[ $r ] = true;
				}
			}
			foreach ( array_keys( dvs_languages() ) as $l ) {
				if ( ! dvs_group_post( 'offer:' . $item['key'], $l ) ) {
					$missing_pages[] = $item['key'] . " [{$l}]";
				}
			}
		}
		$missing = array_diff( $expected, array_keys( $covered ) );
		WP_CLI::log( sprintf( 'Catálogo: %d itens, %d variantes, %d/%d linhas de origem cobertas, %d imagens na galeria.', count( dvs_catalog() ), $variants, count( $expected ) - count( $missing ), count( $expected ), count( dvs_gallery_ids() ) ) );
		if ( $missing ) {
			WP_CLI::warning( 'Linhas de origem sem destino: ' . implode( ', ', $missing ) );
		}
		if ( $missing_pages ) {
			WP_CLI::warning( 'Itens sem página publicada: ' . implode( ', ', $missing_pages ) );
		}
		WP_CLI::log( 'Formulário: ' . dvs_form_mode() . ' · Preços: ' . dvs_price_mode() . ' · Página inicial: ' . get_permalink( (int) get_option( 'page_on_front' ) ) );
	}
}

WP_CLI::add_command( 'diverscape', 'DVS_CLI' );
