<?php
/**
 * Textos da interface (botões, rótulos, mensagens do formulário) nos quatro idiomas.
 *
 * Os valores por omissão estão em content/strings.php. O cliente pode substituí-los em
 * DiversCape → Textos da interface, sem tocar no código. Conteúdo editorial (páginas, cursos)
 * não vive aqui: edita-se nas próprias páginas.
 */

defined( 'ABSPATH' ) || exit;

function dvs_default_strings(): array {
	static $s = null;
	if ( null === $s ) {
		$s = require DVS_CORE_DIR . '/content/strings.php';
	}
	return $s;
}

/** Texto traduzido. Aceita argumentos sprintf. */
function dvs_t( string $key, ...$args ): string {
	static $over = null;
	if ( null === $over ) {
		$over = get_option( 'dvs_strings_override', array() );
		$over = is_array( $over ) ? $over : array();
	}
	$lang = dvs_lang();
	$def  = dvs_default_strings()[ $key ] ?? null;
	$text = $over[ $key ][ $lang ] ?? '';
	if ( '' === $text ) {
		$text = is_array( $def ) ? ( $def[ $lang ] ?? $def['en'] ?? $key ) : $key;
	}
	return $args ? vsprintf( $text, $args ) : $text;
}

/** Versão com escape para HTML. */
function dvs_e( string $key, ...$args ): void {
	echo esc_html( dvs_t( $key, ...$args ) );
}

add_action(
	'admin_menu',
	static function () {
		add_submenu_page( 'dvs-settings', 'Textos da interface', 'Textos da interface', 'manage_options', 'dvs-strings', 'dvs_strings_page' );
	},
	20
);

function dvs_strings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$over = get_option( 'dvs_strings_override', array() );
	$over = is_array( $over ) ? $over : array();
	if ( isset( $_POST['dvs_strings_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['dvs_strings_nonce'] ), 'dvs_strings' ) ) {
		$in   = isset( $_POST['s'] ) && is_array( $_POST['s'] ) ? wp_unslash( $_POST['s'] ) : array(); // phpcs:ignore
		$over = array();
		foreach ( dvs_default_strings() as $key => $def ) {
			foreach ( array_keys( dvs_languages() ) as $l ) {
				$v = isset( $in[ $key ][ $l ] ) ? sanitize_text_field( $in[ $key ][ $l ] ) : '';
				if ( '' !== $v && $v !== ( $def[ $l ] ?? '' ) ) {
					$over[ $key ][ $l ] = $v;
				}
			}
		}
		update_option( 'dvs_strings_override', $over, false );
		echo '<div class="notice notice-success"><p>Textos guardados.</p></div>';
	}
	$filter = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : ''; // phpcs:ignore
	echo '<div class="wrap"><h1>Textos da interface</h1>';
	echo '<p>Rótulos de botões, filtros, formulário e mensagens. Deixe um campo igual ao original para manter o valor por omissão. Os conteúdos das páginas e dos cursos editam-se nas próprias páginas.</p>';
	echo '<form method="get"><input type="hidden" name="page" value="dvs-strings"><p><input type="search" name="q" value="' . esc_attr( $filter ) . '" placeholder="Filtrar"> <button class="button">Filtrar</button></p></form>';
	echo '<form method="post">';
	wp_nonce_field( 'dvs_strings', 'dvs_strings_nonce' );
	echo '<table class="widefat striped"><thead><tr><th>Chave</th>';
	foreach ( dvs_languages() as $cfg ) {
		echo '<th>' . esc_html( $cfg['name'] ) . '</th>';
	}
	echo '</tr></thead><tbody>';
	foreach ( dvs_default_strings() as $key => $def ) {
		$hay = $key . ' ' . implode( ' ', $def );
		$hidden = $filter && false === stripos( $hay, $filter );
		echo '<tr' . ( $hidden ? ' style="display:none"' : '' ) . '><td><code>' . esc_html( $key ) . '</code></td>';
		foreach ( array_keys( dvs_languages() ) as $l ) {
			$val = $over[ $key ][ $l ] ?? ( $def[ $l ] ?? '' );
			printf( '<td><input type="text" class="widefat" name="s[%s][%s]" value="%s"></td>', esc_attr( $key ), esc_attr( $l ), esc_attr( $val ) );
		}
		echo '</tr>';
	}
	echo '</tbody></table><p><button class="button button-primary">Guardar textos</button></p></form></div>';
}
