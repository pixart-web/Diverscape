<?php
/**
 * Dados da empresa (contactos, morada, ligações externas) e definições gerais.
 * Valores por omissão = publicados no site atual em 09/10/2026; a reconfirmar antes do lançamento.
 */

defined( 'ABSPATH' ) || exit;

function dvs_business_defaults(): array {
	return array(
		'name'            => 'DiversCape',
		'legal_ref'       => 'RNAAT 19/2009',
		'email'           => 'dive@diverscape.net',
		'phone'           => '+351 965 559 073',
		'street'          => 'Porto da Baleeira',
		'postcode'        => '8650-368',
		'city'            => 'Sagres',
		'country'         => 'Portugal',
		'lat'             => '37.010088',
		'lng'             => '-8.932488',
		'facebook'        => 'https://www.facebook.com/diverscapesagres/',
		'tripadvisor'     => 'https://www.tripadvisor.com/Attraction_Review-g189121-d4009789-Reviews-Divers_Cape-Sagres_Faro_District_Algarve.html',
		'tripadvisor_id'  => '4009789',
		'padi_url'        => 'https://www.padi.com/',
		'park_url'        => 'https://natural.pt/portal/en/AreaProtegida/Item/12',
	);
}

function dvs_business( string $key ): string {
	static $opt = null;
	if ( null === $opt ) {
		$opt = get_option( 'dvs_business', array() );
		$opt = is_array( $opt ) ? $opt : array();
	}
	return (string) ( $opt[ $key ] ?? dvs_business_defaults()[ $key ] ?? '' );
}

function dvs_phone_href(): string {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', dvs_business( 'phone' ) );
}

function dvs_directions_url(): string {
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( dvs_business( 'lat' ) . ',' . dvs_business( 'lng' ) );
}

/** Modo do formulário: demo (valida, não envia) | live (envia email ao destinatário configurado). */
function dvs_form_mode(): string {
	$mode = apply_filters( 'dvs_form_mode', get_option( 'dvs_form_mode', 'demo' ) );
	return 'live' === $mode ? 'live' : 'demo';
}

add_action(
	'admin_menu',
	static function () {
		add_menu_page( 'DiversCape', 'DiversCape', 'edit_pages', 'dvs-settings', 'dvs_settings_page', 'dashicons-sos', 20 );
		add_submenu_page( 'dvs-settings', 'Dados e definições', 'Dados e definições', 'manage_options', 'dvs-settings', 'dvs_settings_page' );
	},
	9
);

function dvs_settings_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		echo '<div class="wrap"><h1>DiversCape</h1><p>Use os menus «Catálogo e preços», «Cursos e experiências» e «Páginas».</p></div>';
		return;
	}
	if ( isset( $_POST['dvs_settings_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['dvs_settings_nonce'] ), 'dvs_settings' ) ) {
		$in  = isset( $_POST['b'] ) && is_array( $_POST['b'] ) ? wp_unslash( $_POST['b'] ) : array(); // phpcs:ignore
		$out = array();
		foreach ( dvs_business_defaults() as $k => $def ) {
			$v         = isset( $in[ $k ] ) ? trim( (string) $in[ $k ] ) : $def;
			$out[ $k ] = in_array( $k, array( 'facebook', 'tripadvisor', 'padi_url', 'park_url' ), true ) ? esc_url_raw( $v ) : ( 'email' === $k ? sanitize_email( $v ) : sanitize_text_field( $v ) );
		}
		update_option( 'dvs_business', $out );
		update_option( 'dvs_price_mode', 'hidden' === ( $_POST['price_mode'] ?? '' ) ? 'hidden' : 'published' );
		if ( ! defined( 'DVS_DEMO_MODE' ) ) {
			update_option( 'dvs_form_mode', 'live' === ( $_POST['form_mode'] ?? '' ) ? 'live' : 'demo' );
		}
		update_option( 'dvs_form_recipient', sanitize_email( wp_unslash( $_POST['form_recipient'] ?? '' ) ) );
		echo '<div class="notice notice-success"><p>Definições guardadas.</p></div>';
	}
	$labels = array(
		'name' => 'Nome', 'legal_ref' => 'Referência RNAAT publicada', 'email' => 'Email', 'phone' => 'Telefone',
		'street' => 'Morada', 'postcode' => 'Código postal', 'city' => 'Localidade', 'country' => 'País',
		'lat' => 'Latitude', 'lng' => 'Longitude', 'facebook' => 'Facebook', 'tripadvisor' => 'Página Tripadvisor',
		'tripadvisor_id' => 'ID Tripadvisor (widget)', 'padi_url' => 'Ligação PADI', 'park_url' => 'Ligação do parque natural',
	);
	echo '<div class="wrap"><h1>DiversCape — dados e definições</h1><form method="post">';
	wp_nonce_field( 'dvs_settings', 'dvs_settings_nonce' );
	echo '<h2>Dados da empresa</h2><p>Valores do site atual, a reconfirmar antes do lançamento.</p><table class="form-table">';
	foreach ( $labels as $k => $l ) {
		printf( '<tr><th><label for="b_%1$s">%2$s</label></th><td><input class="regular-text" id="b_%1$s" name="b[%1$s]" value="%3$s"></td></tr>', esc_attr( $k ), esc_html( $l ), esc_attr( dvs_business( $k ) ) );
	}
	echo '</table><h2>Preços</h2><table class="form-table"><tr><th>Apresentação</th><td>';
	printf( '<label><input type="radio" name="price_mode" value="published" %s> Mostrar preços publicados no site atual, com aviso «sujeitos a confirmação»</label><br>', checked( dvs_price_mode(), 'published', false ) );
	printf( '<label><input type="radio" name="price_mode" value="hidden" %s> Esconder todos os preços («Consultar»)</label>', checked( dvs_price_mode(), 'hidden', false ) );
	echo '</td></tr></table><h2>Pedido de disponibilidade</h2><table class="form-table"><tr><th>Modo</th><td>';
	if ( defined( 'DVS_DEMO_MODE' ) ) {
		echo '<p><strong>Demonstração</strong> — imposto pelo plugin «DiversCape Demo Mode». Nenhum pedido é enviado nem guardado.</p>';
	} else {
		printf( '<label><input type="radio" name="form_mode" value="demo" %s> Demonstração (valida, não envia)</label><br>', checked( dvs_form_mode(), 'demo', false ) );
		printf( '<label><input type="radio" name="form_mode" value="live" %s> Envio real por email</label>', checked( dvs_form_mode(), 'live', false ) );
	}
	printf( '</td></tr><tr><th><label for="form_recipient">Destinatário</label></th><td><input class="regular-text" id="form_recipient" name="form_recipient" value="%s"><p class="description">Testar apenas com um destinatário de teste autorizado antes de usar o endereço da empresa.</p></td></tr></table>', esc_attr( (string) get_option( 'dvs_form_recipient', '' ) ) );
	echo '<p><button class="button button-primary">Guardar</button></p></form></div>';
}

/* Email local: encaminha para o Mailpit quando a variável DVS_SMTP_HOST existe (ambiente Docker). */
add_action(
	'phpmailer_init',
	static function ( $mailer ) {
		$host = getenv( 'DVS_SMTP_HOST' );
		if ( $host ) {
			$mailer->isSMTP();
			$mailer->Host     = $host; // phpcs:ignore
			$mailer->Port     = 1025; // phpcs:ignore
			$mailer->SMTPAuth = false; // phpcs:ignore
		}
	}
);
