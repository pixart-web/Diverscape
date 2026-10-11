<?php
/**
 * Tipos de conteúdo:
 * - dvs_item  — catálogo central (não traduzível, só no painel): preços, variantes, durações, público.
 * - dvs_offer — página de curso/experiência num idioma (texto). URL por baixo da página da categoria.
 * - dvs_faq   — pergunta frequente num idioma. Publicada = resposta confirmada; rascunho = a validar.
 */

defined( 'ABSPATH' ) || exit;

function dvs_register_content_types(): void {
	register_post_type(
		'dvs_item',
		array(
			'labels'        => array(
				'name'          => 'Catálogo e preços',
				'singular_name' => 'Item do catálogo',
				'add_new_item'  => 'Novo item do catálogo',
				'edit_item'     => 'Editar item do catálogo',
				'menu_name'     => 'Catálogo e preços',
				'all_items'     => 'Todos os itens',
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => 'dvs-settings',
			'supports'      => array( 'title', 'page-attributes' ),
			'map_meta_cap'  => true,
			'capability_type' => 'page',
		)
	);

	register_post_type(
		'dvs_offer',
		array(
			'labels'       => array(
				'name'          => 'Cursos e experiências',
				'singular_name' => 'Curso / experiência',
				'add_new_item'  => 'Nova página de curso ou experiência',
				'edit_item'     => 'Editar página de curso ou experiência',
				'menu_name'     => 'Cursos e experiências',
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => false,
			'rewrite'      => false,
			'query_var'    => false,
			'menu_icon'    => 'dashicons-palmtree',
			'menu_position' => 21,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'custom-fields' ),
			'template'     => array( array( 'core/paragraph' ) ),
		)
	);

	register_post_type(
		'dvs_faq',
		array(
			'labels'       => array(
				'name'          => 'Perguntas frequentes',
				'singular_name' => 'Pergunta frequente',
				'add_new_item'  => 'Nova pergunta',
				'edit_item'     => 'Editar pergunta',
				'menu_name'     => 'Perguntas frequentes',
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-editor-help',
			'menu_position' => 22,
			'supports'     => array( 'title', 'editor', 'page-attributes', 'revisions' ),
		)
	);

	register_post_meta( 'dvs_offer', '_dvs_item', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
	register_post_meta( 'dvs_offer', '_dvs_category', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
	register_post_meta( 'dvs_faq', '_dvs_topic', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
}
add_action( 'init', 'dvs_register_content_types', 5 );

/** URL das ofertas: página da categoria no idioma da oferta + slug. */
add_filter(
	'post_type_link',
	static function ( string $link, WP_Post $post ) {
		if ( 'dvs_offer' !== $post->post_type ) {
			return $link;
		}
		$parent = dvs_offer_parent_page( $post->ID );
		if ( ! $parent || ! $post->post_name ) {
			return $link;
		}
		return trailingslashit( get_permalink( $parent ) ) . $post->post_name . '/';
	},
	10,
	2
);

/** Ofertas sem idioma não aparecem no site (evita páginas órfãs). */
add_action(
	'template_redirect',
	static function () {
		if ( is_singular( 'dvs_offer' ) && ! dvs_post_lang( get_queried_object_id() ) ) {
			global $wp_query;
			$wp_query->set_404();
			status_header( 404 );
		}
	}
);

/** Excluir do mapa do site o que não é público. */
add_filter(
	'wp_sitemaps_post_types',
	static function ( array $types ) {
		unset( $types['dvs_item'], $types['dvs_faq'], $types['attachment'] );
		return $types;
	}
);
add_filter( 'wp_sitemaps_add_provider', static fn( $provider, $name ) => 'users' === $name ? false : $provider, 10, 2 );
add_filter( 'wp_sitemaps_taxonomies', '__return_empty_array' );
