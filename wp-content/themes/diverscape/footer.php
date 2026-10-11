<?php
/**
 * Rodapé: navegação, contactos, idiomas, informação legal confirmada.
 */

defined( 'ABSPATH' ) || exit;
$ready = dvs_theme_ready();
?>
</main>

<footer class="site-footer">
	<div class="site-footer__wave" aria-hidden="true"><svg viewBox="0 0 1440 60" preserveAspectRatio="none"><path d="M0 30c180 22 360 22 540 0s360-22 540 0 270 16 360 8v22H0z"/></svg></div>
	<?php if ( $ready ) : ?>
	<div class="dvs-wrap site-footer__grid">
		<div class="site-footer__brand">
			<a class="site-footer__logo" href="<?php echo esc_url( dvs_home_url() ); ?>"><?php echo dvs_logo( 'site-footer__logo-img', false ); // phpcs:ignore ?><span class="visually-hidden"><?php dvs_e( 'home' ); ?></span></a>
			<p class="site-footer__tagline"><?php dvs_e( 'footer_tagline' ); ?></p>
			<a class="dvs-btn dvs-btn--primary" href="<?php echo esc_url( dvs_request_url() ); ?>"><?php dvs_e( 'cta_plan' ); ?></a>
		</div>
		<nav class="site-footer__col" aria-labelledby="footer-explore">
			<h2 class="site-footer__title" id="footer-explore"><?php dvs_e( 'footer_explore' ); ?></h2>
			<?php dvs_theme_menu( 'footer', array( 'menu_class' => 'site-footer__menu', 'depth' => 1 ) ); ?>
		</nav>
		<div class="site-footer__col">
			<h2 class="site-footer__title"><?php dvs_e( 'footer_contact' ); ?></h2>
			<ul class="site-footer__contact" role="list">
				<li><?php echo dvs_icon( 'mail', 18 ); // phpcs:ignore ?><a href="mailto:<?php echo esc_attr( dvs_business( 'email' ) ); ?>"><?php echo esc_html( dvs_business( 'email' ) ); ?></a></li>
				<li><?php echo dvs_icon( 'phone', 18 ); // phpcs:ignore ?><span><a href="<?php echo esc_attr( dvs_phone_href() ); ?>"><?php echo esc_html( dvs_business( 'phone' ) ); ?></a><small><?php dvs_e( 'call_note' ); ?></small></span></li>
				<li><?php echo dvs_icon( 'pin', 18 ); // phpcs:ignore ?><span><?php echo esc_html( dvs_business( 'street' ) ); ?><br><?php echo esc_html( dvs_business( 'postcode' ) . ' ' . dvs_business( 'city' ) . ', ' . dvs_business( 'country' ) ); ?><br><a href="<?php echo esc_url( dvs_directions_url() ); ?>" target="_blank" rel="noopener"><?php dvs_e( 'directions' ); ?><span class="visually-hidden"> — <?php dvs_e( 'directions_note' ); ?></span> ↗</a></span></li>
			</ul>
			<p class="site-footer__social">
				<a href="<?php echo esc_url( dvs_business( 'facebook' ) ); ?>" target="_blank" rel="noopener">Facebook<span class="visually-hidden"> <?php dvs_e( 'new_tab' ); ?></span></a>
				<a href="<?php echo esc_url( dvs_business( 'tripadvisor' ) ); ?>" target="_blank" rel="noopener">Tripadvisor<span class="visually-hidden"> <?php dvs_e( 'new_tab' ); ?></span></a>
			</p>
		</div>
		<div class="site-footer__col">
			<h2 class="site-footer__title" id="footer-lang"><?php dvs_e( 'language' ); ?></h2>
			<ul class="site-footer__langs" aria-labelledby="footer-lang">
				<?php foreach ( dvs_language_links() as $code => $l ) : ?>
					<li><a href="<?php echo esc_url( $l['url'] ); ?>" lang="<?php echo esc_attr( $l['hreflang'] ); ?>" hreflang="<?php echo esc_attr( $l['hreflang'] ); ?>"<?php echo $l['current'] ? ' aria-current="true"' : ''; ?>><?php echo esc_html( $l['name'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
			<h2 class="site-footer__title site-footer__title--spaced"><?php dvs_e( 'footer_info' ); ?></h2>
			<?php dvs_theme_menu( 'legal', array( 'menu_class' => 'site-footer__menu', 'depth' => 1 ) ); ?>
		</div>
	</div>
	<div class="dvs-wrap site-footer__bottom">
		<p><?php echo esc_html( dvs_t( 'copyright', wp_date( 'Y' ), dvs_business( 'name' ) ) ); ?></p>
		<p><?php echo esc_html( dvs_t( 'legal_ref', dvs_business( 'legal_ref' ) ) ); ?></p>
	</div>
	<?php endif; ?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
