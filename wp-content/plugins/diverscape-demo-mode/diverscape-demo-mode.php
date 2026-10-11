<?php
/**
 * Plugin Name:       DiversCape Demo Mode
 * Description:       Mantém a proposta DiversCape em modo de demonstração: o pedido de disponibilidade é validado mas nunca enviado nem guardado, nenhum email sai do site, a indexação é bloqueada e uma faixa indica que é uma demonstração. Opcionalmente protege o acesso com um código (constante ou variável DVS_DEMO_ACCESS_CODE).
 * Version:           1.0.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Requires Plugins:  diverscape-core
 * Author:            Pixart
 * License:           GPL-2.0-or-later
 *
 * Desativar apenas com instrução explícita para o lançamento, depois de configurar o destinatário real
 * e testar com um endereço de teste autorizado.
 */

defined( 'ABSPATH' ) || exit;

define( 'DVS_DEMO_MODE', true );

final class DVS_Demo_Mode {

	public static function init(): void {
		// 1. Formulário: sempre em demonstração.
		add_filter( 'dvs_form_mode', static fn() => 'demo', 999 );

		// 2. Nenhum email sai do site.
		add_filter( 'pre_wp_mail', array( __CLASS__, 'block_mail' ), 999, 2 );

		// 3. Indexação bloqueada.
		add_filter( 'wp_robots', static fn( $r ) => array_merge( $r, array( 'noindex' => true, 'nofollow' => true ) ), 999 );
		add_action( 'send_headers', static fn() => header( 'X-Robots-Tag: noindex, nofollow', true ) );
		add_filter( 'robots_txt', static fn() => "User-agent: *\nDisallow: /\n", 999 );
		add_filter( 'pre_option_blog_public', static fn() => '0' );

		// 4. Comentários e pingbacks desligados (não fazem parte da proposta).
		add_filter( 'comments_open', '__return_false', 999 );
		add_filter( 'pings_open', '__return_false', 999 );
		add_filter( 'xmlrpc_enabled', '__return_false' );

		// 5. Indicação visível.
		add_action( 'wp_body_open', array( __CLASS__, 'badge' ), 1 );
		add_filter( 'body_class', static fn( $c ) => array_merge( $c, array( 'dvs-demo' ) ) );
		add_action( 'admin_notices', array( __CLASS__, 'admin_notice' ) );

		// 6. Acesso protegido opcional (staging).
		add_action( 'template_redirect', array( __CLASS__, 'gate' ), 0 );
	}

	public static function block_mail( $return, array $atts ) {
		if ( function_exists( 'error_log' ) ) {
			error_log( '[DiversCape Demo Mode] Email bloqueado: ' . wp_strip_all_tags( (string) ( $atts['subject'] ?? '' ) ) ); // phpcs:ignore
		}
		return false;
	}

	public static function badge(): void {
		if ( ! function_exists( 'dvs_t' ) ) {
			return;
		}
		echo '<p class="dvs-demo-badge" role="note"><span class="dvs-demo-badge__long">' . esc_html( dvs_t( 'demo_badge' ) ) . '</span><span class="dvs-demo-badge__short">' . esc_html( dvs_t( 'demo_badge_short' ) ) . '</span></p>';
	}

	public static function admin_notice(): void {
		echo '<div class="notice notice-info"><p><strong>Modo demonstração ativo.</strong> O pedido de disponibilidade não envia nem guarda dados, nenhum email sai do site e a indexação está bloqueada. Desativar o plugin «DiversCape Demo Mode» só no lançamento autorizado.</p></div>';
	}

	private static function access_code(): string {
		if ( defined( 'DVS_DEMO_ACCESS_CODE' ) ) {
			return (string) DVS_DEMO_ACCESS_CODE;
		}
		return (string) getenv( 'DVS_DEMO_ACCESS_CODE' );
	}

	/** Código de acesso simples para a apresentação em staging (desligado se não houver código). */
	public static function gate(): void {
		$code = self::access_code();
		if ( '' === $code || is_user_logged_in() ) {
			return;
		}
		$cookie = 'dvs_demo_access';
		$token  = hash_hmac( 'sha256', $code, wp_salt( 'auth' ) );
		if ( isset( $_COOKIE[ $cookie ] ) && hash_equals( $token, (string) $_COOKIE[ $cookie ] ) ) { // phpcs:ignore
			return;
		}
		$error = false;
		if ( isset( $_POST['dvs_demo_code'] ) ) { // phpcs:ignore
			if ( hash_equals( $code, (string) wp_unslash( $_POST['dvs_demo_code'] ) ) ) { // phpcs:ignore
				setcookie( $cookie, $token, array( 'expires' => time() + 7 * DAY_IN_SECONDS, 'path' => '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) );
				wp_safe_redirect( esc_url_raw( add_query_arg( array() ) ) );
				exit;
			}
			$error = true;
		}
		status_header( 401 );
		nocache_headers();
		$lang  = function_exists( 'dvs_lang' ) ? dvs_lang() : 'en';
		$texts = array(
			'en' => array( 'Private preview', 'Enter the access code to view the DiversCape website proposal.', 'Access code', 'View proposal', 'Incorrect code.' ),
			'pt' => array( 'Pré-visualização privada', 'Introduza o código de acesso para ver a proposta do site DiversCape.', 'Código de acesso', 'Ver proposta', 'Código incorreto.' ),
			'de' => array( 'Private Vorschau', 'Geben Sie den Zugangscode ein, um den Website-Vorschlag für DiversCape zu sehen.', 'Zugangscode', 'Vorschlag ansehen', 'Falscher Code.' ),
			'es' => array( 'Vista previa privada', 'Introduzca el código de acceso para ver la propuesta del sitio de DiversCape.', 'Código de acceso', 'Ver propuesta', 'Código incorrecto.' ),
		);
		$t = $texts[ $lang ] ?? $texts['en'];
		?><!doctype html>
<html lang="<?php echo esc_attr( $lang ); ?>"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title><?php echo esc_html( $t[0] ); ?> · DiversCape</title>
<style>body{margin:0;min-height:100vh;display:grid;place-items:center;background:#062B3A;color:#F5FAF9;font:16px/1.5 system-ui,sans-serif}form{width:min(360px,calc(100% - 32px));background:#0b3b4f;padding:32px;border-radius:16px}h1{margin:0 0 8px;font-size:1.5rem}label{display:block;margin:16px 0 6px;font-weight:600}input{width:100%;box-sizing:border-box;padding:12px;border-radius:8px;border:1px solid #9cc;font-size:1rem}button{margin-top:16px;width:100%;padding:12px;border:0;border-radius:999px;background:#20B8CF;color:#062B3A;font-weight:700;font-size:1rem;cursor:pointer}button:focus-visible,input:focus-visible{outline:3px solid #fff;outline-offset:2px}.err{color:#ffd4d4}</style></head>
<body><form method="post"><h1><?php echo esc_html( $t[0] ); ?></h1><p><?php echo esc_html( $t[1] ); ?></p>
<?php if ( $error ) : ?><p class="err" role="alert"><?php echo esc_html( $t[4] ); ?></p><?php endif; ?>
<label for="dvs_demo_code"><?php echo esc_html( $t[2] ); ?></label><input type="password" id="dvs_demo_code" name="dvs_demo_code" required autocomplete="current-password"><button><?php echo esc_html( $t[3] ); ?></button></form></body></html>
		<?php
		exit;
	}
}

DVS_Demo_Mode::init();
