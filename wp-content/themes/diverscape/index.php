<?php
/**
 * Modelo de recurso. O site usa páginas e ofertas; este ficheiro existe por exigência do WordPress.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<header class="dvs-hero dvs-hero--plain"><div class="dvs-hero__inner dvs-wrap"><h1 class="dvs-hero__title"><?php echo esc_html( wp_get_document_title() ); ?></h1></div></header>
<section class="dvs-section"><div class="dvs-wrap dvs-prose">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?></article>
		<?php endwhile; ?>
	<?php endif; ?>
</div></section>
<?php
get_footer();
