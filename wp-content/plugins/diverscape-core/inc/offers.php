<?php
/**
 * Páginas de curso/experiência (dvs_offer): texto traduzido + ligação ao item do catálogo.
 *
 * Campos próprios de cada idioma:
 * - título, resumo (excerto), corpo (editor de blocos), imagem destacada (opcional)
 * - `_dvs_audience` — «Para quem é» (uma frase)
 * - `_dvs_faq`      — perguntas desta oferta: [ [q, a], … ] (só respostas confirmadas)
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_meta( 'dvs_offer', '_dvs_audience', array( 'type' => 'string', 'single' => true, 'show_in_rest' => false ) );
		register_post_meta( 'dvs_offer', '_dvs_faq', array( 'type' => 'array', 'single' => true, 'show_in_rest' => false ) );
	}
);

function dvs_offer_faq( int $offer_id ): array {
	$faq = get_post_meta( $offer_id, '_dvs_faq', true );
	return is_array( $faq ) ? array_values( array_filter( $faq, static fn( $r ) => ! empty( $r[0] ) && ! empty( $r[1] ) ) ) : array();
}

add_action(
	'add_meta_boxes_dvs_offer',
	static function () {
		add_meta_box( 'dvs-offer', 'Curso / experiência', 'dvs_offer_metabox', 'dvs_offer', 'normal', 'high' );
	}
);

function dvs_offer_metabox( WP_Post $post ): void {
	wp_nonce_field( 'dvs_offer_save', 'dvs_offer_nonce' );
	$current = (string) get_post_meta( $post->ID, '_dvs_item', true );
	$item    = $current ? dvs_item( $current ) : null;
	echo '<p><label for="dvs_item_key"><strong>Item do catálogo</strong> (preços, variantes e durações partilhados)</label><br><select id="dvs_item_key" name="dvs_item_key"><option value="">— escolher —</option>';
	foreach ( dvs_catalog() as $k => $i ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( $current, $k, false ), esc_html( $i['name'] ) );
	}
	echo '</select>';
	if ( $item ) {
		printf( ' <a href="%s">Editar preços e variantes</a>', esc_url( get_edit_post_link( $item['post_id'] ) ) );
		echo '<ul style="margin:.5em 0 0 1em;list-style:disc">';
		foreach ( $item['variants'] as $v ) {
			$price = 'fixed' === $v['type'] && null !== $v['price'] ? dvs_money( (float) $v['price'], 'pt' ) : ( 'on_request' === $v['type'] ? 'Sob consulta' : 'Por validar' );
			printf( '<li>%s — %s</li>', esc_html( $v['label']['pt'] ?? 'Variante única' ), esc_html( $price ) );
		}
		echo '</ul>';
	}
	echo '</p>';
	printf(
		'<p><label for="dvs_audience"><strong>Para quem é</strong></label><input id="dvs_audience" class="widefat" name="dvs_audience" value="%s"></p>',
		esc_attr( (string) get_post_meta( $post->ID, '_dvs_audience', true ) )
	);
	echo '<p><strong>Perguntas desta página</strong> — só respostas confirmadas pela equipa. Formato: pergunta numa linha, resposta na linha seguinte, linha em branco entre pares.</p>';
	$pairs = array_map( static fn( $r ) => $r[0] . "\n" . $r[1], dvs_offer_faq( $post->ID ) );
	printf( '<textarea class="widefat" rows="6" name="dvs_faq" aria-label="Perguntas e respostas">%s</textarea>', esc_textarea( implode( "\n\n", $pairs ) ) );
	echo '<p class="description">O resumo (excerto) aparece nos cartões do catálogo e na descrição para motores de pesquisa.</p>';
}

add_action(
	'save_post_dvs_offer',
	static function ( int $post_id ) {
		if ( ! isset( $_POST['dvs_offer_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['dvs_offer_nonce'] ), 'dvs_offer_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		$key = sanitize_title( wp_unslash( $_POST['dvs_item_key'] ?? '' ) );
		if ( $key && dvs_item( $key ) ) {
			update_post_meta( $post_id, '_dvs_item', $key );
			update_post_meta( $post_id, '_dvs_category', dvs_item( $key )['category'] );
		}
		update_post_meta( $post_id, '_dvs_audience', sanitize_text_field( wp_unslash( $_POST['dvs_audience'] ?? '' ) ) );
		$raw   = preg_split( "/\r?\n\s*\r?\n/", trim( (string) wp_unslash( $_POST['dvs_faq'] ?? '' ) ) );
		$pairs = array();
		foreach ( $raw as $block ) {
			$lines = preg_split( "/\r?\n/", trim( $block ), 2 );
			if ( count( $lines ) === 2 ) {
				$pairs[] = array( sanitize_text_field( $lines[0] ), sanitize_textarea_field( $lines[1] ) );
			}
		}
		update_post_meta( $post_id, '_dvs_faq', $pairs );
	}
);
