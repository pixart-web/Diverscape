<?php
/**
 * Tema DiversCape — apresentação. Os dados de negócio vivem no plugin DiversCape Core.
 */

defined( 'ABSPATH' ) || exit;

define( 'DVS_THEME_DIR', get_template_directory() );
define( 'DVS_THEME_URI', get_template_directory_uri() );

/** O tema depende do plugin; sem ele mostra um aviso em vez de falhar. */
function dvs_theme_ready(): bool {
	return function_exists( 'dvs_lang' );
}

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/main.css' );
		remove_theme_support( 'core-block-patterns' );

		$locs  = array();
		$names = array( 'primary' => 'Menu principal', 'footer' => 'Rodapé — explorar', 'legal' => 'Rodapé — legal' );
		$langs = function_exists( 'dvs_languages' ) ? dvs_languages() : array( 'en' => array( 'short' => 'EN' ) );
		foreach ( $names as $k => $label ) {
			foreach ( $langs as $code => $cfg ) {
				$locs[ "{$k}_{$code}" ] = $label . ' (' . $cfg['short'] . ')';
			}
		}
		register_nav_menus( $locs );
	}
);

/* Limpeza do <head>. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

add_action(
	'wp_enqueue_scripts',
	static function () {
		$css = DVS_THEME_DIR . '/assets/css/main.css';
		$js  = DVS_THEME_DIR . '/assets/js/main.js';
		wp_enqueue_style( 'diverscape', DVS_THEME_URI . '/assets/css/main.css', array(), (string) filemtime( $css ) );
		wp_enqueue_script( 'diverscape', DVS_THEME_URI . '/assets/js/main.js', array(), (string) filemtime( $js ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		if ( dvs_theme_ready() ) {
			wp_localize_script(
				'diverscape',
				'dvsI18n',
				array_merge(
					dvs_form_js_strings(),
					array(
						'menu'       => dvs_t( 'menu' ),
						'menu_close' => dvs_t( 'menu_close' ),
						'reviews_loading' => dvs_t( 'reviews_loading' ),
						'reviews_failed'  => dvs_t( 'reviews_failed' ),
						'catalog_count'   => dvs_t( 'catalog_count', 999 ),
						'catalog_count_one' => dvs_t( 'catalog_count_one' ),
					)
				)
			);
		}
		// Estilos de blocos que não usamos no site público.
		wp_dequeue_style( 'classic-theme-styles' );
	}
);

/* Pré-carregar as fontes de texto (latin). */
add_action(
	'wp_head',
	static function () {
		foreach ( array( 'manrope-latin-wght-normal.woff2', 'inter-latin-wght-normal.woff2' ) as $f ) {
			printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( DVS_THEME_URI . '/assets/fonts/' . $f ) );
		}
		printf( '<link rel="icon" href="%s" type="image/png" sizes="64x64">' . "\n" . '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( DVS_THEME_URI . '/assets/img/favicon.png' ), esc_url( DVS_THEME_URI . '/assets/img/apple-touch-icon.png' ) );
		echo '<meta name="theme-color" content="#062B3A">' . "\n";
	},
	1
);

/* As fontes latin-ext ficam disponíveis por @font-face no CSS (carregadas só quando necessárias). */

/** A página começa com um hero com imagem? (cabeçalho transparente por cima) */
function dvs_page_has_hero(): bool {
	if ( is_singular( 'dvs_offer' ) ) {
		return true;
	}
	if ( ! is_singular() ) {
		return false;
	}
	$blocks = parse_blocks( (string) get_post_field( 'post_content', get_queried_object_id() ) );
	foreach ( $blocks as $b ) {
		if ( empty( $b['blockName'] ) ) {
			continue;
		}
		return 'diverscape/hero' === $b['blockName'] && ( 'home' === ( $b['attrs']['variant'] ?? '' ) || ! empty( $b['attrs']['image'] ) );
	}
	return false;
}

add_filter(
	'body_class',
	static function ( array $c ) {
		if ( dvs_page_has_hero() ) {
			$c[] = 'has-hero';
		}
		if ( dvs_theme_ready() ) {
			$c[] = 'lang-' . dvs_lang();
		}
		return $c;
	}
);

/** Imprime um menu do idioma atual. */
function dvs_theme_menu( string $location, array $args = array() ): void {
	$lang = dvs_theme_ready() ? dvs_lang() : 'en';
	$loc  = "{$location}_{$lang}";
	if ( ! has_nav_menu( $loc ) ) {
		return;
	}
	wp_nav_menu(
		array_merge(
			array(
				'theme_location' => $loc,
				'container'      => false,
				'fallback_cb'    => false,
				'depth'          => 2,
			),
			$args
		)
	);
}

/* Submenus acessíveis: botão de divulgação a seguir à ligação do item-pai (menu principal). */
add_filter(
	'walker_nav_menu_start_el',
	static function ( $output, $item, $depth, $args ) {
		if ( 0 === $depth && in_array( 'menu-item-has-children', (array) $item->classes, true ) && isset( $args->menu_class ) && false !== strpos( (string) $args->menu_class, 'site-nav__menu' ) ) {
			$label   = dvs_theme_ready() ? dvs_t( 'submenu_toggle', wp_strip_all_tags( $item->title ) ) : $item->title;
			$output .= '<button type="button" class="site-nav__toggle" aria-expanded="false" aria-controls="sub-' . (int) $item->ID . '"><span class="visually-hidden">' . esc_html( $label ) . '</span><svg aria-hidden="true" viewBox="0 0 24 24" width="16" height="16"><path d="M6 9l6 6 6-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></button>';
		}
		return $output;
	},
	10,
	4
);

add_filter(
	'nav_menu_submenu_css_class',
	static fn( $classes ) => array_merge( $classes, array( 'site-nav__sub' ) )
);

/* Identificador do submenu para aria-controls. */
add_filter(
	'wp_nav_menu_items',
	static function ( $items, $args ) {
		if ( false === strpos( (string) ( $args->menu_class ?? '' ), 'site-nav__menu' ) ) {
			return $items;
		}
		// Atribui id ao <ul class="sub-menu"> seguinte a cada botão (ordem garantida pelo walker).
		return preg_replace_callback(
			'/aria-controls="(sub-\d+)"><span(.*?)<ul class="sub-menu/s',
			static fn( $m ) => 'aria-controls="' . $m[1] . '"><span' . $m[2] . '<ul id="' . $m[1] . '" class="sub-menu',
			$items
		);
	},
	10,
	2
);

/** Marca o item de menu atual também quando o URL tem parâmetros (ligações dvs:). */
add_filter(
	'nav_menu_link_attributes',
	static function ( $atts, $item ) {
		if ( in_array( 'current-menu-item', (array) $item->classes, true ) ) {
			$atts['aria-current'] = 'page';
		}
		return $atts;
	},
	20,
	2
);

/** Logótipo (do plugin; recorre ao ficheiro do tema). */
function dvs_logo( string $class = 'site-brand__logo', bool $eager = true ): string {
	$id = dvs_theme_ready() ? dvs_media_id( 'logo-diverscape.png' ) : 0;
	$alt = 'DiversCape — Sagres, Portugal';
	if ( $id ) {
		return wp_get_attachment_image(
			$id,
			'medium',
			false,
			array(
				'class'         => $class,
				'alt'           => $alt,
				'loading'       => $eager ? 'eager' : 'lazy',
				'decoding'      => 'async',
				'sizes'         => '120px',
			)
		);
	}
	return '';
}

/** Ícones SVG simples. */
function dvs_icon( string $name, int $size = 20 ): string {
	$paths = array(
		'phone'  => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
		'globe'  => '<circle cx="12" cy="12" r="9" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 12h18M12 3c2.5 2.6 3.7 5.6 3.7 9s-1.2 6.4-3.7 9c-2.5-2.6-3.7-5.6-3.7-9S9.5 5.6 12 3z" fill="none" stroke="currentColor" stroke-width="1.8"/>',
		'menu'   => '<path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'close'  => '<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
		'mail'   => '<rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M3 7l9 6 9-6" fill="none" stroke="currentColor" stroke-width="1.8"/>',
		'pin'    => '<path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z" fill="none" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.5" fill="none" stroke="currentColor" stroke-width="1.8"/>',
		'check'  => '<path d="M5 12.5l4.5 4.5L19 7.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>',
		'arrow'  => '<path d="M5 12h14M13 6l6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
	);
	return '<svg class="dvs-icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}

add_action(
	'init',
	static function () {
		register_block_pattern_category( 'diverscape', array( 'label' => 'DiversCape' ) );
	}
);
