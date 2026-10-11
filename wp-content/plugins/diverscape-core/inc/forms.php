<?php
/**
 * Pedido de disponibilidade.
 *
 * - Quatro passos (atividade → datas e grupo → dados → rever e enviar), com melhoria progressiva:
 *   sem JavaScript, todos os passos aparecem num só formulário e o envio é tratado pelo servidor.
 * - Validação sempre no servidor (REST para o envio em JavaScript; POST normal como alternativa).
 * - Anti-spam: campo isco, token assinado com tempo mínimo de preenchimento, limite de frequência.
 * - Modo demonstração (por omissão): valida e responde que nada foi enviado. Nada é guardado.
 * - Modo real: envia um email ao destinatário configurado. Nada é guardado na base de dados.
 * - Não recolhe dados clínicos, documentos nem dados detalhados de menores.
 */

defined( 'ABSPATH' ) || exit;

const DVS_FORM_MIN_SECONDS = 3;
const DVS_FORM_MAX_AGE     = 4 * HOUR_IN_SECONDS;
const DVS_FORM_RATE_LIMIT  = 8;

function dvs_form_token(): string {
	$t = (string) time();
	return $t . '.' . hash_hmac( 'sha256', 'dvs-form|' . $t, wp_salt( 'nonce' ) );
}

function dvs_form_token_valid( string $token ): string {
	$parts = explode( '.', $token );
	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return 'spam';
	}
	if ( ! hash_equals( hash_hmac( 'sha256', 'dvs-form|' . $parts[0], wp_salt( 'nonce' ) ), $parts[1] ) ) {
		return 'spam';
	}
	$age = time() - (int) $parts[0];
	if ( $age < DVS_FORM_MIN_SECONDS ) {
		return 'spam';
	}
	if ( $age > DVS_FORM_MAX_AGE ) {
		return 'expired';
	}
	return '';
}

/** Limite de frequência por IP (guardado apenas um hash, durante 10 minutos). */
function dvs_form_rate_limited(): bool {
	$ip  = (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ); // phpcs:ignore
	$key = 'dvs_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'auth' ) ), 0, 20 );
	$n   = (int) get_transient( $key );
	if ( $n >= DVS_FORM_RATE_LIMIT ) {
		return true;
	}
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );
	return false;
}

function dvs_form_levels(): array {
	return array( 'none' => 'f_level_none', 'tried' => 'f_level_tried', 'ow' => 'f_level_ow', 'adv' => 'f_level_adv', 'tec' => 'f_level_tec', 'mixed' => 'f_level_mixed' );
}

function dvs_form_flex(): array {
	return array( 'exact' => 'f_flex_exact', 'days' => 'f_flex_days', 'any' => 'f_flex_any' );
}

/**
 * Valida os dados recebidos.
 *
 * @return array{data:array, errors:array<string,string>, fatal:string}
 */
function dvs_form_validate( array $in ): array {
	$errors = array();
	$fatal  = '';
	$get    = static fn( $k, $max = 200 ) => trim( mb_substr( sanitize_text_field( wp_unslash( (string) ( $in[ $k ] ?? '' ) ) ), 0, $max ) );

	if ( '' !== trim( (string) ( $in['website'] ?? '' ) ) ) {
		$fatal = 'spam';
	}
	$tok = dvs_form_token_valid( (string) ( $in['dvs_token'] ?? '' ) );
	if ( $tok ) {
		$fatal = $fatal ?: $tok;
	}

	$data = array(
		'offer'      => sanitize_key( $in['offer'] ?? '' ),
		'variant'    => sanitize_text_field( wp_unslash( (string) ( $in['variant'] ?? '' ) ) ),
		'date'       => $get( 'date', 10 ),
		'flex'       => sanitize_key( $in['flex'] ?? '' ),
		'people'     => $get( 'people', 3 ),
		'level'      => sanitize_key( $in['level'] ?? '' ),
		'name'       => $get( 'name', 120 ),
		'email'      => $get( 'email', 160 ),
		'phone'      => $get( 'phone', 30 ),
		'reply_lang' => sanitize_key( $in['reply_lang'] ?? dvs_lang() ),
		'message'    => trim( sanitize_textarea_field( wp_unslash( (string) ( $in['message'] ?? '' ) ) ) ),
		'privacy'    => ! empty( $in['privacy'] ),
	);

	// Passo 1.
	$special = array( 'unsure', 'general' );
	if ( ! $data['offer'] || ( ! in_array( $data['offer'], $special, true ) && ! dvs_item( $data['offer'] ) ) ) {
		$errors['offer'] = dvs_t( 'e_offer' );
	}
	if ( $data['variant'] ) {
		$item = dvs_item( $data['offer'] );
		$vkey = str_contains( $data['variant'], ':' ) ? explode( ':', $data['variant'], 2 ) : array( $data['offer'], $data['variant'] );
		if ( ! $item || $vkey[0] !== $data['offer'] || ! dvs_variant( $item, $vkey[1] ) ) {
			$data['variant'] = '';
		} else {
			$data['variant'] = $vkey[1];
		}
	}
	// Passo 2.
	if ( '' !== $data['date'] ) {
		$d = DateTime::createFromFormat( '!Y-m-d', $data['date'], wp_timezone() );
		$today = new DateTime( 'today', wp_timezone() );
		if ( ! $d || $d->format( 'Y-m-d' ) !== $data['date'] || $d < $today ) {
			$errors['date'] = dvs_t( 'e_date' );
		}
	}
	if ( ! array_key_exists( $data['flex'], dvs_form_flex() ) ) {
		$data['flex'] = '';
	}
	if ( '' === $data['people'] || ! ctype_digit( $data['people'] ) || (int) $data['people'] < 1 || (int) $data['people'] > 20 ) {
		$errors['people'] = dvs_t( 'e_people' );
	}
	if ( ! array_key_exists( $data['level'], dvs_form_levels() ) ) {
		$data['level'] = '';
	}
	// Passo 3.
	if ( '' === $data['name'] ) {
		$errors['name'] = dvs_t( 'e_required' );
	}
	if ( '' === $data['email'] ) {
		$errors['email'] = dvs_t( 'e_required' );
	} elseif ( ! is_email( $data['email'] ) ) {
		$errors['email'] = dvs_t( 'e_email' );
	}
	if ( '' !== $data['phone'] && ! preg_match( '/^\+?[0-9 ()\-]{6,20}$/', $data['phone'] ) ) {
		$errors['phone'] = dvs_t( 'e_phone' );
	}
	if ( ! dvs_is_lang( $data['reply_lang'] ) ) {
		$data['reply_lang'] = dvs_lang();
	}
	if ( mb_strlen( $data['message'] ) > 2000 ) {
		$errors['message'] = dvs_t( 'e_long' );
	}
	// Passo 4.
	if ( ! $data['privacy'] ) {
		$errors['privacy'] = dvs_t( 'e_privacy' );
	}
	return array( 'data' => $data, 'errors' => $errors, 'fatal' => $fatal );
}

/** Passo de cada campo (para o JavaScript reabrir o passo com erro). */
function dvs_form_field_step( string $field ): int {
	$map = array( 'offer' => 1, 'variant' => 1, 'date' => 2, 'flex' => 2, 'people' => 2, 'level' => 2, 'name' => 3, 'email' => 3, 'phone' => 3, 'reply_lang' => 3, 'message' => 3, 'privacy' => 4 );
	return $map[ $field ] ?? 1;
}

/** Resumo legível do pedido: [[rótulo, valor], …]. */
function dvs_form_summary( array $d ): array {
	$offer = dvs_t( 'f_not_set' );
	if ( 'unsure' === $d['offer'] ) {
		$offer = dvs_t( 'f_offer_unsure' );
	} elseif ( 'general' === $d['offer'] ) {
		$offer = dvs_t( 'f_offer_general' );
	} elseif ( $item = dvs_item( $d['offer'] ) ) { // phpcs:ignore
		$offer = dvs_item_title( $item );
		if ( $d['variant'] && ( $v = dvs_variant( $item, $d['variant'] ) ) ) { // phpcs:ignore
			$label = dvs_variant_label( $v );
			$offer .= ( $label ? ' — ' . $label : '' ) . ' (' . dvs_variant_price_text( $v ) . ')';
		} else {
			$offer .= ' (' . dvs_item_price( $item )['text'] . ')';
		}
	}
	$date = $d['date'] ? wp_date( get_option( 'date_format' ) ?: 'j F Y', strtotime( $d['date'] . ' 12:00' ) ) : dvs_t( 'f_not_set' );
	if ( $d['flex'] ) {
		$date .= ' · ' . dvs_t( dvs_form_flex()[ $d['flex'] ] );
	}
	return array(
		array( dvs_t( 'f_offer' ), $offer ),
		array( dvs_t( 'f_date' ), $date ),
		array( dvs_t( 'f_people' ), (string) $d['people'] ),
		array( dvs_t( 'f_level' ), $d['level'] ? dvs_t( dvs_form_levels()[ $d['level'] ] ) : dvs_t( 'f_not_set' ) ),
		array( dvs_t( 'f_name' ), $d['name'] ),
		array( dvs_t( 'f_email' ), $d['email'] ),
		array( dvs_t( 'f_phone' ), $d['phone'] ?: dvs_t( 'f_not_set' ) ),
		array( dvs_t( 'f_lang' ), dvs_languages()[ $d['reply_lang'] ]['name'] ?? '' ),
		array( dvs_t( 'f_message' ), $d['message'] ?: dvs_t( 'f_not_set' ) ),
	);
}

/**
 * Processa um envio. Devolve o resultado para JSON ou para a renderização sem JavaScript.
 */
function dvs_form_process( array $in ): array {
	if ( dvs_form_rate_limited() ) {
		return array( 'ok' => false, 'status' => 429, 'message' => dvs_t( 'e_rate' ), 'errors' => array() );
	}
	$v = dvs_form_validate( $in );
	$honeypot = '' !== trim( (string) ( $in['website'] ?? '' ) );
	// Erros de preenchimento primeiro (nada é enviado); o campo isco é recusado sempre.
	if ( $v['errors'] && ! $honeypot ) {
		$steps = array();
		foreach ( array_keys( $v['errors'] ) as $f ) {
			$steps[ $f ] = dvs_form_field_step( $f );
		}
		return array( 'ok' => false, 'status' => 422, 'message' => dvs_t( 'f_error_summary' ), 'errors' => $v['errors'], 'steps' => $steps, 'data' => $v['data'] );
	}
	if ( $v['fatal'] ) {
		return array( 'ok' => false, 'status' => 400, 'message' => dvs_t( 'e_spam' ), 'errors' => array() );
	}
	if ( 'demo' === dvs_form_mode() ) {
		/**
		 * Modo demonstração: nada é enviado nem guardado. O filtro permite ao plugin Demo Mode
		 * registar (sem dados pessoais) que houve uma verificação.
		 */
		do_action( 'dvs_form_demo_checked' );
		return array( 'ok' => true, 'status' => 200, 'mode' => 'demo', 'title' => dvs_t( 'f_demo_title' ), 'message' => dvs_t( 'f_demo_text' ) );
	}
	$to = sanitize_email( (string) get_option( 'dvs_form_recipient', '' ) );
	if ( ! $to ) {
		return array( 'ok' => false, 'status' => 500, 'message' => dvs_t( 'e_send' ), 'errors' => array() );
	}
	$lines = array();
	foreach ( dvs_form_summary( $v['data'] ) as $row ) {
		$lines[] = $row[0] . ': ' . $row[1];
	}
	$lines[] = '';
	$lines[] = 'Idioma da página: ' . dvs_lang();
	$sent    = wp_mail(
		$to,
		'[DiversCape] Pedido de disponibilidade — ' . $v['data']['name'],
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $v['data']['name'] . ' <' . $v['data']['email'] . '>' )
	);
	if ( ! $sent ) {
		return array( 'ok' => false, 'status' => 500, 'message' => dvs_t( 'e_send' ), 'errors' => array() );
	}
	return array( 'ok' => true, 'status' => 200, 'mode' => 'live', 'title' => dvs_t( 'f_success_title' ), 'message' => dvs_t( 'f_success_text' ) );
}

/* REST: POST /wp-json/diverscape/v1/request */
add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'diverscape/v1',
			'/request',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => static function ( WP_REST_Request $req ) {
					$params = $req->get_body_params() ?: $req->get_json_params() ?: array();
					dvs_set_lang( dvs_is_lang( $params['lang'] ?? '' ) ? $params['lang'] : 'en' );
					$res = dvs_form_process( (array) $params );
					return new WP_REST_Response( $res, $res['status'] );
				},
			)
		);
	}
);

/* Alternativa sem JavaScript: o formulário envia para a própria página. */
add_action(
	'template_redirect',
	static function () {
		if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || empty( $_POST['dvs_request'] ) ) { // phpcs:ignore
			return;
		}
		$GLOBALS['dvs_form_result'] = dvs_form_process( wp_unslash( $_POST ) ); // phpcs:ignore
		nocache_headers();
	},
	5
);

/* ---------------------------------------------------------------------------------------------
 * Renderização
 * ------------------------------------------------------------------------------------------- */

function dvs_render_request_form( array $attrs = array() ): string {
	$res    = $GLOBALS['dvs_form_result'] ?? null;
	$posted = $res['data'] ?? array();
	$errors = $res['errors'] ?? array();
	$offer  = sanitize_key( $posted['offer'] ?? ( $_GET['offer'] ?? '' ) ); // phpcs:ignore
	$var    = sanitize_text_field( wp_unslash( $posted['variant'] ?? ( $_GET['variant'] ?? '' ) ) ); // phpcs:ignore
	if ( $offer && ! dvs_item( $offer ) && ! in_array( $offer, array( 'unsure', 'general' ), true ) ) {
		$offer = '';
	}
	$val = static fn( $k, $def = '' ) => esc_attr( (string) ( $posted[ $k ] ?? $def ) );
	$err = static function ( $k ) use ( $errors ) {
		return isset( $errors[ $k ] ) ? '<p class="dvs-field__error" id="err-' . esc_attr( $k ) . '">' . esc_html( $errors[ $k ] ) . '</p>' : '';
	};
	$inv = static fn( $k, $help = '' ) => isset( $errors[ $k ] ) ? ' aria-invalid="true" aria-describedby="err-' . esc_attr( $k ) . ( $help ? ' ' . $help : '' ) . '"' : ( $help ? ' aria-describedby="' . $help . '"' : '' );
	$demo  = 'demo' === dvs_form_mode();
	$steps = array( 1 => 'step1', 2 => 'step2', 3 => 'step3', 4 => 'step4' );
	$cats  = dvs_categories();

	ob_start();
	?>
	<div class="dvs-request" id="request" data-dvs-request data-rest="<?php echo esc_url( rest_url( 'diverscape/v1/request' ) ); ?>">
		<?php if ( $res && $res['ok'] ) : ?>
			<div class="dvs-request__result" role="status" tabindex="-1" data-dvs-result>
				<h2 class="dvs-request__result-title"><?php echo esc_html( $res['title'] ); ?></h2>
				<p><?php echo esc_html( $res['message'] ); ?></p>
				<p><a class="dvs-btn dvs-btn--ghost" href="<?php echo esc_url( dvs_url( 'page:contact' ) ); ?>#request"><?php dvs_e( 'f_new_request' ); ?></a></p>
			</div>
		<?php else : ?>
		<?php if ( $demo ) : ?>
			<p class="dvs-request__demo" role="note"><?php dvs_e( 'f_demo_notice' ); ?></p>
		<?php endif; ?>
		<ol class="dvs-steps" aria-label="<?php echo esc_attr( dvs_t( 'form_steps' ) ); ?>" data-dvs-steps hidden>
			<?php foreach ( $steps as $n => $key ) : ?>
				<li class="dvs-steps__item" data-step-indicator="<?php echo (int) $n; ?>"><span class="dvs-steps__num" aria-hidden="true"><?php echo (int) $n; ?></span><span class="dvs-steps__label"><?php dvs_e( $key ); ?></span></li>
			<?php endforeach; ?>
		</ol>
		<div class="dvs-request__live visually-hidden" aria-live="polite" data-dvs-live></div>
		<div class="dvs-request__errors" role="alert" data-dvs-errors <?php echo ( $res && ! $res['ok'] ) ? '' : 'hidden'; ?> tabindex="-1">
			<?php if ( $res && ! $res['ok'] ) : ?>
				<p><strong><?php echo esc_html( $res['message'] ); ?></strong></p>
				<?php if ( $errors ) : ?>
					<ul><?php foreach ( $errors as $f => $m ) : ?><li><a href="#f-<?php echo esc_attr( $f ); ?>"><?php echo esc_html( $m ); ?></a></li><?php endforeach; ?></ul>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<form class="dvs-request__form" method="post" action="<?php echo esc_url( add_query_arg( array() ) ); ?>#request" novalidate data-dvs-form>
			<input type="hidden" name="dvs_request" value="1">
			<input type="hidden" name="lang" value="<?php echo esc_attr( dvs_lang() ); ?>">
			<input type="hidden" name="dvs_token" value="<?php echo esc_attr( dvs_form_token() ); ?>">
			<div class="dvs-hp" aria-hidden="true"><label for="f-website">Website</label><input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off"></div>

			<fieldset class="dvs-step" data-step="1">
				<legend class="dvs-step__legend"><span class="dvs-step__count" data-step-count></span><?php dvs_e( 'step1' ); ?></legend>
				<div class="dvs-field">
					<label for="f-offer"><?php dvs_e( 'f_offer' ); ?> <span class="dvs-req"><?php dvs_e( 'f_required' ); ?></span></label>
					<select id="f-offer" name="offer" required<?php echo $inv( 'offer' ); // phpcs:ignore ?>>
						<option value=""><?php dvs_e( 'f_offer_choose' ); ?></option>
						<option value="unsure" <?php selected( $offer, 'unsure' ); ?>><?php dvs_e( 'f_offer_unsure' ); ?></option>
						<?php foreach ( $cats as $ck => $c ) : ?>
							<?php $items = dvs_items_by_category( $ck ); if ( ! $items ) { continue; } ?>
							<optgroup label="<?php echo esc_attr( dvs_t( $c['label'] ) ); ?>">
								<?php foreach ( $items as $item ) : ?>
									<option value="<?php echo esc_attr( $item['key'] ); ?>" <?php selected( $offer, $item['key'] ); ?> data-price="<?php echo esc_attr( dvs_item_price( $item )['text'] ); ?>"><?php echo esc_html( dvs_item_title( $item ) ); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
						<option value="general" <?php selected( $offer, 'general' ); ?>><?php dvs_e( 'f_offer_general' ); ?></option>
					</select>
					<?php echo $err( 'offer' ); // phpcs:ignore ?>
				</div>
				<div class="dvs-field" data-variant-field>
					<label for="f-variant"><?php dvs_e( 'f_variant' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></label>
					<select id="f-variant" name="variant">
						<option value=""><?php dvs_e( 'f_variant_any' ); ?></option>
						<?php foreach ( dvs_catalog() as $item ) : ?>
							<?php if ( count( $item['variants'] ) < 2 ) { continue; } ?>
							<optgroup label="<?php echo esc_attr( dvs_item_title( $item ) ); ?>" data-offer="<?php echo esc_attr( $item['key'] ); ?>">
								<?php foreach ( $item['variants'] as $v ) : ?>
									<?php $vv = $item['key'] . ':' . $v['key']; ?>
									<option value="<?php echo esc_attr( $vv ); ?>" <?php selected( $offer === $item['key'] && ( $var === $v['key'] || $var === $vv ) ); ?>><?php echo esc_html( dvs_variant_label( $v ) . ' — ' . dvs_variant_price_text( $v ) ); ?></option>
								<?php endforeach; ?>
							</optgroup>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="dvs-step__nav"><button type="button" class="dvs-btn dvs-btn--primary" data-next><?php dvs_e( 'f_next' ); ?></button></div>
			</fieldset>

			<fieldset class="dvs-step" data-step="2">
				<legend class="dvs-step__legend"><span class="dvs-step__count" data-step-count></span><?php dvs_e( 'step2' ); ?></legend>
				<div class="dvs-row">
					<div class="dvs-field">
						<label for="f-date"><?php dvs_e( 'f_date' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></label>
						<input type="date" id="f-date" name="date" min="<?php echo esc_attr( wp_date( 'Y-m-d' ) ); ?>" value="<?php echo $val( 'date' ); // phpcs:ignore ?>"<?php echo $inv( 'date', 'h-date' ); // phpcs:ignore ?>>
						<p class="dvs-field__help" id="h-date"><?php dvs_e( 'f_date_help' ); ?></p>
						<?php echo $err( 'date' ); // phpcs:ignore ?>
					</div>
					<div class="dvs-field">
						<label for="f-people"><?php dvs_e( 'f_people' ); ?> <span class="dvs-req"><?php dvs_e( 'f_required' ); ?></span></label>
						<input type="number" id="f-people" name="people" min="1" max="20" inputmode="numeric" required value="<?php echo $val( 'people', '1' ); // phpcs:ignore ?>"<?php echo $inv( 'people', 'h-people' ); // phpcs:ignore ?>>
						<p class="dvs-field__help" id="h-people"><?php dvs_e( 'f_people_help' ); ?></p>
						<?php echo $err( 'people' ); // phpcs:ignore ?>
					</div>
				</div>
				<fieldset class="dvs-field dvs-choice">
					<legend><?php dvs_e( 'f_flex' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></legend>
					<?php foreach ( dvs_form_flex() as $k => $s ) : ?>
						<label class="dvs-choice__item"><input type="radio" name="flex" value="<?php echo esc_attr( $k ); ?>" <?php checked( $posted['flex'] ?? 'days', $k ); ?>> <span><?php dvs_e( $s ); ?></span></label>
					<?php endforeach; ?>
				</fieldset>
				<div class="dvs-field">
					<label for="f-level"><?php dvs_e( 'f_level' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></label>
					<select id="f-level" name="level">
						<option value=""><?php dvs_e( 'f_not_set' ); ?></option>
						<?php foreach ( dvs_form_levels() as $k => $s ) : ?>
							<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $posted['level'] ?? '', $k ); ?>><?php dvs_e( $s ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="dvs-step__nav"><button type="button" class="dvs-btn dvs-btn--ghost" data-back><?php dvs_e( 'f_back' ); ?></button><button type="button" class="dvs-btn dvs-btn--primary" data-next><?php dvs_e( 'f_next' ); ?></button></div>
			</fieldset>

			<fieldset class="dvs-step" data-step="3">
				<legend class="dvs-step__legend"><span class="dvs-step__count" data-step-count></span><?php dvs_e( 'step3' ); ?></legend>
				<div class="dvs-row">
					<div class="dvs-field">
						<label for="f-name"><?php dvs_e( 'f_name' ); ?> <span class="dvs-req"><?php dvs_e( 'f_required' ); ?></span></label>
						<input type="text" id="f-name" name="name" autocomplete="name" required maxlength="120" value="<?php echo $val( 'name' ); // phpcs:ignore ?>"<?php echo $inv( 'name' ); // phpcs:ignore ?>>
						<?php echo $err( 'name' ); // phpcs:ignore ?>
					</div>
					<div class="dvs-field">
						<label for="f-email"><?php dvs_e( 'f_email' ); ?> <span class="dvs-req"><?php dvs_e( 'f_required' ); ?></span></label>
						<input type="email" id="f-email" name="email" autocomplete="email" required maxlength="160" value="<?php echo $val( 'email' ); // phpcs:ignore ?>"<?php echo $inv( 'email' ); // phpcs:ignore ?>>
						<?php echo $err( 'email' ); // phpcs:ignore ?>
					</div>
				</div>
				<div class="dvs-row">
					<div class="dvs-field">
						<label for="f-phone"><?php dvs_e( 'f_phone' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></label>
						<input type="tel" id="f-phone" name="phone" autocomplete="tel" maxlength="30" value="<?php echo $val( 'phone' ); // phpcs:ignore ?>"<?php echo $inv( 'phone' ); // phpcs:ignore ?>>
						<?php echo $err( 'phone' ); // phpcs:ignore ?>
					</div>
					<div class="dvs-field">
						<label for="f-reply_lang"><?php dvs_e( 'f_lang' ); ?></label>
						<select id="f-reply_lang" name="reply_lang">
							<?php foreach ( dvs_languages() as $code => $cfg ) : ?>
								<option value="<?php echo esc_attr( $code ); ?>" lang="<?php echo esc_attr( $cfg['hreflang'] ); ?>" <?php selected( $posted['reply_lang'] ?? dvs_lang(), $code ); ?>><?php echo esc_html( $cfg['name'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="dvs-field">
					<label for="f-message"><?php dvs_e( 'f_message' ); ?> <span class="dvs-opt"><?php dvs_e( 'f_optional' ); ?></span></label>
					<textarea id="f-message" name="message" rows="5" maxlength="2000"<?php echo $inv( 'message', 'h-message' ); // phpcs:ignore ?>><?php echo esc_textarea( (string) ( $posted['message'] ?? '' ) ); ?></textarea>
					<p class="dvs-field__help" id="h-message"><?php dvs_e( 'f_message_help' ); ?></p>
					<?php echo $err( 'message' ); // phpcs:ignore ?>
				</div>
				<div class="dvs-step__nav"><button type="button" class="dvs-btn dvs-btn--ghost" data-back><?php dvs_e( 'f_back' ); ?></button><button type="button" class="dvs-btn dvs-btn--primary" data-next><?php dvs_e( 'f_next' ); ?></button></div>
			</fieldset>

			<fieldset class="dvs-step" data-step="4">
				<legend class="dvs-step__legend"><span class="dvs-step__count" data-step-count></span><?php dvs_e( 'step4' ); ?></legend>
				<div class="dvs-summary" data-dvs-summary hidden>
					<h3 class="dvs-summary__title"><?php dvs_e( 'f_summary' ); ?></h3>
					<dl class="dvs-summary__list" data-dvs-summary-list></dl>
				</div>
				<p class="dvs-request__note"><?php dvs_e( 'f_not_booking' ); ?></p>
				<div class="dvs-field dvs-check">
					<input type="checkbox" id="f-privacy" name="privacy" value="1" required <?php checked( ! empty( $posted['privacy'] ) ); ?><?php echo $inv( 'privacy' ); // phpcs:ignore ?>>
					<label for="f-privacy"><?php dvs_e( 'f_privacy' ); ?> <a href="<?php echo esc_url( dvs_url( 'page:privacy' ) ); ?>" target="_blank" rel="noopener"><?php dvs_e( 'f_privacy_link' ); ?><span class="visually-hidden"> <?php dvs_e( 'new_tab' ); ?></span></a></label>
					<?php echo $err( 'privacy' ); // phpcs:ignore ?>
				</div>
				<div class="dvs-step__nav"><button type="button" class="dvs-btn dvs-btn--ghost" data-back><?php dvs_e( 'f_back' ); ?></button><button type="submit" class="dvs-btn dvs-btn--primary" data-submit><?php dvs_e( 'f_submit' ); ?></button></div>
			</fieldset>
		</form>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/** Textos para o JavaScript do formulário. */
function dvs_form_js_strings(): array {
	$keys = array( 'step_of', 'e_required', 'e_email', 'e_date', 'e_people', 'e_phone', 'e_privacy', 'e_offer', 'e_spam', 'f_error_summary', 'f_sending', 'f_submit', 'f_not_set', 'f_new_request', 'f_edit', 'step1', 'step2', 'step3', 'step4' );
	$out  = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = dvs_t( $k );
	}
	return $out;
}

/*
 * O WordPress também lê variáveis públicas de consulta do POST (ex.: «name», «m», «p»).
 * No envio sem JavaScript, os campos do formulário não podem alterar a página pedida.
 */
add_filter(
	'request',
	static function ( array $vars ) {
		if ( empty( $_POST['dvs_request'] ) ) { // phpcs:ignore
			return $vars;
		}
		foreach ( $vars as $k => $v ) {
			if ( isset( $_POST[ $k ] ) && ! isset( $_GET[ $k ] ) ) { // phpcs:ignore
				unset( $vars[ $k ] );
			}
		}
		return $vars;
	},
	1
);
