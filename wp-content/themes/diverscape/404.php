<?php
/**
 * Página não encontrada, localizada pelo prefixo do endereço (/pt/, /de/, /sp/).
 */

defined( 'ABSPATH' ) || exit;
get_header();
$ready = dvs_theme_ready();
?>
<header class="dvs-hero dvs-hero--plain dvs-hero--404">
	<div class="dvs-hero__inner dvs-wrap">
		<p class="dvs-eyebrow dvs-hero__eyebrow">404</p>
		<h1 class="dvs-hero__title"><?php echo esc_html( $ready ? dvs_t( 'nf_title' ) : 'Page not found' ); ?></h1>
		<?php if ( $ready ) : ?><p class="dvs-hero__lead"><?php dvs_e( 'nf_text' ); ?></p><?php endif; ?>
	</div>
	<div class="dvs-404__bubbles" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span></div>
</header>
<?php if ( $ready ) : ?>
<section class="dvs-section">
	<div class="dvs-wrap">
		<ul class="dvs-404__links" role="list">
			<li><a class="dvs-404__link" href="<?php echo esc_url( dvs_home_url() ); ?>"><?php dvs_e( 'nf_home' ); ?> <span aria-hidden="true">→</span></a></li>
			<li><a class="dvs-404__link" href="<?php echo esc_url( dvs_url( 'page:experiences' ) ); ?>"><?php dvs_e( 'cat_experiences' ); ?> <span aria-hidden="true">→</span></a></li>
			<li><a class="dvs-404__link" href="<?php echo esc_url( dvs_url( 'page:courses' ) ); ?>"><?php dvs_e( 'back_catalog' ); ?> <span aria-hidden="true">→</span></a></li>
			<li><a class="dvs-404__link" href="<?php echo esc_url( dvs_url( 'page:certified-divers' ) ); ?>"><?php dvs_e( 'cat_certified_short' ); ?> <span aria-hidden="true">→</span></a></li>
			<li><a class="dvs-404__link" href="<?php echo esc_url( dvs_request_url() ); ?>"><?php dvs_e( 'cta_plan' ); ?> <span aria-hidden="true">→</span></a></li>
		</ul>
	</div>
</section>
<?php endif; ?>
<?php
get_footer();
