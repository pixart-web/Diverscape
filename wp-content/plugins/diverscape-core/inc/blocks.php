<?php
/**
 * Blocos dinâmicos DiversCape (renderização no servidor; editor genérico em assets/blocks-editor.js).
 *
 * Ligações internas aceitam o esquema `dvs:` resolvido no idioma da página:
 *   dvs:courses                → página «courses» no idioma atual
 *   dvs:offer:open-water-diver → página do curso no idioma atual
 *   dvs:request                → pedido de disponibilidade
 *   dvs:request:boat-dives:2-own → pedido com item e variante pré-selecionados
 *   dvs:courses?category=specialties → com parâmetros
 * Imagens aceitam o ID do anexo ou a chave (nome do ficheiro importado).
 */

defined( 'ABSPATH' ) || exit;

function dvs_resolve_url( string $url ): string {
	$url = trim( $url );
	if ( 0 !== strpos( $url, 'dvs:' ) ) {
		return $url;
	}
	$rest  = substr( $url, 4 );
	$query = '';
	$hash  = '';
	if ( false !== strpos( $rest, '#' ) ) {
		list( $rest, $hash ) = explode( '#', $rest, 2 );
	}
	if ( false !== strpos( $rest, '?' ) ) {
		list( $rest, $query ) = explode( '?', $rest, 2 );
	}
	$parts = explode( ':', $rest );
	if ( 'request' === $parts[0] ) {
		$item = isset( $parts[1] ) ? dvs_item( $parts[1] ) : null;
		return dvs_request_url( $item, $parts[2] ?? '' );
	}
	if ( 'offer' === $parts[0] && isset( $parts[1] ) ) {
		$item = dvs_item( $parts[1] );
		$out  = $item ? dvs_item_url( $item ) : dvs_url( 'page:courses' );
	} else {
		$out = dvs_url( 'page:' . $parts[0] );
	}
	if ( $query ) {
		$out .= ( false === strpos( $out, '?' ) ? '?' : '&' ) . $query;
	}
	return $out . ( $hash ? '#' . $hash : '' );
}

/** Parágrafos a partir de texto com linhas em branco. Permite <a>, <strong>, <em>. */
function dvs_paragraphs( string $text, string $class = '' ): string {
	$out = '';
	foreach ( preg_split( "/\n\s*\n/", trim( $text ) ) as $p ) {
		if ( '' === trim( $p ) ) {
			continue;
		}
		$p    = preg_replace_callback( '/href="(dvs:[^"]+)"/', static fn( $m ) => 'href="' . esc_url( dvs_resolve_url( $m[1] ) ) . '"', $p );
		$out .= '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . wp_kses( trim( $p ), array( 'a' => array( 'href' => true, 'target' => true, 'rel' => true ), 'strong' => array(), 'em' => array(), 'br' => array() ) ) . '</p>';
	}
	return $out;
}

function dvs_btn( string $label, string $url, string $variant = 'primary', array $extra = array() ): string {
	if ( '' === trim( $label ) || '' === trim( $url ) ) {
		return '';
	}
	$href     = dvs_resolve_url( $url );
	$external = 0 === strpos( $href, 'http' ) && false === strpos( $href, (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	$attrs    = $external ? ' target="_blank" rel="noopener"' : '';
	foreach ( $extra as $k => $v ) {
		$attrs .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
	}
	$sr = $external ? '<span class="visually-hidden"> ' . esc_html( dvs_t( 'new_tab' ) ) . '</span>' : '';
	return '<a class="dvs-btn dvs-btn--' . esc_attr( $variant ) . '" href="' . esc_url( $href ) . '"' . $attrs . '>' . esc_html( $label ) . $sr . '</a>';
}

function dvs_heading( array $a, string $default_level = '2', string $class = 'dvs-section__title' ): string {
	$out = '';
	if ( ! empty( $a['eyebrow'] ) ) {
		$out .= '<p class="dvs-eyebrow">' . esc_html( $a['eyebrow'] ) . '</p>';
	}
	if ( ! empty( $a['title'] ) ) {
		$l    = in_array( (string) ( $a['level'] ?? $default_level ), array( '1', '2', '3' ), true ) ? (string) ( $a['level'] ?? $default_level ) : $default_level;
		$out .= "<h{$l} class=\"" . esc_attr( $class ) . '">' . esc_html( $a['title'] ) . "</h{$l}>";
	}
	return $out;
}

/* ---------------------------------------------------------------------------------------------
 * Cartão de oferta (usado em vários blocos)
 * ------------------------------------------------------------------------------------------- */

function dvs_offer_card( array $item, array $opts = array() ): string {
	$oid     = dvs_offer_id( $item['key'] );
	$url     = dvs_item_url( $item );
	$title   = dvs_item_title( $item );
	$summary = $oid ? (string) get_post_field( 'post_excerpt', $oid ) : '';
	$img     = $item['image_id'] ?: $item['image'];
	$price   = dvs_item_price( $item );
	$dur     = dvs_item_duration( $item );
	$cat     = dvs_t( dvs_categories()[ $item['category'] ]['short'] ?? 'cat_courses_short' );
	$heading = $opts['heading'] ?? 'h3';
	$search  = remove_accents( mb_strtolower( $title . ' ' . $summary . ' ' . $cat . ' ' . implode( ' ', array_map( 'dvs_variant_label', $item['variants'] ) ) ) );

	$attrs = sprintf(
		' data-category="%s" data-audience="%s" data-search="%s"',
		esc_attr( $item['category'] ),
		esc_attr( implode( ' ', $item['audience'] ) ),
		esc_attr( $search )
	);
	if ( ! empty( $opts['hidden'] ) ) {
		$attrs .= ' hidden';
	}
	$aud = array();
	foreach ( $item['audience'] as $a ) {
		if ( isset( dvs_audiences()[ $a ] ) && 'kids' !== $a && dvs_t( dvs_audiences()[ $a ] ) !== $cat ) {
			$aud[] = dvs_t( dvs_audiences()[ $a ] );
		}
	}
	ob_start();
	?>
	<article class="dvs-card"<?php echo $attrs; // phpcs:ignore ?>>
		<div class="dvs-card__media"><?php echo dvs_img( $img, 'dvs-card', array( 'sizes' => '(min-width: 1100px) 360px, (min-width: 700px) 45vw, 92vw' ) ); // phpcs:ignore ?></div>
		<div class="dvs-card__body">
			<p class="dvs-card__cat"><?php echo esc_html( $cat ); ?><?php echo $aud ? ' · ' . esc_html( implode( ', ', $aud ) ) : ''; ?></p>
			<<?php echo esc_attr( $heading ); ?> class="dvs-card__title"><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $title ); ?></a></<?php echo esc_attr( $heading ); ?>>
			<?php if ( $summary ) : ?><p class="dvs-card__summary"><?php echo esc_html( $summary ); ?></p><?php endif; ?>
			<div class="dvs-card__foot">
				<?php if ( $dur ) : ?><span class="dvs-card__dur"><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 7v5l3 2" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg><?php echo esc_html( $dur ); ?></span><?php endif; ?>
				<span class="dvs-card__price<?php echo 'fixed' !== $price['kind'] ? ' is-consult' : ''; ?>"><?php echo esc_html( $price['text'] ); ?></span>
			</div>
		</div>
	</article>
	<?php
	return (string) ob_get_clean();
}

function dvs_price_notice( ?array $items = null ): string {
	return dvs_prices_need_notice( $items ) ? '<p class="dvs-price-notice"><svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18"><circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="2"/><path d="M12 11v6M12 7.5v.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>' . esc_html( dvs_t( 'price_notice' ) ) . '</p>' : '';
}

/* ---------------------------------------------------------------------------------------------
 * Definição dos blocos: atributos (tipo, rótulo, controlo) e renderização
 * ------------------------------------------------------------------------------------------- */

function dvs_block_definitions(): array {
	$txt  = static fn( $label ) => array( 'type' => 'string', 'default' => '', 'label' => $label, 'control' => 'text' );
	$area = static fn( $label ) => array( 'type' => 'string', 'default' => '', 'label' => $label, 'control' => 'textarea' );
	$img  = static fn( $label ) => array( 'type' => 'string', 'default' => '', 'label' => $label, 'control' => 'media' );
	$sel  = static fn( $label, $opts, $def ) => array( 'type' => 'string', 'default' => $def, 'label' => $label, 'control' => 'select', 'options' => $opts );
	$cats = array( '' => 'Todas' );
	foreach ( dvs_categories() as $k => $c ) {
		$cats[ $k ] = dvs_default_strings()[ $c['label'] ]['pt'];
	}
	$base = array( 'eyebrow' => $txt( 'Antetítulo' ), 'title' => $txt( 'Título' ) );

	return array(
		'hero'      => array(
			'title' => 'DiversCape — Destaque (hero)',
			'attrs' => array(
				'variant'   => $sel( 'Formato', array( 'home' => 'Homepage (ecrã inteiro)', 'page' => 'Página (médio)' ), 'page' ),
				'eyebrow'   => $txt( 'Antetítulo' ),
				'title'     => $txt( 'Título (H1)' ),
				'lead'      => $area( 'Texto de apoio' ),
				'image'     => $img( 'Fotografia' ),
				'focus'     => $txt( 'Enquadramento (ex.: 50% 40%)' ),
				'btn1Label' => $txt( 'Botão principal — texto' ),
				'btn1Url'   => $txt( 'Botão principal — ligação' ),
				'btn2Label' => $txt( 'Botão secundário — texto' ),
				'btn2Url'   => $txt( 'Botão secundário — ligação' ),
				'caption'   => $txt( 'Nota de rodapé do hero (local, legenda)' ),
			),
		),
		'doors'     => array(
			'title' => 'DiversCape — Escolher por objetivo',
			'attrs' => array_merge(
				$base,
				array( 'intro' => $area( 'Introdução' ) ),
				array_merge(
					...array_map(
						static fn( $n ) => array(
							"d{$n}Title" => $txt( "Entrada {$n} — título" ),
							"d{$n}Text"  => $area( "Entrada {$n} — texto" ),
							"d{$n}Url"   => $txt( "Entrada {$n} — ligação" ),
							"d{$n}Image" => $img( "Entrada {$n} — fotografia" ),
							"d{$n}Cta"   => $txt( "Entrada {$n} — texto do botão" ),
						),
						array( 1, 2, 3 )
					)
				)
			),
		),
		'offers'    => array(
			'title' => 'DiversCape — Cursos e experiências',
			'attrs' => array_merge(
				$base,
				array(
					'intro'     => $area( 'Introdução' ),
					'source'    => $sel( 'Origem', array( 'featured' => 'Destaques (definidos no catálogo)', 'category' => 'Categoria' ), 'featured' ),
					'category'  => $sel( 'Categoria', $cats, '' ),
					'exclude'   => $txt( 'Excluir (chaves separadas por vírgula)' ),
					'count'     => array( 'type' => 'number', 'default' => 4, 'label' => 'Número máximo', 'control' => 'range', 'min' => 1, 'max' => 20 ),
					'layout'    => $sel( 'Apresentação', array( 'cards' => 'Cartões', 'rows' => 'Linhas editoriais' ), 'cards' ),
					'linkLabel' => $txt( 'Ligação — texto' ),
					'linkUrl'   => $txt( 'Ligação — endereço' ),
					'tone'      => $sel( 'Fundo', array( 'light' => 'Claro', 'sand' => 'Areia', 'deep' => 'Azul profundo' ), 'light' ),
				)
			),
		),
		'surface'   => array(
			'title' => 'DiversCape — Acima e abaixo de água',
			'attrs' => array_merge(
				$base,
				array(
					'text'       => $area( 'Texto' ),
					'above'      => $img( 'Fotografia acima de água' ),
					'below'      => $img( 'Fotografia subaquática' ),
					'belowTitle' => $txt( 'Título da parte subaquática' ),
					'belowText'  => $area( 'Texto da parte subaquática' ),
					'linkLabel'  => $txt( 'Ligação — texto' ),
					'linkUrl'    => $txt( 'Ligação — endereço' ),
				)
			),
		),
		'training'  => array(
			'title' => 'DiversCape — Mapa da formação',
			'attrs' => array_merge( $base, array( 'intro' => $area( 'Introdução' ) ) ),
		),
		'story'     => array(
			'title' => 'DiversCape — Bloco editorial (imagem e texto)',
			'attrs' => array_merge(
				$base,
				array(
					'text'      => $area( 'Texto (linha em branco separa parágrafos)' ),
					'image'     => $img( 'Fotografia' ),
					'image2'    => $img( 'Segunda fotografia (opcional)' ),
					'reverse'   => array( 'type' => 'boolean', 'default' => false, 'label' => 'Imagem à direita', 'control' => 'toggle' ),
					'tone'      => $sel( 'Fundo', array( 'light' => 'Claro', 'sand' => 'Areia', 'deep' => 'Azul profundo' ), 'light' ),
					'points'    => $area( 'Pontos (um por linha, opcional)' ),
					'linkLabel' => $txt( 'Ligação — texto' ),
					'linkUrl'   => $txt( 'Ligação — endereço' ),
				)
			),
		),
		'trust'     => array(
			'title' => 'DiversCape — Pessoas e confiança',
			'attrs' => array_merge(
				$base,
				array(
					'text'      => $area( 'Texto' ),
					'image'     => $img( 'Fotografia principal' ),
					'image2'    => $img( 'Fotografia secundária' ),
					'points'    => $area( 'Pontos (um por linha)' ),
					'linkLabel' => $txt( 'Ligação — texto' ),
					'linkUrl'   => $txt( 'Ligação — endereço' ),
				)
			),
		),
		'gallery'   => array(
			'title' => 'DiversCape — Galeria',
			'attrs' => array_merge(
				$base,
				array(
					'count'     => array( 'type' => 'number', 'default' => 8, 'label' => 'Fotografias (0 = todas)', 'control' => 'range', 'min' => 0, 'max' => 40 ),
					'linkLabel' => $txt( 'Ligação — texto' ),
					'linkUrl'   => $txt( 'Ligação — endereço' ),
				)
			),
		),
		'reviews'   => array(
			'title' => 'DiversCape — Avaliações',
			'attrs' => array_merge( $base, array( 'text' => $area( 'Texto' ) ) ),
		),
		'invite'    => array(
			'title' => 'DiversCape — Convite final',
			'attrs' => array_merge(
				$base,
				array(
					'text'      => $area( 'Texto' ),
					'image'     => $img( 'Fotografia de fundo' ),
					'btn1Label' => $txt( 'Botão — texto' ),
					'btn1Url'   => $txt( 'Botão — ligação' ),
				)
			),
		),
		'catalog'   => array(
			'title' => 'DiversCape — Catálogo com pesquisa e filtros',
			'attrs' => array( 'categories' => $txt( 'Categorias (vazio = todas; separadas por vírgula)' ) ),
		),
		'prices'    => array(
			'title' => 'DiversCape — Tabela de preços',
			'attrs' => array( 'categories' => $txt( 'Categorias (vazio = todas; separadas por vírgula)' ) ),
		),
		'packages'  => array(
			'title' => 'DiversCape — Pacotes de mergulho (certificados)',
			'attrs' => array_merge( $base, array( 'intro' => $area( 'Introdução' ) ) ),
		),
		'request'   => array(
			'title' => 'DiversCape — Pedido de disponibilidade',
			'attrs' => array(),
		),
		'contact'   => array(
			'title' => 'DiversCape — Contactos',
			'attrs' => array_merge( $base, array( 'text' => $area( 'Texto' ) ) ),
		),
		'faq'       => array(
			'title' => 'DiversCape — Perguntas frequentes',
			'attrs' => array_merge(
				$base,
				array(
					'topic'     => $sel( 'Tema', array( '' => 'Todos', 'general' => 'Geral', 'experiences' => 'Experiências', 'courses' => 'Cursos', 'certified' => 'Certificados', 'technical' => 'Técnico', 'request' => 'Pedidos' ), '' ),
					'limit'     => array( 'type' => 'number', 'default' => 0, 'label' => 'Máximo (0 = todas)', 'control' => 'range', 'min' => 0, 'max' => 30 ),
					'linkLabel' => $txt( 'Ligação — texto' ),
					'linkUrl'   => $txt( 'Ligação — endereço' ),
				)
			),
		),
		'links'     => array(
			'title' => 'DiversCape — Ligações úteis',
			'attrs' => array_merge( $base, array( 'items' => $area( 'Ligações: «Texto | URL | descrição» uma por linha' ) ) ),
		),
	);
}

add_action(
	'init',
	static function () {
		foreach ( dvs_block_definitions() as $name => $def ) {
			$attrs = array();
			foreach ( $def['attrs'] as $k => $a ) {
				$attrs[ $k ] = array( 'type' => $a['type'], 'default' => $a['default'] );
			}
			$attrs['className'] = array( 'type' => 'string', 'default' => '' );
			$attrs['anchor']    = array( 'type' => 'string', 'default' => '' );
			register_block_type(
				'diverscape/' . $name,
				array(
					'api_version'     => 3,
					'title'           => $def['title'],
					'category'        => 'diverscape',
					'attributes'      => $attrs,
					'supports'        => array( 'html' => false, 'anchor' => true ),
					'render_callback' => static function ( $a ) use ( $name ) {
						$fn = 'dvs_block_' . str_replace( '-', '_', $name );
						return function_exists( $fn ) ? $fn( $a ) : '';
					},
				)
			);
		}
	}
);

add_filter(
	'block_categories_all',
	static function ( array $cats ) {
		array_unshift( $cats, array( 'slug' => 'diverscape', 'title' => 'DiversCape' ) );
		return $cats;
	}
);

add_action(
	'enqueue_block_editor_assets',
	static function () {
		wp_enqueue_script( 'dvs-blocks-editor', DVS_CORE_URL . 'assets/blocks-editor.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render' ), DVS_CORE_VERSION, true );
		$defs = array();
		foreach ( dvs_block_definitions() as $name => $def ) {
			$defs[ 'diverscape/' . $name ] = array( 'title' => $def['title'], 'attrs' => $def['attrs'] );
		}
		$post = get_post();
		wp_add_inline_script( 'dvs-blocks-editor', 'window.dvsBlocks=' . wp_json_encode( array( 'blocks' => $defs, 'lang' => $post ? ( dvs_post_lang( $post->ID ) ?: 'en' ) : 'en' ) ) . ';', 'before' );
	}
);

/* Pré-visualização no editor: renderizar no idioma da página editada. */
add_filter(
	'rest_pre_dispatch',
	static function ( $result, $server, WP_REST_Request $req ) {
		if ( 0 === strpos( $req->get_route(), '/wp/v2/block-renderer/diverscape/' ) ) {
			$l = sanitize_key( (string) $req->get_param( 'dvs_lang' ) );
			dvs_set_lang( dvs_is_lang( $l ) ? $l : 'en' );
		}
		return $result;
	},
	10,
	3
);

function dvs_section_attrs( array $a, string $base ): string {
	$cls = trim( $base . ' ' . ( $a['className'] ?? '' ) );
	$id  = ! empty( $a['anchor'] ) ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
	return 'class="' . esc_attr( $cls ) . '"' . $id;
}

/* ---------------------------------------------------------------------------------------------
 * Renderização
 * ------------------------------------------------------------------------------------------- */

function dvs_block_hero( array $a ): string {
	$home  = 'home' === $a['variant'];
	$plain = ! $home && '' === trim( (string) $a['image'] );
	$focus = preg_match( '/^\d{1,3}% \d{1,3}%$/', (string) $a['focus'] ) ? $a['focus'] : '50% 50%';
	$img   = dvs_img(
		$a['image'],
		'full',
		array(
			'class'   => 'dvs-hero__img',
			'loading' => 'eager',
			'sizes'   => '100vw',
			'style'   => 'object-position:' . $focus,
		)
	);
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-hero dvs-hero--' . ( $home ? 'home' : ( $plain ? 'plain' : 'page' ) ) ); // phpcs:ignore ?>>
		<?php if ( ! $plain ) : ?>
		<div class="dvs-hero__media" data-parallax><?php echo $img; // phpcs:ignore ?></div>
		<div class="dvs-hero__shade" aria-hidden="true"></div>
		<?php endif; ?>
		<div class="dvs-hero__inner dvs-wrap">
			<?php if ( ! $home ) : ?>
				<?php echo dvs_breadcrumbs_html(); // phpcs:ignore ?>
			<?php endif; ?>
			<?php if ( $a['eyebrow'] ) : ?><p class="dvs-eyebrow dvs-hero__eyebrow" data-reveal><?php echo esc_html( $a['eyebrow'] ); ?></p><?php endif; ?>
			<h1 class="dvs-hero__title" data-reveal><?php echo dvs_hero_title( $a['title'] ); // phpcs:ignore ?></h1>
			<?php if ( $a['lead'] ) : ?><p class="dvs-hero__lead" data-reveal><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
			<?php if ( $a['btn1Label'] || $a['btn2Label'] ) : ?>
				<div class="dvs-hero__actions" data-reveal>
					<?php echo dvs_btn( $a['btn1Label'], $a['btn1Url'], 'primary' ); // phpcs:ignore ?>
					<?php echo dvs_btn( $a['btn2Label'], $a['btn2Url'], 'light' ); // phpcs:ignore ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $a['caption'] ) : ?><p class="dvs-hero__caption dvs-wrap"><?php echo esc_html( $a['caption'] ); ?></p><?php endif; ?>
		<?php if ( $home ) : ?><div class="dvs-hero__wave" aria-hidden="true"><svg viewBox="0 0 1440 80" preserveAspectRatio="none"><path d="M0 40c160 26 320 26 480 0s320-26 480 0 320 26 480 0v40H0z"/></svg></div><?php endif; ?>
	</section>
	<?php
	return (string) ob_get_clean();
}

/** Título do hero: o texto depois do primeiro ponto final fica em destaque (segunda linha). */
function dvs_hero_title( string $title ): string {
	$parts = preg_split( '/(?<=\.)\s+/u', trim( $title ), 2 );
	if ( 2 === count( $parts ) ) {
		return esc_html( $parts[0] ) . ' <span class="dvs-hero__title-accent">' . esc_html( $parts[1] ) . '</span>';
	}
	return esc_html( $title );
}

function dvs_breadcrumbs_html(): string {
	$crumbs = dvs_breadcrumbs();
	if ( count( $crumbs ) < 2 ) {
		return '';
	}
	$out  = '<nav class="dvs-crumbs" aria-label="' . esc_attr( dvs_t( 'breadcrumb' ) ) . '"><ol>';
	$last = count( $crumbs ) - 1;
	foreach ( $crumbs as $i => $c ) {
		$out .= $i === $last
			? '<li><span aria-current="page">' . esc_html( $c[0] ) . '</span></li>'
			: '<li><a href="' . esc_url( $c[1] ) . '">' . esc_html( $c[0] ) . '</a></li>';
	}
	return $out . '</ol></nav>';
}

function dvs_block_doors( array $a ): string {
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-doors' ); // phpcs:ignore ?>>
		<div class="dvs-wrap">
			<header class="dvs-section__head" data-reveal><?php echo dvs_heading( $a ); // phpcs:ignore ?><?php echo $a['intro'] ? dvs_paragraphs( $a['intro'], 'dvs-section__intro' ) : ''; // phpcs:ignore ?></header>
			<div class="dvs-doors__grid">
				<?php for ( $n = 1; $n <= 3; $n++ ) : ?>
					<?php if ( empty( $a[ "d{$n}Title" ] ) ) { continue; } ?>
					<article class="dvs-door" data-reveal>
						<div class="dvs-door__media"><?php echo dvs_img( $a[ "d{$n}Image" ], 'dvs-portrait', array( 'sizes' => '(min-width: 900px) 30vw, 92vw' ) ); // phpcs:ignore ?></div>
						<div class="dvs-door__body">
							<p class="dvs-door__num" aria-hidden="true">0<?php echo (int) $n; ?></p>
							<h3 class="dvs-door__title"><a href="<?php echo esc_url( dvs_resolve_url( $a[ "d{$n}Url" ] ) ); ?>"><?php echo esc_html( $a[ "d{$n}Title" ] ); ?></a></h3>
							<p class="dvs-door__text"><?php echo esc_html( $a[ "d{$n}Text" ] ); ?></p>
							<?php if ( ! empty( $a[ "d{$n}Cta" ] ) ) : ?><span class="dvs-door__cta" aria-hidden="true"><?php echo esc_html( $a[ "d{$n}Cta" ] ); ?> <span class="dvs-arrow">→</span></span><?php endif; ?>
						</div>
					</article>
				<?php endfor; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_offers( array $a ): string {
	$items = dvs_catalog();
	if ( 'category' === $a['source'] && $a['category'] ) {
		$items = dvs_items_by_category( $a['category'] );
	} else {
		$items = array_filter( $items, static fn( $i ) => $i['featured'] );
	}
	$exclude = array_filter( array_map( 'trim', explode( ',', (string) $a['exclude'] ) ) );
	$items   = array_filter( $items, static fn( $i ) => ! in_array( $i['key'], $exclude, true ) && dvs_offer_id( $i['key'] ) );
	$items   = array_slice( $items, 0, max( 1, (int) $a['count'] ) );
	if ( ! $items ) {
		return '';
	}
	$rows = 'rows' === $a['layout'];
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-offers dvs-tone--' . esc_attr( $a['tone'] ) ); // phpcs:ignore ?>>
		<div class="dvs-wrap">
			<?php if ( $a['title'] || $a['eyebrow'] ) : ?>
				<header class="dvs-section__head dvs-section__head--split" data-reveal>
					<div><?php echo dvs_heading( $a ); // phpcs:ignore ?><?php echo $a['intro'] ? dvs_paragraphs( $a['intro'], 'dvs-section__intro' ) : ''; // phpcs:ignore ?></div>
					<?php if ( $a['linkLabel'] ) : ?><a class="dvs-link-arrow" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a><?php endif; ?>
				</header>
			<?php endif; ?>
			<?php if ( $rows ) : ?>
				<div class="dvs-feature-list">
					<?php foreach ( array_values( $items ) as $i => $item ) : ?>
						<?php echo dvs_offer_feature( $item, 1 === $i % 2 ); // phpcs:ignore ?>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<div class="dvs-cards dvs-cards--<?php echo count( $items ) >= 4 ? 4 : count( $items ); ?>">
					<?php foreach ( $items as $item ) : ?>
						<?php echo dvs_offer_card( $item ); // phpcs:ignore ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php echo dvs_price_notice( $items ); // phpcs:ignore ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/** Linha editorial larga para uma oferta (páginas de categoria). */
function dvs_offer_feature( array $item, bool $reverse = false ): string {
	$oid     = dvs_offer_id( $item['key'] );
	$summary = $oid ? (string) get_post_field( 'post_excerpt', $oid ) : '';
	$aud     = $oid ? (string) get_post_meta( $oid, '_dvs_audience', true ) : '';
	$facts   = dvs_item_facts( $item );
	ob_start();
	?>
	<article class="dvs-feature<?php echo $reverse ? ' is-reverse' : ''; ?>" data-reveal>
		<div class="dvs-feature__media"><?php echo dvs_img( $item['image_id'] ?: $item['image'], 'large', array( 'sizes' => '(min-width: 900px) 50vw, 92vw' ) ); // phpcs:ignore ?></div>
		<div class="dvs-feature__body">
			<h3 class="dvs-feature__title"><a href="<?php echo esc_url( dvs_item_url( $item ) ); ?>"><?php echo esc_html( dvs_item_title( $item ) ); ?></a></h3>
			<?php if ( $summary ) : ?><p class="dvs-feature__summary"><?php echo esc_html( $summary ); ?></p><?php endif; ?>
			<?php if ( $aud ) : ?><p class="dvs-feature__aud"><strong><?php dvs_e( 'fact_audience' ); ?>:</strong> <?php echo esc_html( $aud ); ?></p><?php endif; ?>
			<dl class="dvs-facts dvs-facts--inline">
				<?php foreach ( $facts as $f ) : ?><div><dt><?php echo esc_html( $f[0] ); ?></dt><dd><?php echo esc_html( $f[1] ); ?></dd></div><?php endforeach; ?>
			</dl>
			<div class="dvs-feature__actions">
				<?php echo dvs_btn( dvs_t( 'view_details' ), dvs_item_url( $item ), 'secondary', array( 'aria-label' => dvs_t( 'view_details_of', dvs_item_title( $item ) ) ) ); // phpcs:ignore ?>
				<?php echo dvs_btn( dvs_t( 'request_this' ), dvs_request_url( $item ), 'ghost', array( 'aria-label' => dvs_t( 'request_option', dvs_item_title( $item ) ) ) ); // phpcs:ignore ?>
			</div>
		</div>
	</article>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_surface( array $a ): string {
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-surface' ); // phpcs:ignore ?>>
		<div class="dvs-surface__above">
			<div class="dvs-surface__sky dvs-wrap" data-reveal>
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( $a['text'], 'dvs-surface__text' ); // phpcs:ignore ?>
			</div>
			<div class="dvs-surface__horizon"><?php echo dvs_img( $a['above'], 'full', array( 'sizes' => '100vw', 'class' => 'dvs-surface__pano' ) ); // phpcs:ignore ?></div>
		</div>
		<div class="dvs-surface__line" aria-hidden="true"><svg viewBox="0 0 1440 120" preserveAspectRatio="none"><path class="w1" d="M0 60c120-24 240-24 360 0s240 24 360 0 240-24 360 0 240 24 360 0v60H0z"/><path class="w2" d="M0 78c160-20 320-20 480 0s320 20 480 0 320-20 480 0v42H0z"/></svg></div>
		<div class="dvs-surface__below">
			<div class="dvs-surface__deep dvs-wrap">
				<div class="dvs-surface__photo" data-reveal><?php echo dvs_img( $a['below'], 'large', array( 'sizes' => '(min-width: 900px) 55vw, 92vw' ) ); // phpcs:ignore ?></div>
				<div class="dvs-surface__copy" data-reveal>
					<?php if ( $a['belowTitle'] ) : ?><h3 class="dvs-surface__below-title"><?php echo esc_html( $a['belowTitle'] ); ?></h3><?php endif; ?>
					<?php echo dvs_paragraphs( $a['belowText'] ); // phpcs:ignore ?>
					<?php if ( $a['linkLabel'] ) : ?><p><a class="dvs-link-arrow dvs-link-arrow--light" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a></p><?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_training( array $a ): string {
	$groups = array(
		'experiences' => array( 'url' => 'dvs:experiences', 'icon' => 'bubbles' ),
		'courses'     => array( 'url' => 'dvs:courses?category=courses#catalog', 'icon' => 'fins' ),
		'specialties' => array( 'url' => 'dvs:courses?category=specialties#catalog', 'icon' => 'star' ),
		'first-aid'   => array( 'url' => 'dvs:courses?category=first-aid#catalog', 'icon' => 'cross' ),
		'technical'   => array( 'url' => 'dvs:technical-diving', 'icon' => 'tank' ),
	);
	$icons  = array(
		'bubbles' => '<circle cx="9" cy="15" r="5"/><circle cx="17" cy="8" r="3"/><circle cx="19" cy="17" r="2"/>',
		'fins'    => '<path d="M5 20c2-6 3-11 3-16h3c0 5 1 10 3 16z"/><path d="M14 20c1-5 2-9 2-14h3c0 5-1 9 0 14z"/>',
		'star'    => '<path d="M12 3l2.6 5.6 6.1.7-4.5 4.2 1.2 6L12 16.6 6.6 19.5l1.2-6L3.3 9.3l6.1-.7z"/>',
		'cross'   => '<path d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6z"/>',
		'tank'    => '<rect x="8" y="6" width="8" height="15" rx="4"/><path d="M10 6V3h4v3M12 3h5"/>',
	);
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-training dvs-tone--sand' ); // phpcs:ignore ?>>
		<div class="dvs-wrap">
			<header class="dvs-section__head" data-reveal><?php echo dvs_heading( $a ); // phpcs:ignore ?><?php echo $a['intro'] ? dvs_paragraphs( $a['intro'], 'dvs-section__intro' ) : ''; // phpcs:ignore ?></header>
			<ul class="dvs-training__list" role="list">
				<?php foreach ( $groups as $cat => $g ) : ?>
					<?php $n = count( array_filter( dvs_items_by_category( $cat ), static fn( $i ) => dvs_offer_id( $i['key'] ) ) ); ?>
					<li class="dvs-training__item" data-reveal>
						<a class="dvs-training__link" href="<?php echo esc_url( dvs_resolve_url( $g['url'] ) ); ?>">
							<svg class="dvs-training__icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"><?php echo $icons[ $g['icon'] ]; // phpcs:ignore ?></svg>
							<span class="dvs-training__name"><?php echo esc_html( dvs_t( dvs_categories()[ $cat ]['label'] ) ); ?></span>
							<span class="dvs-training__desc"><?php echo esc_html( dvs_t( 'catdesc_' . str_replace( '-', '_', $cat ) ) ); ?></span>
							<span class="dvs-training__count"><?php echo esc_html( 1 === $n ? dvs_t( 'offers_count_one' ) : dvs_t( 'offers_count', $n ) ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_points( string $points ): string {
	$lines = array_filter( array_map( 'trim', preg_split( "/\r?\n/", $points ) ) );
	if ( ! $lines ) {
		return '';
	}
	$out = '<ul class="dvs-points" role="list">';
	foreach ( $lines as $l ) {
		$out .= '<li>' . wp_kses( $l, array( 'strong' => array(), 'a' => array( 'href' => true ) ) ) . '</li>';
	}
	return $out . '</ul>';
}

function dvs_block_story( array $a ): string {
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-story dvs-tone--' . esc_attr( $a['tone'] ) . ( $a['reverse'] ? ' is-reverse' : '' ) . ( $a['image2'] ? ' has-pair' : '' ) ); // phpcs:ignore ?>>
		<div class="dvs-wrap dvs-story__grid">
			<div class="dvs-story__media" data-reveal>
				<?php echo dvs_img( $a['image'], 'large', array( 'sizes' => '(min-width: 900px) 50vw, 92vw', 'class' => 'dvs-story__img' ) ); // phpcs:ignore ?>
				<?php if ( $a['image2'] ) : ?><?php echo dvs_img( $a['image2'], 'dvs-card', array( 'sizes' => '(min-width: 900px) 22vw, 45vw', 'class' => 'dvs-story__img2' ) ); // phpcs:ignore ?><?php endif; ?>
			</div>
			<div class="dvs-story__body" data-reveal>
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( $a['text'] ); // phpcs:ignore ?>
				<?php echo dvs_points( (string) $a['points'] ); // phpcs:ignore ?>
				<?php if ( $a['linkLabel'] ) : ?><p><a class="dvs-link-arrow<?php echo 'deep' === $a['tone'] ? ' dvs-link-arrow--light' : ''; ?>" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a></p><?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_trust( array $a ): string {
	$padi   = dvs_media_id( 'padi-logo.png' );
	$tecrec = dvs_media_id( 'padi-tecrec-logo.png' );
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-trust dvs-tone--deep' ); // phpcs:ignore ?>>
		<div class="dvs-wrap dvs-trust__grid">
			<div class="dvs-trust__media" data-reveal>
				<?php echo dvs_img( $a['image'], 'large', array( 'sizes' => '(min-width: 900px) 55vw, 92vw', 'class' => 'dvs-trust__img' ) ); // phpcs:ignore ?>
				<?php if ( $a['image2'] ) : ?><?php echo dvs_img( $a['image2'], 'dvs-card', array( 'sizes' => '(min-width: 900px) 24vw, 50vw', 'class' => 'dvs-trust__img2' ) ); // phpcs:ignore ?><?php endif; ?>
			</div>
			<div class="dvs-trust__body" data-reveal>
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( $a['text'] ); // phpcs:ignore ?>
				<?php echo dvs_points( (string) $a['points'] ); // phpcs:ignore ?>
				<div class="dvs-trust__creds">
					<?php if ( $padi ) : ?><span class="dvs-cred"><?php echo dvs_img( $padi, 'medium', array( 'class' => 'dvs-cred__logo' ) ); // phpcs:ignore ?></span><?php endif; ?>
					<?php if ( $tecrec ) : ?><span class="dvs-cred dvs-cred--wide"><?php echo dvs_img( $tecrec, 'medium', array( 'class' => 'dvs-cred__logo' ) ); // phpcs:ignore ?></span><?php endif; ?>
					<span class="dvs-cred dvs-cred--text"><?php echo esc_html( dvs_t( 'legal_ref', dvs_business( 'legal_ref' ) ) ); ?></span>
				</div>
				<?php if ( $a['linkLabel'] ) : ?><p><a class="dvs-link-arrow dvs-link-arrow--light" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a></p><?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_gallery( array $a ): string {
	$ids   = dvs_gallery_ids();
	$count = (int) $a['count'];
	if ( $count > 0 ) {
		$ids = array_slice( $ids, 0, $count );
	}
	if ( ! $ids ) {
		return '';
	}
	$total = count( $ids );
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-gallery' . ( $count > 0 ? ' is-mosaic' : ' is-full' ) ); // phpcs:ignore ?> data-dvs-gallery>
		<div class="dvs-wrap">
			<?php if ( $a['title'] || $a['eyebrow'] ) : ?>
				<header class="dvs-section__head dvs-section__head--split" data-reveal>
					<div><?php echo dvs_heading( $a ); // phpcs:ignore ?></div>
					<?php if ( $a['linkLabel'] ) : ?><a class="dvs-link-arrow" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a><?php endif; ?>
				</header>
			<?php endif; ?>
			<ul class="dvs-gallery__grid" role="list">
				<?php foreach ( $ids as $i => $id ) : ?>
					<?php
					$full    = wp_get_attachment_image_src( $id, 'full' );
					$caption = dvs_media_caption( $id ) ?: dvs_media_alt( $id );
					?>
					<li class="dvs-gallery__item">
						<a class="dvs-gallery__link" href="<?php echo esc_url( $full[0] ?? '' ); ?>" data-index="<?php echo (int) $i; ?>" data-full="<?php echo esc_url( $full[0] ?? '' ); ?>" data-w="<?php echo (int) ( $full[1] ?? 0 ); ?>" data-h="<?php echo (int) ( $full[2] ?? 0 ); ?>" data-caption="<?php echo esc_attr( $caption ); ?>" aria-label="<?php echo esc_attr( dvs_t( 'gallery_open', $caption ) ); ?>">
							<?php echo dvs_img( $id, 'dvs-card', array( 'sizes' => '(min-width: 1100px) 25vw, (min-width: 600px) 33vw, 50vw' ) ); // phpcs:ignore ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<dialog class="dvs-lightbox" aria-label="<?php echo esc_attr( dvs_t( 'gallery_dialog' ) ); ?>" data-lightbox>
			<figure class="dvs-lightbox__figure">
				<img class="dvs-lightbox__img" alt="" data-lb-img>
				<figcaption class="dvs-lightbox__caption"><span data-lb-caption></span> <span class="dvs-lightbox__count" data-lb-count></span></figcaption>
			</figure>
			<button type="button" class="dvs-lightbox__btn dvs-lightbox__prev" data-lb-prev><span class="visually-hidden"><?php dvs_e( 'gallery_prev' ); ?></span><svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="M15 5l-7 7 7 7" fill="none" stroke="currentColor" stroke-width="2"/></svg></button>
			<button type="button" class="dvs-lightbox__btn dvs-lightbox__next" data-lb-next><span class="visually-hidden"><?php dvs_e( 'gallery_next' ); ?></span><svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2"/></svg></button>
			<button type="button" class="dvs-lightbox__btn dvs-lightbox__close" data-lb-close><span class="visually-hidden"><?php dvs_e( 'gallery_close' ); ?></span><svg aria-hidden="true" viewBox="0 0 24 24" width="28" height="28"><path d="M6 6l12 12M18 6L6 18" fill="none" stroke="currentColor" stroke-width="2"/></svg></button>
			<template data-lb-count-tpl><?php echo esc_html( dvs_t( 'gallery_count', 999, $total ) ); ?></template>
		</dialog>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_reviews( array $a ): string {
	$ta_langs = array( 'en' => 'en_UK', 'pt' => 'pt', 'de' => 'de', 'es' => 'es' );
	$src      = sprintf(
		'https://www.jscache.com/wejs?wtype=selfserveprop&uniq=299&locationId=%s&lang=%s&rating=true&nreviews=4&writereviewlink=true&popIdx=true&iswide=true&border=true&display_version=2',
		rawurlencode( dvs_business( 'tripadvisor_id' ) ),
		$ta_langs[ dvs_lang() ]
	);
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-reviews' ); // phpcs:ignore ?> data-dvs-reviews data-src="<?php echo esc_url( $src ); ?>">
		<div class="dvs-wrap dvs-reviews__grid">
			<div class="dvs-reviews__copy" data-reveal>
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( (string) $a['text'] ); // phpcs:ignore ?>
				<p class="dvs-reviews__links">
					<?php echo dvs_btn( dvs_t( 'reviews_link' ), dvs_business( 'tripadvisor' ), 'secondary' ); // phpcs:ignore ?>
					<a class="dvs-link-arrow" href="<?php echo esc_url( dvs_business( 'facebook' ) ); ?>" target="_blank" rel="noopener"><?php dvs_e( 'reviews_facebook' ); ?><span class="visually-hidden"> <?php dvs_e( 'new_tab' ); ?></span> <span class="dvs-arrow" aria-hidden="true">↗</span></a>
				</p>
			</div>
			<div class="dvs-reviews__widget" data-reveal>
				<div class="dvs-reviews__consent" data-reviews-consent>
					<svg aria-hidden="true" viewBox="0 0 64 40" width="64" height="40"><circle cx="16" cy="20" r="12" fill="none" stroke="currentColor" stroke-width="3"/><circle cx="48" cy="20" r="12" fill="none" stroke="currentColor" stroke-width="3"/><circle cx="16" cy="20" r="4" fill="currentColor"/><circle cx="48" cy="20" r="4" fill="currentColor"/></svg>
					<p><?php dvs_e( 'reviews_consent' ); ?></p>
					<button type="button" class="dvs-btn dvs-btn--ghost" data-reviews-load hidden><?php dvs_e( 'reviews_load' ); ?></button>
					<p class="dvs-reviews__status" role="status" data-reviews-status></p>
				</div>
				<div id="TA_selfserveprop299" class="TA_selfserveprop" data-reviews-target hidden>
					<ul id="AVL5Jx" class="TA_links D8Ukvj"><li id="h5ShZo" class="rt8ylf"><a target="_blank" rel="noopener" href="https://www.tripadvisor.co.uk/">Tripadvisor</a></li></ul>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_contact_list( bool $light = false ): string {
	$addr = dvs_business( 'street' ) . ', ' . dvs_business( 'postcode' ) . ' ' . dvs_business( 'city' ) . ', ' . dvs_business( 'country' );
	ob_start();
	?>
	<ul class="dvs-contact-list<?php echo $light ? ' is-light' : ''; ?>" role="list">
		<li><span class="dvs-contact-list__label"><?php dvs_e( 'contact_email' ); ?></span><a href="mailto:<?php echo esc_attr( dvs_business( 'email' ) ); ?>"><?php echo esc_html( dvs_business( 'email' ) ); ?></a></li>
		<li><span class="dvs-contact-list__label"><?php dvs_e( 'contact_phone' ); ?></span><a href="<?php echo esc_attr( dvs_phone_href() ); ?>"><?php echo esc_html( dvs_business( 'phone' ) ); ?></a><small><?php dvs_e( 'call_note' ); ?></small></li>
		<li><span class="dvs-contact-list__label"><?php dvs_e( 'contact_address' ); ?></span><span><?php echo esc_html( $addr ); ?></span><a class="dvs-contact-list__dir" href="<?php echo esc_url( dvs_directions_url() ); ?>" target="_blank" rel="noopener"><?php dvs_e( 'directions' ); ?><span class="visually-hidden"> — <?php dvs_e( 'directions_note' ); ?></span> <span aria-hidden="true">↗</span></a></li>
	</ul>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_invite( array $a ): string {
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-invite' ); // phpcs:ignore ?>>
		<div class="dvs-invite__media" data-parallax><?php echo dvs_img( $a['image'], 'full', array( 'sizes' => '100vw', 'class' => 'dvs-invite__img' ) ); // phpcs:ignore ?></div>
		<div class="dvs-invite__shade" aria-hidden="true"></div>
		<div class="dvs-wrap dvs-invite__inner">
			<div class="dvs-invite__copy" data-reveal>
				<?php echo dvs_heading( $a, '2', 'dvs-invite__title' ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( (string) $a['text'], 'dvs-invite__text' ); // phpcs:ignore ?>
				<p class="dvs-invite__actions"><?php echo dvs_btn( $a['btn1Label'] ?: dvs_t( 'cta_plan' ), $a['btn1Url'] ?: 'dvs:request', 'primary' ); // phpcs:ignore ?></p>
			</div>
			<div class="dvs-invite__contact" data-reveal><?php echo dvs_contact_list( true ); // phpcs:ignore ?></div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_catalog( array $a ): string {
	$allowed = array_filter( array_map( 'trim', explode( ',', (string) $a['categories'] ) ) );
	$items   = array_filter( dvs_catalog(), static fn( $i ) => ( ! $allowed || in_array( $i['category'], $allowed, true ) ) && dvs_offer_id( $i['key'] ) );
	$q       = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore
	$cat     = isset( $_GET['category'] ) ? sanitize_key( $_GET['category'] ) : ''; // phpcs:ignore
	$aud     = isset( $_GET['level'] ) ? sanitize_key( $_GET['level'] ) : ''; // phpcs:ignore
	$cats    = array_unique( array_column( $items, 'category' ) );
	if ( $cat && ! in_array( $cat, $cats, true ) ) {
		$cat = '';
	}
	if ( $aud && ! isset( dvs_audiences()[ $aud ] ) ) {
		$aud = '';
	}
	$needle  = remove_accents( mb_strtolower( $q ) );
	$visible = 0;
	$cards   = '';
	foreach ( $items as $item ) {
		$title   = remove_accents( mb_strtolower( dvs_item_title( $item ) . ' ' . get_post_field( 'post_excerpt', dvs_offer_id( $item['key'] ) ) . ' ' . dvs_t( dvs_categories()[ $item['category'] ]['short'] ) . ' ' . implode( ' ', array_map( 'dvs_variant_label', $item['variants'] ) ) ) );
		$match   = ( ! $cat || $item['category'] === $cat ) && ( ! $aud || in_array( $aud, $item['audience'], true ) ) && ( '' === $needle || false !== strpos( $title, $needle ) );
		$visible += $match ? 1 : 0;
		$cards  .= dvs_offer_card( $item, array( 'hidden' => ! $match ) );
	}
	$action = get_permalink() ?: '';
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-catalog' ); // phpcs:ignore ?> id="catalog" data-dvs-catalog>
		<div class="dvs-wrap">
			<form class="dvs-filters" method="get" action="<?php echo esc_url( $action ); ?>#catalog" role="search" aria-label="<?php echo esc_attr( dvs_t( 'catalog_filters' ) ); ?>" data-filters>
				<div class="dvs-filters__search">
					<label for="catalog-q"><?php dvs_e( 'catalog_search_label' ); ?></label>
					<div class="dvs-filters__input">
						<svg aria-hidden="true" viewBox="0 0 24 24" width="20" height="20"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 20l-4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
						<input type="search" id="catalog-q" name="q" value="<?php echo esc_attr( $q ); ?>" placeholder="<?php echo esc_attr( dvs_t( 'catalog_search_ph' ) ); ?>" autocomplete="off">
					</div>
				</div>
				<fieldset class="dvs-filters__cats">
					<legend><?php dvs_e( 'catalog_category' ); ?></legend>
					<div class="dvs-chips">
						<label class="dvs-chip"><input type="radio" name="category" value="" <?php checked( $cat, '' ); ?>><span><?php dvs_e( 'catalog_all' ); ?></span></label>
						<?php foreach ( dvs_categories() as $ck => $c ) : ?>
							<?php if ( ! in_array( $ck, $cats, true ) ) { continue; } ?>
							<label class="dvs-chip"><input type="radio" name="category" value="<?php echo esc_attr( $ck ); ?>" <?php checked( $cat, $ck ); ?>><span><?php echo esc_html( dvs_t( $c['short'] ) ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<div class="dvs-filters__level">
					<label for="catalog-level"><?php dvs_e( 'catalog_audience' ); ?></label>
					<select id="catalog-level" name="level">
						<option value=""><?php dvs_e( 'catalog_all' ); ?></option>
						<?php foreach ( dvs_audiences() as $k => $s ) : ?>
							<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $aud, $k ); ?>><?php dvs_e( $s ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="dvs-filters__actions">
					<button type="submit" class="dvs-btn dvs-btn--secondary" data-filters-submit><?php dvs_e( 'catalog_apply' ); ?></button>
					<a class="dvs-filters__clear" href="<?php echo esc_url( $action ); ?>#catalog" data-filters-clear><?php dvs_e( 'catalog_clear' ); ?></a>
				</div>
			</form>
			<h2 class="visually-hidden"><?php dvs_e( 'back_catalog' ); ?></h2>
			<p class="dvs-catalog__count" role="status" data-count data-one="<?php echo esc_attr( dvs_t( 'catalog_count_one' ) ); ?>" data-many="<?php echo esc_attr( dvs_t( 'catalog_count', 999 ) ); ?>"><?php echo esc_html( 1 === $visible ? dvs_t( 'catalog_count_one' ) : dvs_t( 'catalog_count', $visible ) ); ?></p>
			<div class="dvs-cards dvs-cards--catalog" data-results><?php echo $cards; // phpcs:ignore ?></div>
			<div class="dvs-empty" data-empty <?php echo $visible ? 'hidden' : ''; ?>>
				<h2 class="dvs-empty__title"><?php dvs_e( 'catalog_empty_title' ); ?></h2>
				<p><?php dvs_e( 'catalog_empty_text' ); ?></p>
				<p class="dvs-empty__actions"><a class="dvs-btn dvs-btn--secondary" href="<?php echo esc_url( $action ); ?>#catalog" data-filters-clear><?php dvs_e( 'catalog_clear' ); ?></a> <?php echo dvs_btn( dvs_t( 'ask_question' ), dvs_request_url( null ) . '', 'ghost' ); // phpcs:ignore ?></p>
			</div>
			<?php echo dvs_price_notice( $items ); // phpcs:ignore ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_prices( array $a ): string {
	$allowed = array_filter( array_map( 'trim', explode( ',', (string) $a['categories'] ) ) );
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-prices' ); // phpcs:ignore ?>>
		<div class="dvs-wrap">
			<?php echo dvs_price_notice(); // phpcs:ignore ?>
			<nav class="dvs-prices__toc" aria-label="<?php echo esc_attr( dvs_t( 'catalog_category' ) ); ?>"><ul role="list">
				<?php foreach ( dvs_categories() as $ck => $c ) : ?>
					<?php if ( ( $allowed && ! in_array( $ck, $allowed, true ) ) || ! dvs_items_by_category( $ck ) ) { continue; } ?>
					<li><a href="#prices-<?php echo esc_attr( $ck ); ?>"><?php echo esc_html( dvs_t( $c['short'] ) ); ?></a></li>
				<?php endforeach; ?>
			</ul></nav>
			<?php foreach ( dvs_categories() as $ck => $c ) : ?>
				<?php
				$items = dvs_items_by_category( $ck );
				if ( ( $allowed && ! in_array( $ck, $allowed, true ) ) || ! $items ) {
					continue;
				}
				?>
				<div class="dvs-prices__group" id="prices-<?php echo esc_attr( $ck ); ?>">
					<h2 class="dvs-prices__title"><?php echo esc_html( dvs_t( $c['label'] ) ); ?></h2>
					<?php if ( 'technical' === $ck ) : ?><p class="dvs-prices__note"><?php dvs_e( 'price_tec_note' ); ?></p><?php endif; ?>
					<div class="dvs-table-wrap" tabindex="0" role="region" aria-label="<?php echo esc_attr( dvs_t( $c['label'] ) ); ?>">
						<table class="dvs-table dvs-table--stack">
							<thead><tr><th scope="col"><?php dvs_e( 'col_activity' ); ?></th><th scope="col"><?php dvs_e( 'option' ); ?></th><th scope="col"><?php dvs_e( 'fact_duration' ); ?></th><th scope="col" class="num"><?php dvs_e( 'price' ); ?></th><th scope="col"><span class="visually-hidden"><?php dvs_e( 'request_this' ); ?></span></th></tr></thead>
							<tbody>
							<?php foreach ( $items as $item ) : ?>
								<?php $nv = count( $item['variants'] ); ?>
								<?php foreach ( $item['variants'] as $i => $v ) : ?>
									<tr<?php echo 0 === $i ? ' class="is-first"' : ''; ?>>
										<?php if ( 0 === $i ) : ?><th scope="rowgroup" rowspan="<?php echo (int) $nv; ?>"><a href="<?php echo esc_url( dvs_item_url( $item ) ); ?>"><?php echo esc_html( dvs_item_title( $item ) ); ?></a></th><?php endif; ?>
										<td data-label="<?php echo esc_attr( dvs_t( 'option' ) ); ?>"><?php echo esc_html( dvs_variant_label( $v ) ?: dvs_t( 'option_single' ) ); ?></td>
										<td data-label="<?php echo esc_attr( dvs_t( 'fact_duration' ) ); ?>"><?php echo esc_html( dvs_format_duration( $v['duration'] ?? null ) ?: dvs_item_duration( $item ) ?: '—' ); ?></td>
										<td class="num" data-label="<?php echo esc_attr( dvs_t( 'price' ) ); ?>"><?php echo esc_html( dvs_variant_price_text( $v ) ); ?></td>
										<td class="act"><a href="<?php echo esc_url( dvs_request_url( $item, $v['key'] ) ); ?>" aria-label="<?php echo esc_attr( dvs_t( 'request_option', dvs_item_title( $item ) . ( dvs_variant_label( $v ) ? ' — ' . dvs_variant_label( $v ) : '' ) ) ); ?>"><?php dvs_e( 'request_this' ); ?></a></td>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_packages( array $a ): string {
	$item = dvs_item( 'boat-dives' );
	if ( ! $item ) {
		return '';
	}
	$by    = array();
	$dives = array();
	foreach ( $item['variants'] as $v ) {
		if ( isset( $v['dives'], $v['equipment'] ) ) {
			$by[ $v['dives'] . '-' . ( $v['equipment'] ? 'equip' : 'own' ) ] = $v;
			$dives[ $v['dives'] ] = true;
		}
	}
	ksort( $dives );
	$data = array();
	foreach ( $by as $k => $v ) {
		$data[ $k ] = array(
			'key'      => $v['key'],
			'price'    => dvs_variant_price_text( $v ),
			'duration' => dvs_format_duration( $v['duration'] ?? null ),
			'url'      => dvs_request_url( $item, $v['key'] ),
			'label'    => dvs_variant_label( $v ),
		);
	}
	$first = $data['2-own'] ?? reset( $data );
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-packages dvs-tone--sand' ); // phpcs:ignore ?> id="packages">
		<div class="dvs-wrap dvs-packages__grid">
			<div class="dvs-packages__copy">
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo $a['intro'] ? dvs_paragraphs( $a['intro'] ) : ''; // phpcs:ignore ?>
			</div>
			<form class="dvs-packages__picker" data-packages='<?php echo esc_attr( wp_json_encode( $data ) ); ?>' hidden>
				<fieldset>
					<legend><?php dvs_e( 'pkg_dives' ); ?></legend>
					<div class="dvs-seg">
						<?php foreach ( array_keys( $dives ) as $n ) : ?>
							<label class="dvs-seg__opt"><input type="radio" name="dives" value="<?php echo (int) $n; ?>" <?php checked( 2, $n ); ?>><span><?php echo esc_html( 1 === $n ? dvs_t( 'pkg_dive_1' ) : dvs_t( 'pkg_dive_n', $n ) ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php dvs_e( 'pkg_equipment' ); ?></legend>
					<div class="dvs-seg">
						<label class="dvs-seg__opt"><input type="radio" name="equip" value="own" checked><span><?php dvs_e( 'pkg_own' ); ?></span></label>
						<label class="dvs-seg__opt"><input type="radio" name="equip" value="equip"><span><?php dvs_e( 'pkg_included' ); ?></span></label>
					</div>
				</fieldset>
				<div class="dvs-packages__result" aria-live="polite">
					<p class="dvs-packages__label"><?php dvs_e( 'pkg_price' ); ?></p>
					<p class="dvs-packages__price" data-pkg-price><?php echo esc_html( $first['price'] ); ?></p>
					<p class="dvs-packages__dur" data-pkg-dur><?php echo esc_html( $first['duration'] ); ?></p>
				</div>
				<a class="dvs-btn dvs-btn--primary" href="<?php echo esc_url( $first['url'] ); ?>" data-pkg-cta><?php dvs_e( 'request_this' ); ?></a>
			</form>
		</div>
		<div class="dvs-wrap">
			<div class="dvs-table-wrap" tabindex="0" role="region" aria-label="<?php echo esc_attr( dvs_t( 'pkg_all' ) ); ?>">
				<table class="dvs-table dvs-table--packages">
					<caption class="visually-hidden"><?php dvs_e( 'pkg_all' ); ?></caption>
					<thead><tr><th scope="col"><?php dvs_e( 'pkg_dives' ); ?></th><th scope="col" class="num"><?php dvs_e( 'pkg_own' ); ?></th><th scope="col" class="num"><?php dvs_e( 'pkg_included' ); ?></th></tr></thead>
					<tbody>
					<?php foreach ( array_keys( $dives ) as $n ) : ?>
						<tr>
							<th scope="row"><?php echo esc_html( 1 === $n ? dvs_t( 'pkg_dive_1' ) : dvs_t( 'pkg_dive_n', $n ) ); ?></th>
							<?php foreach ( array( 'own', 'equip' ) as $e ) : ?>
								<td class="num"><?php if ( isset( $data[ "{$n}-{$e}" ] ) ) : ?><a href="<?php echo esc_url( $data[ "{$n}-{$e}" ]['url'] ); ?>" aria-label="<?php echo esc_attr( dvs_t( 'request_option', $data[ "{$n}-{$e}" ]['label'] ) ); ?>"><?php echo esc_html( $data[ "{$n}-{$e}" ]['price'] ); ?></a><?php else : ?>—<?php endif; ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<?php echo dvs_price_notice( array( $item ) ); // phpcs:ignore ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_request( array $a ): string {
	return '<section ' . dvs_section_attrs( $a, 'dvs-section dvs-request-section' ) . '><div class="dvs-wrap dvs-wrap--narrow">' . dvs_render_request_form( $a ) . '</div></section>';
}

function dvs_block_contact( array $a ): string {
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-contact dvs-tone--sand' ); // phpcs:ignore ?>>
		<div class="dvs-wrap dvs-contact__grid">
			<div>
				<?php echo dvs_heading( $a ); // phpcs:ignore ?>
				<?php echo dvs_paragraphs( (string) $a['text'] ); // phpcs:ignore ?>
				<p class="dvs-contact__reply"><?php dvs_e( 'reply_time' ); ?></p>
			</div>
			<div class="dvs-contact__card"><?php echo dvs_contact_list(); // phpcs:ignore ?></div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_faq( array $a ): string {
	$args = array(
		'post_type'   => 'dvs_faq',
		'post_status' => 'publish',
		'numberposts' => $a['limit'] ? (int) $a['limit'] : -1,
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'meta_query'  => array( array( 'key' => '_dvs_lang', 'value' => dvs_lang() ) ), // phpcs:ignore
	);
	if ( $a['topic'] ) {
		$args['meta_query'][] = array( 'key' => '_dvs_topic', 'value' => $a['topic'] );
	}
	$faqs = get_posts( $args );
	if ( ! $faqs ) {
		return '';
	}
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-faq' ); // phpcs:ignore ?>>
		<div class="dvs-wrap dvs-wrap--narrow">
			<?php echo dvs_heading( $a ); // phpcs:ignore ?>
			<div class="dvs-faq__list">
				<?php foreach ( $faqs as $f ) : ?>
					<details class="dvs-faq__item">
						<summary class="dvs-faq__q"><?php echo esc_html( $f->post_title ); ?></summary>
						<div class="dvs-faq__a"><?php echo wp_kses_post( apply_filters( 'the_content', $f->post_content ) ); ?></div>
					</details>
				<?php endforeach; ?>
			</div>
			<?php if ( $a['linkLabel'] ) : ?><p><a class="dvs-link-arrow" href="<?php echo esc_url( dvs_resolve_url( $a['linkUrl'] ) ); ?>"><?php echo esc_html( $a['linkLabel'] ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a></p><?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

function dvs_block_links( array $a ): string {
	$rows = array_filter( array_map( 'trim', preg_split( "/\r?\n/", (string) $a['items'] ) ) );
	if ( ! $rows ) {
		return '';
	}
	ob_start();
	?>
	<section <?php echo dvs_section_attrs( $a, 'dvs-section dvs-links' ); // phpcs:ignore ?>>
		<div class="dvs-wrap">
			<?php echo dvs_heading( $a ); // phpcs:ignore ?>
			<ul class="dvs-links__list" role="list">
				<?php foreach ( $rows as $r ) : ?>
					<?php $p = array_map( 'trim', explode( '|', $r ) ); if ( count( $p ) < 2 ) { continue; } ?>
					<li class="dvs-links__item"><a href="<?php echo esc_url( dvs_resolve_url( $p[1] ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $p[0] ); ?><span class="visually-hidden"> <?php dvs_e( 'new_tab' ); ?></span> <span aria-hidden="true">↗</span></a><?php if ( ! empty( $p[2] ) ) : ?><span class="dvs-links__desc"><?php echo esc_html( $p[2] ); ?></span><?php endif; ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/* Menus: ligações personalizadas com o esquema dvs: (resolvidas no idioma do menu). */
add_filter( 'kses_allowed_protocols', static fn( $p ) => array_merge( $p, array( 'dvs' ) ) );
add_filter(
	'nav_menu_link_attributes',
	static function ( $atts ) {
		if ( ! empty( $atts['href'] ) && 0 === strpos( $atts['href'], 'dvs:' ) ) {
			$atts['href'] = dvs_resolve_url( $atts['href'] );
		}
		return $atts;
	}
);
