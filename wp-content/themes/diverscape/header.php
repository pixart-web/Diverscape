<?php
/**
 * Cabeçalho: logótipo original numa aba clara, menu com submenus acessíveis, seletor de idioma
 * (nomes escritos, leva à tradução da página atual) e chamada para ação.
 */

defined( 'ABSPATH' ) || exit;
$ready = dvs_theme_ready();
$langs = $ready ? dvs_language_links() : array();
$cur   = $ready ? dvs_lang() : 'en';
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>document.documentElement.classList.replace('no-js','js');</script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php echo esc_html( $ready ? dvs_t( 'skip' ) : 'Skip to content' ); ?></a>

<header class="site-header" data-header>
	<div class="site-header__bar dvs-wrap">
		<a class="site-brand" href="<?php echo esc_url( $ready ? dvs_home_url() : home_url( '/' ) ); ?>" rel="home">
			<?php echo dvs_logo(); // phpcs:ignore ?>
			<span class="visually-hidden"><?php echo esc_html( $ready ? dvs_t( 'home' ) : 'Home' ); ?></span>
		</a>

		<nav class="site-nav" id="site-nav" aria-label="<?php echo esc_attr( $ready ? dvs_t( 'nav_main' ) : 'Main' ); ?>" data-nav>
			<?php dvs_theme_menu( 'primary', array( 'menu_class' => 'site-nav__menu', 'menu_id' => 'menu-primary' ) ); ?>
			<div class="site-nav__extra">
				<?php if ( $ready ) : ?>
					<a class="dvs-btn dvs-btn--primary site-nav__cta" href="<?php echo esc_url( dvs_request_url() ); ?>"><?php dvs_e( 'cta_plan' ); ?></a>
					<a class="site-nav__phone" href="<?php echo esc_attr( dvs_phone_href() ); ?>"><?php echo dvs_icon( 'phone' ); // phpcs:ignore ?> <?php echo esc_html( dvs_business( 'phone' ) ); ?><small><?php dvs_e( 'call_note' ); ?></small></a>
					<p class="site-nav__lang-title" id="nav-lang-title"><?php dvs_e( 'language' ); ?></p>
					<ul class="site-nav__langs" aria-labelledby="nav-lang-title">
						<?php foreach ( $langs as $code => $l ) : ?>
							<li><a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['hreflang'] ); ?>" hreflang="<?php echo esc_attr( $l['hreflang'] ); ?>"<?php echo $l['current'] ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $l['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</nav>

		<div class="site-header__tools">
			<?php if ( $ready ) : ?>
				<div class="lang-switch" data-lang-switch>
					<button type="button" class="lang-switch__btn" aria-expanded="false" aria-controls="lang-menu">
						<?php echo dvs_icon( 'globe', 18 ); // phpcs:ignore ?>
						<span aria-hidden="true"><?php echo esc_html( $langs[ $cur ]['short'] ?? 'EN' ); ?></span>
						<span class="visually-hidden"><?php echo esc_html( dvs_t( 'lang_switch_label' ) . ': ' . ( $langs[ $cur ]['name'] ?? '' ) ); ?></span>
					</button>
					<ul class="lang-switch__menu" id="lang-menu" hidden>
						<?php foreach ( $langs as $code => $l ) : ?>
							<li><a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['hreflang'] ); ?>" hreflang="<?php echo esc_attr( $l['hreflang'] ); ?>"<?php echo $l['current'] ? ' aria-current="true"' : ''; ?>><span class="lang-switch__code" aria-hidden="true"><?php echo esc_html( $l['short'] ); ?></span> <?php echo esc_html( $l['name'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<a class="dvs-btn dvs-btn--primary site-header__cta" href="<?php echo esc_url( dvs_request_url() ); ?>"><?php dvs_e( 'cta_plan' ); ?></a>
				<a class="site-header__call" href="<?php echo esc_attr( dvs_phone_href() ); ?>"><?php echo dvs_icon( 'phone' ); // phpcs:ignore ?><span class="visually-hidden"><?php echo esc_html( dvs_t( 'call_us' ) . ' ' . dvs_business( 'phone' ) . ' — ' . dvs_t( 'call_note' ) ); ?></span></a>
				<button type="button" class="site-header__menu" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
					<span class="site-header__menu-icon" aria-hidden="true"><span></span><span></span><span></span></span>
					<span class="site-header__menu-label" data-menu-label><?php dvs_e( 'menu' ); ?></span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
