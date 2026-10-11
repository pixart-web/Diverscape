<?php
/**
 * Páginas: conteúdo em blocos. Se a página não começar por um destaque, mostra um cabeçalho simples.
 */

defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	$blocks = parse_blocks( get_the_content() );
	$first  = '';
	foreach ( $blocks as $b ) {
		if ( ! empty( $b['blockName'] ) ) {
			$first = $b['blockName'];
			break;
		}
	}
	if ( 'diverscape/hero' !== $first ) :
		?>
		<header class="dvs-hero dvs-hero--plain">
			<div class="dvs-hero__inner dvs-wrap">
				<?php echo function_exists( 'dvs_breadcrumbs_html' ) ? dvs_breadcrumbs_html() : ''; // phpcs:ignore ?>
				<h1 class="dvs-hero__title"><?php the_title(); ?></h1>
			</div>
		</header>
		<?php
	endif;
	the_content();
endwhile;
get_footer();
