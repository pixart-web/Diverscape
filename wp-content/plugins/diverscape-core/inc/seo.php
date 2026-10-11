<?php
/**
 * SEO multilingue: título e descrição por página e idioma, canonical próprio de cada tradução,
 * hreflang recíproco (en, pt-PT, de, es + x-default), Open Graph/Twitter, dados estruturados
 * com propriedades confirmadas (sem avaliações, preços ativos ou certificações inventadas)
 * e breadcrumbs.
 *
 * Campos opcionais por página/oferta: `_dvs_seo_title`, `_dvs_seo_desc`.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		foreach ( array( 'page', 'dvs_offer' ) as $t ) {
			register_post_meta( $t, '_dvs_seo_title', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
			register_post_meta( $t, '_dvs_seo_desc', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
		}
	}
);

remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );

/** Mapas do site ativos mesmo em demonstração (para validação); a indexação continua bloqueada. */
add_filter( 'wp_sitemaps_enabled', '__return_true' );

function dvs_seo_title(): string {
	if ( is_404() ) {
		return dvs_t( 'nf_title' ) . ' · DiversCape';
	}
	$id = get_queried_object_id();
	if ( $id ) {
		$custom = (string) get_post_meta( $id, '_dvs_seo_title', true );
		if ( $custom ) {
			return $custom;
		}
		return wp_strip_all_tags( get_the_title( $id ) ) . ' · DiversCape Sagres';
	}
	return 'DiversCape Sagres';
}

function dvs_seo_description(): string {
	$id = get_queried_object_id();
	if ( ! $id ) {
		return dvs_t( 'footer_tagline' );
	}
	$d = (string) get_post_meta( $id, '_dvs_seo_desc', true );
	if ( ! $d ) {
		$d = (string) get_post_field( 'post_excerpt', $id );
	}
	if ( ! $d ) {
		$d = wp_trim_words( wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $id ) ) ), 28, '…' );
	}
	return trim( preg_replace( '/\s+/', ' ', $d ) ) ?: dvs_t( 'footer_tagline' );
}

add_filter( 'pre_get_document_title', static fn() => dvs_seo_title() );

/** Imagem de partilha: imagem destacada, imagem do item, ou fotografia principal. */
function dvs_seo_image(): int {
	$id = get_queried_object_id();
	if ( $id && has_post_thumbnail( $id ) ) {
		return (int) get_post_thumbnail_id( $id );
	}
	if ( $id && 'dvs_offer' === get_post_type( $id ) ) {
		$item = dvs_offer_item( $id );
		if ( $item ) {
			return (int) ( $item['image_id'] ?: dvs_media_id( $item['image'] ) );
		}
	}
	return dvs_media_id( 'diver-anemone.jpg' );
}

/** Breadcrumbs: [[rótulo, url], …] do início do idioma até à página atual. */
function dvs_breadcrumbs(): array {
	$crumbs = array( array( dvs_t( 'home' ), dvs_home_url() ) );
	$obj    = get_queried_object();
	if ( ! $obj instanceof WP_Post || 'page:home' === dvs_post_group( $obj->ID ) ) {
		return $crumbs;
	}
	$chain = array();
	if ( 'dvs_offer' === $obj->post_type ) {
		$parent = dvs_offer_parent_page( $obj->ID );
		$chain  = $parent ? array_reverse( array_merge( array( $parent ), get_post_ancestors( $parent ) ) ) : array();
	} else {
		$chain = array_reverse( get_post_ancestors( $obj ) );
	}
	foreach ( $chain as $pid ) {
		if ( 'page:home' === dvs_post_group( (int) $pid ) ) {
			continue;
		}
		$crumbs[] = array( get_the_title( $pid ), get_permalink( $pid ) );
	}
	$crumbs[] = array( get_the_title( $obj ), get_permalink( $obj ) );
	return $crumbs;
}

add_action(
	'wp_head',
	static function () {
		$lang  = dvs_lang();
		$langs = dvs_languages();
		$id    = get_queried_object_id();
		$desc  = dvs_seo_description();
		$url   = $id && ! is_404() ? get_permalink( $id ) : '';

		echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
		if ( $url ) {
			echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
		}

		// hreflang recíproco: todas as traduções publicadas do mesmo grupo.
		if ( $id && ! is_404() && dvs_post_group( $id ) ) {
			$alts = array();
			foreach ( $langs as $code => $cfg ) {
				$tid = dvs_translation_id( $id, $code );
				if ( $tid && 'publish' === get_post_status( $tid ) ) {
					$alts[ $code ] = get_permalink( $tid );
				}
			}
			if ( count( $alts ) > 1 ) {
				foreach ( $alts as $code => $href ) {
					echo '<link rel="alternate" hreflang="' . esc_attr( $langs[ $code ]['hreflang'] ) . '" href="' . esc_url( $href ) . '">' . "\n";
				}
				if ( isset( $alts[ dvs_default_lang() ] ) ) {
					echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $alts[ dvs_default_lang() ] ) . '">' . "\n";
				}
			}
		}

		// Open Graph / Twitter.
		$img = dvs_seo_image();
		$src = $img ? wp_get_attachment_image_src( $img, 'large' ) : null;
		$og  = array(
			'og:site_name'   => 'DiversCape Sagres',
			'og:type'        => is_front_page() || ( $id && 'page:home' === dvs_post_group( $id ) ) ? 'website' : 'article',
			'og:title'       => dvs_seo_title(),
			'og:description' => $desc,
			'og:url'         => $url,
			'og:locale'      => $langs[ $lang ]['og'],
		);
		foreach ( $og as $p => $v ) {
			if ( $v ) {
				echo '<meta property="' . esc_attr( $p ) . '" content="' . esc_attr( $v ) . '">' . "\n";
			}
		}
		foreach ( $langs as $code => $cfg ) {
			if ( $code !== $lang ) {
				echo '<meta property="og:locale:alternate" content="' . esc_attr( $cfg['og'] ) . '">' . "\n";
			}
		}
		if ( $src ) {
			echo '<meta property="og:image" content="' . esc_url( $src[0] ) . '">' . "\n";
			echo '<meta property="og:image:width" content="' . (int) $src[1] . '">' . "\n";
			echo '<meta property="og:image:height" content="' . (int) $src[2] . '">' . "\n";
			echo '<meta property="og:image:alt" content="' . esc_attr( dvs_media_alt( $img ) ) . '">' . "\n";
		}
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

		// Dados estruturados.
		$graph = array();
		$org   = array(
			'@type'     => 'SportsActivityLocation',
			'@id'       => home_url( '/#organization' ),
			'name'      => dvs_business( 'name' ),
			'url'       => dvs_home_url(),
			'email'     => dvs_business( 'email' ),
			'telephone' => preg_replace( '/\s+/', '', dvs_business( 'phone' ) ),
			'address'   => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => dvs_business( 'street' ),
				'postalCode'      => dvs_business( 'postcode' ),
				'addressLocality' => dvs_business( 'city' ),
				'addressCountry'  => 'PT',
			),
			'geo'       => array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => (float) dvs_business( 'lat' ),
				'longitude' => (float) dvs_business( 'lng' ),
			),
			'sameAs'    => array_values( array_filter( array( dvs_business( 'facebook' ), dvs_business( 'tripadvisor' ) ) ) ),
		);
		$logo = dvs_media_id( 'logo-diverscape.png' );
		if ( $logo ) {
			$org['logo'] = wp_get_attachment_url( $logo );
		}
		if ( $src ) {
			$org['image'] = $src[0];
		}
		$graph[] = $org;

		$crumbs = dvs_breadcrumbs();
		if ( count( $crumbs ) > 1 ) {
			$list = array();
			foreach ( $crumbs as $i => $c ) {
				$list[] = array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => wp_strip_all_tags( $c[0] ), 'item' => $c[1] );
			}
			$graph[] = array( '@type' => 'BreadcrumbList', 'itemListElement' => $list );
		}

		if ( is_singular( 'dvs_offer' ) ) {
			$item = dvs_offer_item( $id );
			if ( $item && in_array( $item['category'], array( 'courses', 'specialties', 'first-aid', 'technical' ), true ) ) {
				$graph[] = array(
					'@type'       => 'Course',
					'name'        => wp_strip_all_tags( get_the_title( $id ) ),
					'description' => $desc,
					'inLanguage'  => $langs[ $lang ]['hreflang'],
					'provider'    => array( '@id' => home_url( '/#organization' ) ),
				);
			}
		}
		echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
	},
	2
);
