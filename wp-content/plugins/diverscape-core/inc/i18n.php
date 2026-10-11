<?php
/**
 * Camada multilingue leve da DiversCape.
 *
 * Modelo:
 * - Cada página, oferta ou pergunta frequente tem `_dvs_lang` (en|pt|de|es) e `_dvs_group`
 *   (identificador estável partilhado pelas traduções, p. ex. `page:courses` ou `offer:deep-diver`).
 * - Inglês vive na raiz; as restantes línguas são páginas-filhas das páginas `pt`, `de` e `sp`,
 *   o que dá URLs nativas do WordPress: /pt/cursos/, /de/kurse/, /sp/cursos/.
 * - O caminho histórico do espanhol é /sp/; o código de idioma é `es` (hreflang `es`, locale es_ES).
 * - Sem redirecionamento por IP ou idioma do navegador.
 *
 * Os preços e variantes NÃO são traduzíveis: vivem no catálogo central (ver catalog.php).
 */

defined( 'ABSPATH' ) || exit;

/** Configuração dos idiomas. A ordem é a do seletor. */
function dvs_languages(): array {
	return array(
		'en' => array( 'locale' => 'en_GB', 'hreflang' => 'en', 'prefix' => '', 'name' => 'English', 'short' => 'EN', 'og' => 'en_GB' ),
		'pt' => array( 'locale' => 'pt_PT', 'hreflang' => 'pt-PT', 'prefix' => 'pt', 'name' => 'Português', 'short' => 'PT', 'og' => 'pt_PT' ),
		'de' => array( 'locale' => 'de_DE', 'hreflang' => 'de', 'prefix' => 'de', 'name' => 'Deutsch', 'short' => 'DE', 'og' => 'de_DE' ),
		'es' => array( 'locale' => 'es_ES', 'hreflang' => 'es', 'prefix' => 'sp', 'name' => 'Español', 'short' => 'ES', 'og' => 'es_ES' ),
	);
}

function dvs_default_lang(): string {
	return 'en';
}

function dvs_is_lang( $lang ): bool {
	return is_string( $lang ) && isset( dvs_languages()[ $lang ] );
}

/** Tipos de conteúdo com traduções ligadas. */
function dvs_translatable_types(): array {
	return array( 'page', 'dvs_offer', 'dvs_faq' );
}

/** Idioma forçado (importação, renderização no editor, pedidos REST). */
function dvs_set_lang( ?string $lang ): void {
	$GLOBALS['dvs_forced_lang'] = dvs_is_lang( $lang ) ? $lang : null;
}

/** Idioma a partir do caminho do pedido (/pt/…, /de/…, /sp/…). */
function dvs_lang_from_path( string $path ): string {
	$home = wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ?: '/';
	$path = '/' . ltrim( (string) wp_parse_url( $path, PHP_URL_PATH ), '/' );
	if ( 0 === strpos( $path, $home ) ) {
		$path = substr( $path, strlen( $home ) );
	}
	$first = strtolower( (string) strtok( trim( $path, '/' ), '/' ) );
	foreach ( dvs_languages() as $code => $cfg ) {
		if ( $cfg['prefix'] && $cfg['prefix'] === $first ) {
			return $code;
		}
	}
	return dvs_default_lang();
}

/** Idioma atual do pedido. */
function dvs_lang(): string {
	if ( ! empty( $GLOBALS['dvs_forced_lang'] ) ) {
		return $GLOBALS['dvs_forced_lang'];
	}
	if ( did_action( 'wp' ) && ! is_admin() ) {
		$obj = get_queried_object();
		if ( $obj instanceof WP_Post ) {
			$l = dvs_post_lang( $obj->ID );
			if ( $l ) {
				return $l;
			}
		}
	}
	if ( is_admin() && ! wp_doing_ajax() ) {
		return dvs_default_lang();
	}
	return dvs_lang_from_path( (string) ( $_SERVER['REQUEST_URI'] ?? '/' ) ); // phpcs:ignore
}

function dvs_post_lang( int $post_id ): string {
	$l = (string) get_post_meta( $post_id, '_dvs_lang', true );
	return dvs_is_lang( $l ) ? $l : '';
}

function dvs_post_group( int $post_id ): string {
	return (string) get_post_meta( $post_id, '_dvs_group', true );
}

/**
 * ID publicado de um grupo de tradução num idioma (cache por pedido).
 *
 * @param bool $any_status Incluir rascunhos (painel/importação).
 */
function dvs_group_post( string $group, ?string $lang = null, bool $any_status = false ): int {
	static $cache = array();
	$lang = $lang ?: dvs_lang();
	$key  = $group . '|' . $lang . '|' . (int) $any_status;
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	$ids = get_posts(
		array(
			'post_type'        => dvs_translatable_types(),
			'post_status'      => $any_status ? array( 'publish', 'draft', 'pending', 'private' ) : 'publish',
			'numberposts'      => 1,
			'fields'           => 'ids',
			'suppress_filters' => true,
			'meta_query'       => array( // phpcs:ignore
				'relation' => 'AND',
				array( 'key' => '_dvs_group', 'value' => $group ),
				array( 'key' => '_dvs_lang', 'value' => $lang ),
			),
		)
	);
	$cache[ $key ] = $ids ? (int) $ids[0] : 0;
	return $cache[ $key ];
}

/** Tradução de um conteúdo noutro idioma (0 se não existir). */
function dvs_translation_id( int $post_id, string $lang ): int {
	if ( dvs_post_lang( $post_id ) === $lang ) {
		return $post_id;
	}
	$group = dvs_post_group( $post_id );
	return $group ? dvs_group_post( $group, $lang ) : 0;
}

/** Página inicial de um idioma. */
function dvs_home_url( ?string $lang = null ): string {
	$lang = $lang ?: dvs_lang();
	$id   = dvs_group_post( 'page:home', $lang );
	if ( $id ) {
		return get_permalink( $id );
	}
	$prefix = dvs_languages()[ $lang ]['prefix'] ?? '';
	return home_url( $prefix ? "/{$prefix}/" : '/' );
}

/**
 * URL de um grupo no idioma pedido; recorre à página inicial do idioma se não houver tradução.
 * Aceita `page:courses`, `offer:deep-diver` ou só `courses` (assume página).
 */
function dvs_url( string $group, ?string $lang = null, array $args = array() ): string {
	if ( false === strpos( $group, ':' ) ) {
		$group = 'page:' . $group;
	}
	$id  = dvs_group_post( $group, $lang );
	$url = $id ? get_permalink( $id ) : dvs_home_url( $lang );
	return $args ? add_query_arg( $args, $url ) : $url;
}

/** Ligações do seletor de idioma para a página atual. */
function dvs_language_links(): array {
	$current = dvs_lang();
	$obj     = is_singular() ? get_queried_object() : null;
	$links   = array();
	foreach ( dvs_languages() as $code => $cfg ) {
		$url   = '';
		$exact = false;
		if ( $obj instanceof WP_Post ) {
			$tid = dvs_translation_id( $obj->ID, $code );
			if ( $tid && 'publish' === get_post_status( $tid ) ) {
				$url   = get_permalink( $tid );
				$exact = true;
			}
		}
		if ( ! $url ) {
			$url = dvs_home_url( $code );
		}
		$links[ $code ] = array(
			'url'      => $url,
			'name'     => $cfg['name'],
			'short'    => $cfg['short'],
			'hreflang' => $cfg['hreflang'],
			'current'  => $code === $current,
			'exact'    => $exact,
		);
	}
	return $links;
}

/* ---------------------------------------------------------------------------------------------
 * Front-end: locale, atributo lang, resolução de ofertas em caminhos hierárquicos.
 * ------------------------------------------------------------------------------------------- */

add_filter(
	'locale',
	static function ( $locale ) {
		if ( is_admin() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			return $locale;
		}
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $GLOBALS['dvs_forced_lang'] ) ) {
			return dvs_languages()[ $GLOBALS['dvs_forced_lang'] ]['locale'];
		}
		return dvs_languages()[ dvs_lang() ]['locale'] ?? $locale;
	}
);

add_filter(
	'language_attributes',
	static function ( $output ) {
		if ( is_admin() ) {
			return $output;
		}
		$hl = dvs_languages()[ dvs_lang() ]['hreflang'];
		return preg_replace( '/lang="[^"]*"/', 'lang="' . esc_attr( $hl ) . '"', $output );
	}
);

/**
 * Ofertas vivem por baixo da página da sua categoria no seu idioma
 * (/courses/deep-diver/, /pt/cursos/mergulho-profundo/). Quando o WordPress não encontra
 * uma página com esse caminho, procuramos uma oferta com esse slug, idioma e página-mãe.
 */
add_filter(
	'request',
	static function ( array $vars ) {
		if ( is_admin() ) {
			return $vars;
		}
		$path = trim( (string) ( $GLOBALS['wp']->request ?? '' ), '/' );
		if ( '' === $path || false === strpos( $path, '/' ) || 0 === strpos( $path, 'wp-' ) ) {
			return $vars;
		}
		if ( ! empty( $vars['pagename'] ) && get_page_by_path( $vars['pagename'] ) ) {
			return $vars;
		}
		if ( get_page_by_path( $path ) ) {
			return array( 'pagename' => $path );
		}
		$parts = explode( '/', $path );
		$slug  = array_pop( $parts );
		$parent = get_page_by_path( implode( '/', $parts ) );
		if ( ! $parent ) {
			return $vars;
		}
		$lang   = dvs_post_lang( $parent->ID );
		$offers = get_posts(
			array(
				'post_type'        => 'dvs_offer',
				'name'             => sanitize_title( $slug ),
				'post_status'      => 'publish',
				'numberposts'      => 5,
				'suppress_filters' => true,
				'meta_query'       => array( array( 'key' => '_dvs_lang', 'value' => $lang ) ), // phpcs:ignore
			)
		);
		foreach ( $offers as $offer ) {
			if ( (int) dvs_offer_parent_page( $offer->ID ) === (int) $parent->ID ) {
				return array(
					'p'         => $offer->ID,
					'post_type' => 'dvs_offer',
				);
			}
		}
		return $vars;
	}
);

/** Permite o mesmo slug em idiomas diferentes (ex.: «bubblemaker» em PT e ES). */
add_filter(
	'wp_unique_post_slug',
	static function ( $slug, $post_id, $status, $type, $parent, $original ) {
		if ( ! in_array( $type, array( 'dvs_offer', 'dvs_faq' ), true ) || $slug === $original ) {
			return $slug;
		}
		$lang = dvs_post_lang( (int) $post_id ) ?: ( $GLOBALS['dvs_forced_lang'] ?? '' );
		if ( ! $lang ) {
			return $slug;
		}
		$clash = get_posts(
			array(
				'post_type'        => $type,
				'name'             => $original,
				'post_status'      => 'any',
				'post__not_in'     => array( (int) $post_id ),
				'fields'           => 'ids',
				'numberposts'      => 1,
				'suppress_filters' => true,
				'meta_query'       => array( array( 'key' => '_dvs_lang', 'value' => $lang ) ), // phpcs:ignore
			)
		);
		return $clash ? $slug : $original;
	},
	10,
	6
);

/* ---------------------------------------------------------------------------------------------
 * Painel: coluna e filtro de idioma, caixa de traduções, criar tradução.
 * ------------------------------------------------------------------------------------------- */

add_action(
	'init',
	static function () {
		foreach ( dvs_translatable_types() as $type ) {
			register_post_meta( $type, '_dvs_lang', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
			register_post_meta( $type, '_dvs_group', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
			add_filter( "manage_{$type}_posts_columns", 'dvs_admin_lang_column' );
			add_action( "manage_{$type}_posts_custom_column", 'dvs_admin_lang_column_value', 10, 2 );
		}
	}
);

function dvs_admin_lang_column( array $cols ): array {
	$new = array();
	foreach ( $cols as $k => $v ) {
		$new[ $k ] = $v;
		if ( 'title' === $k ) {
			$new['dvs_lang'] = 'Idioma';
		}
	}
	return $new;
}

function dvs_admin_lang_column_value( string $col, int $id ): void {
	if ( 'dvs_lang' !== $col ) {
		return;
	}
	$l = dvs_post_lang( $id );
	echo $l ? '<strong>' . esc_html( dvs_languages()[ $l ]['short'] ) . '</strong>' : '<span style="color:#b32d2e">—</span>';
	$others = array();
	foreach ( dvs_languages() as $code => $cfg ) {
		if ( $code === $l ) {
			continue;
		}
		$tid      = dvs_post_group( $id ) ? dvs_group_post( dvs_post_group( $id ), $code, true ) : 0;
		$others[] = $tid
			? '<a href="' . esc_url( get_edit_post_link( $tid ) ) . '" title="' . esc_attr( $cfg['name'] ) . '">' . esc_html( $cfg['short'] ) . '</a>'
			: '<span style="color:#b32d2e" title="Sem tradução">' . esc_html( $cfg['short'] ) . '</span>';
	}
	echo '<br><small>' . implode( ' · ', $others ) . '</small>'; // phpcs:ignore
}

add_action(
	'restrict_manage_posts',
	static function ( $type ) {
		if ( ! in_array( $type, dvs_translatable_types(), true ) ) {
			return;
		}
		$cur = isset( $_GET['dvs_lang'] ) ? sanitize_key( $_GET['dvs_lang'] ) : ''; // phpcs:ignore
		echo '<select name="dvs_lang"><option value="">Todos os idiomas</option>';
		foreach ( dvs_languages() as $code => $cfg ) {
			printf( '<option value="%s"%s>%s</option>', esc_attr( $code ), selected( $cur, $code, false ), esc_html( $cfg['name'] ) );
		}
		echo '</select>';
	}
);

add_action(
	'pre_get_posts',
	static function ( WP_Query $q ) {
		if ( ! is_admin() || ! $q->is_main_query() ) {
			return;
		}
		$l = isset( $_GET['dvs_lang'] ) ? sanitize_key( $_GET['dvs_lang'] ) : ''; // phpcs:ignore
		if ( dvs_is_lang( $l ) ) {
			$q->set( 'meta_query', array( array( 'key' => '_dvs_lang', 'value' => $l ) ) );
		}
	}
);

add_action(
	'add_meta_boxes',
	static function () {
		foreach ( dvs_translatable_types() as $type ) {
			add_meta_box( 'dvs-translations', 'Idioma e traduções', 'dvs_translations_metabox', $type, 'side', 'high' );
		}
	}
);

function dvs_translations_metabox( WP_Post $post ): void {
	wp_nonce_field( 'dvs_lang_save', 'dvs_lang_nonce' );
	$lang  = dvs_post_lang( $post->ID );
	$group = dvs_post_group( $post->ID );
	echo '<p><label for="dvs_lang_field"><strong>Idioma deste conteúdo</strong></label><br><select id="dvs_lang_field" name="dvs_lang_field" style="width:100%">';
	echo '<option value="">— escolher —</option>';
	foreach ( dvs_languages() as $code => $cfg ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $code ), selected( $lang, $code, false ), esc_html( $cfg['name'] ) );
	}
	echo '</select></p>';
	if ( ! $group ) {
		echo '<p class="description">Grupo de tradução atribuído ao guardar.</p>';
		return;
	}
	printf( '<p class="description">Grupo: <code>%s</code></p>', esc_html( $group ) );
	echo '<ul style="margin:0">';
	foreach ( dvs_languages() as $code => $cfg ) {
		if ( $code === $lang ) {
			continue;
		}
		$tid = dvs_group_post( $group, $code, true );
		if ( $tid ) {
			$status = 'publish' === get_post_status( $tid ) ? '' : ' (rascunho)';
			printf( '<li>%s: <a href="%s">editar</a>%s</li>', esc_html( $cfg['name'] ), esc_url( get_edit_post_link( $tid ) ), esc_html( $status ) );
		} else {
			$url = wp_nonce_url( admin_url( 'admin-post.php?action=dvs_create_translation&post=' . $post->ID . '&lang=' . $code ), 'dvs_translate_' . $post->ID );
			printf( '<li>%s: <a href="%s">criar tradução</a></li>', esc_html( $cfg['name'] ), esc_url( $url ) );
		}
	}
	echo '</ul>';
}

add_action(
	'save_post',
	static function ( int $post_id, WP_Post $post ) {
		if ( ! in_array( $post->post_type, dvs_translatable_types(), true ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! isset( $_POST['dvs_lang_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dvs_lang_nonce'] ), 'dvs_lang_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$lang = isset( $_POST['dvs_lang_field'] ) ? sanitize_key( $_POST['dvs_lang_field'] ) : '';
		if ( dvs_is_lang( $lang ) ) {
			update_post_meta( $post_id, '_dvs_lang', $lang );
		}
		if ( ! dvs_post_group( $post_id ) ) {
			$prefix = 'page' === $post->post_type ? 'page' : ( 'dvs_offer' === $post->post_type ? 'offer' : 'faq' );
			update_post_meta( $post_id, '_dvs_group', $prefix . ':' . ( $post->post_name ?: 'item-' . $post_id ) );
		}
	},
	10,
	2
);

add_action(
	'admin_post_dvs_create_translation',
	static function () {
		$src  = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
		$lang = isset( $_GET['lang'] ) ? sanitize_key( $_GET['lang'] ) : '';
		check_admin_referer( 'dvs_translate_' . $src );
		if ( ! $src || ! dvs_is_lang( $lang ) || ! current_user_can( 'edit_post', $src ) ) {
			wp_die( 'Pedido inválido.' );
		}
		$id = dvs_create_translation( $src, $lang );
		if ( is_wp_error( $id ) ) {
			wp_die( esc_html( $id->get_error_message() ) );
		}
		wp_safe_redirect( get_edit_post_link( $id, 'url' ) );
		exit;
	}
);

/** Duplica um conteúdo como rascunho noutro idioma, no mesmo grupo e sob a página-mãe equivalente. */
function dvs_create_translation( int $src, string $lang ) {
	$post  = get_post( $src );
	$group = dvs_post_group( $src );
	if ( ! $post || ! $group ) {
		return new WP_Error( 'dvs', 'Conteúdo sem grupo de tradução.' );
	}
	$existing = dvs_group_post( $group, $lang, true );
	if ( $existing ) {
		return $existing;
	}
	$parent = 0;
	if ( $post->post_parent ) {
		$parent = dvs_translation_id( (int) $post->post_parent, $lang ) ?: dvs_group_post( dvs_post_group( (int) $post->post_parent ), $lang, true );
	} elseif ( 'page' === $post->post_type && 'en' === dvs_post_lang( $src ) && 'page:home' !== $group ) {
		$parent = dvs_group_post( 'page:home', $lang, true );
	}
	dvs_set_lang( $lang );
	$id = wp_insert_post(
		array(
			'post_type'    => $post->post_type,
			'post_status'  => 'draft',
			'post_title'   => $post->post_title,
			'post_name'    => $post->post_name,
			'post_content' => $post->post_content,
			'post_excerpt' => $post->post_excerpt,
			'post_parent'  => $parent,
			'menu_order'   => $post->menu_order,
		),
		true
	);
	dvs_set_lang( null );
	if ( is_wp_error( $id ) ) {
		return $id;
	}
	foreach ( get_post_meta( $src ) as $k => $vals ) {
		if ( in_array( $k, array( '_dvs_lang', '_edit_lock', '_edit_last' ), true ) ) {
			continue;
		}
		foreach ( $vals as $v ) {
			add_post_meta( $id, $k, maybe_unserialize( $v ) );
		}
	}
	update_post_meta( $id, '_dvs_lang', $lang );
	update_post_meta( $id, '_dvs_group', $group );
	return $id;
}
