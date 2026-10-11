<?php
/**
 * Media: importação idempotente, origem registada, texto alternativo e legenda por idioma, galeria.
 *
 * Metadados do anexo:
 * - `_dvs_key`     — identificador estável (nome do ficheiro de origem)
 * - `_dvs_source`  — origem (URL no site atual, licença)
 * - `_dvs_alt`     — [lang => texto alternativo]
 * - `_dvs_caption` — [lang => legenda]
 * - `_dvs_gallery` — posição na galeria (0 = fora da galeria)
 * - `_dvs_review`  — nota interna (ex.: identificação de espécie a validar)
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function () {
		add_image_size( 'dvs-card', 720, 540, true );
		add_image_size( 'dvs-portrait', 640, 800, true );
	}
);

/* Tamanhos gerados em WebP (o original mantém-se em JPEG). */
add_filter(
	'image_editor_output_format',
	static function ( array $formats ) {
		if ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
			$formats['image/jpeg'] = 'image/webp';
		}
		return $formats;
	}
);
add_filter( 'wp_editor_set_quality', static fn( $q, $mime ) => 'image/webp' === $mime ? 78 : $q, 10, 2 );
add_filter( 'big_image_size_threshold', static fn() => 2400 );

function dvs_media_id( string $key ): int {
	static $cache = array();
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	$ids           = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_dvs_key', // phpcs:ignore
			'meta_value'  => $key, // phpcs:ignore
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	$cache[ $key ] = $ids ? (int) $ids[0] : 0;
	return $cache[ $key ];
}

/** Texto alternativo no idioma atual. */
function dvs_media_alt( int $id, ?string $lang = null ): string {
	$lang = $lang ?: dvs_lang();
	$alts = get_post_meta( $id, '_dvs_alt', true );
	if ( is_array( $alts ) && ! empty( $alts[ $lang ] ) ) {
		return $alts[ $lang ];
	}
	return (string) get_post_meta( $id, '_wp_attachment_image_alt', true );
}

function dvs_media_caption( int $id, ?string $lang = null ): string {
	$lang = $lang ?: dvs_lang();
	$c    = get_post_meta( $id, '_dvs_caption', true );
	return is_array( $c ) ? (string) ( $c[ $lang ] ?? $c['en'] ?? '' ) : '';
}

/**
 * <img> responsivo com alt no idioma atual.
 *
 * @param int|string $image ID do anexo ou chave (nome do ficheiro).
 */
function dvs_img( $image, string $size = 'large', array $attr = array() ): string {
	$id = is_numeric( $image ) ? (int) $image : dvs_media_id( (string) $image );
	if ( ! $id ) {
		return '';
	}
	$attr = array_merge(
		array(
			'alt'      => dvs_media_alt( $id ),
			'loading'  => 'lazy',
			'decoding' => 'async',
		),
		$attr
	);
	if ( isset( $attr['loading'] ) && 'eager' === $attr['loading'] ) {
		$attr['fetchpriority'] = $attr['fetchpriority'] ?? 'high';
	}
	return wp_get_attachment_image( $id, $size, false, $attr );
}

/** IDs da galeria por ordem. */
function dvs_gallery_ids(): array {
	$ids = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_key'    => '_dvs_gallery', // phpcs:ignore
			'orderby'     => 'meta_value_num',
			'order'       => 'ASC',
			'meta_query'  => array( array( 'key' => '_dvs_gallery', 'value' => 0, 'compare' => '>', 'type' => 'NUMERIC' ) ), // phpcs:ignore
		)
	);
	return array_map( 'intval', $ids );
}

/**
 * Importa um ficheiro local para a biblioteca (idempotente pela chave). Atualiza metadados se já existir.
 */
function dvs_import_media( string $file, string $key, array $meta = array() ): int {
	$id = dvs_media_id( $key );
	if ( ! $id ) {
		if ( ! is_readable( $file ) ) {
			return 0;
		}
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		$tmp = wp_tempnam( basename( $file ) );
		copy( $file, $tmp );
		$id = media_handle_sideload( array( 'name' => basename( $file ), 'tmp_name' => $tmp ), 0, $meta['title'] ?? '' );
		if ( is_wp_error( $id ) ) {
			@unlink( $tmp ); // phpcs:ignore
			return 0;
		}
		update_post_meta( $id, '_dvs_key', $key );
	}
	if ( isset( $meta['alt'] ) ) {
		update_post_meta( $id, '_dvs_alt', $meta['alt'] );
		update_post_meta( $id, '_wp_attachment_image_alt', $meta['alt']['en'] ?? '' );
	}
	foreach ( array( 'caption' => '_dvs_caption', 'source' => '_dvs_source', 'gallery' => '_dvs_gallery', 'review' => '_dvs_review' ) as $k => $mk ) {
		if ( isset( $meta[ $k ] ) ) {
			update_post_meta( $id, $mk, $meta[ $k ] );
		}
	}
	return (int) $id;
}

/* Campos no ecrã do anexo: alt e legenda nos quatro idiomas, posição na galeria, origem. */
add_filter(
	'attachment_fields_to_edit',
	static function ( array $fields, WP_Post $post ): array {
		$alts = (array) get_post_meta( $post->ID, '_dvs_alt', true );
		$caps = (array) get_post_meta( $post->ID, '_dvs_caption', true );
		foreach ( dvs_languages() as $l => $cfg ) {
			$fields[ 'dvs_alt_' . $l ] = array(
				'label' => 'Texto alternativo ' . $cfg['short'],
				'input' => 'text',
				'value' => (string) ( $alts[ $l ] ?? '' ),
			);
			$fields[ 'dvs_caption_' . $l ] = array(
				'label' => 'Legenda ' . $cfg['short'],
				'input' => 'text',
				'value' => (string) ( $caps[ $l ] ?? '' ),
			);
		}
		$fields['dvs_gallery'] = array(
			'label' => 'Posição na galeria',
			'input' => 'text',
			'value' => (string) get_post_meta( $post->ID, '_dvs_gallery', true ),
			'helps' => '0 ou vazio = fora da galeria. As primeiras oito aparecem na homepage.',
		);
		$fields['dvs_source'] = array(
			'label' => 'Origem / licença',
			'input' => 'text',
			'value' => (string) get_post_meta( $post->ID, '_dvs_source', true ),
		);
		$review = (string) get_post_meta( $post->ID, '_dvs_review', true );
		if ( $review ) {
			$fields['dvs_review'] = array(
				'label' => 'A validar',
				'input' => 'html',
				'html'  => '<p style="color:#996800;margin:0">' . esc_html( $review ) . '</p>',
			);
		}
		return $fields;
	},
	10,
	2
);

add_filter(
	'attachment_fields_to_save',
	static function ( array $post, array $att ): array {
		$alts = array();
		$caps = array();
		foreach ( array_keys( dvs_languages() ) as $l ) {
			if ( isset( $att[ 'dvs_alt_' . $l ] ) ) {
				$alts[ $l ] = sanitize_text_field( $att[ 'dvs_alt_' . $l ] );
			}
			if ( isset( $att[ 'dvs_caption_' . $l ] ) ) {
				$caps[ $l ] = sanitize_text_field( $att[ 'dvs_caption_' . $l ] );
			}
		}
		if ( $alts ) {
			update_post_meta( $post['ID'], '_dvs_alt', $alts );
		}
		if ( $caps ) {
			update_post_meta( $post['ID'], '_dvs_caption', $caps );
		}
		if ( isset( $att['dvs_gallery'] ) ) {
			update_post_meta( $post['ID'], '_dvs_gallery', absint( $att['dvs_gallery'] ) );
		}
		if ( isset( $att['dvs_source'] ) ) {
			update_post_meta( $post['ID'], '_dvs_source', sanitize_text_field( $att['dvs_source'] ) );
		}
		return $post;
	},
	10,
	2
);
