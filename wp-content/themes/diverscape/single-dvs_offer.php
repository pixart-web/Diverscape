<?php
/**
 * Página de curso/experiência: texto da tradução + dados comerciais do catálogo central.
 * Só mostra factos confirmados; variantes com pedido pré-selecionado.
 */

defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$oid   = get_the_ID();
	$item  = dvs_offer_item( $oid );
	if ( ! $item ) {
		the_content();
		continue;
	}
	$facts    = dvs_item_facts( $item );
	$price    = dvs_item_price( $item );
	$aud      = (string) get_post_meta( $oid, '_dvs_audience', true );
	$faq      = dvs_offer_faq( $oid );
	$cat      = dvs_categories()[ $item['category'] ] ?? null;
	$image    = $item['image_id'] ?: $item['image'];
	$training = in_array( $item['category'], array( 'courses', 'specialties', 'first-aid', 'technical' ), true );
	$multi    = count( $item['variants'] ) > 1;
	?>
	<section class="dvs-hero dvs-hero--page dvs-hero--offer">
		<div class="dvs-hero__media" data-parallax><?php echo dvs_img( $image, 'full', array( 'class' => 'dvs-hero__img', 'loading' => 'eager', 'sizes' => '100vw' ) ); // phpcs:ignore ?></div>
		<div class="dvs-hero__shade" aria-hidden="true"></div>
		<div class="dvs-hero__inner dvs-wrap">
			<?php echo dvs_breadcrumbs_html(); // phpcs:ignore ?>
			<?php if ( $cat ) : ?><p class="dvs-eyebrow dvs-hero__eyebrow"><?php echo esc_html( dvs_t( $cat['label'] ) ); ?></p><?php endif; ?>
			<h1 class="dvs-hero__title dvs-hero__title--offer"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?><p class="dvs-hero__lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
			<div class="dvs-hero__actions">
				<?php echo dvs_btn( 'on_request' === $price['kind'] ? dvs_t( 'request_info' ) : dvs_t( 'request_this' ), dvs_request_url( $item ), 'primary' ); // phpcs:ignore ?>
				<?php if ( $multi ) : ?><?php echo dvs_btn( dvs_t( 'options' ), '#options', 'light' ); // phpcs:ignore ?><?php endif; ?>
			</div>
		</div>
	</section>

	<section class="dvs-section dvs-offer">
		<div class="dvs-wrap dvs-offer__grid">
			<div class="dvs-offer__main">
				<?php if ( $aud ) : ?>
					<p class="dvs-offer__aud"><span class="dvs-offer__aud-label"><?php dvs_e( 'fact_audience' ); ?></span> <?php echo esc_html( $aud ); ?></p>
				<?php endif; ?>
				<div class="dvs-offer__body dvs-prose-inline"><?php the_content(); ?></div>
				<?php if ( $training ) : ?>
					<p class="dvs-offer__note"><?php echo dvs_icon( 'check', 18 ); // phpcs:ignore ?> <?php dvs_e( 'prereq_note' ); ?></p>
				<?php endif; ?>
				<?php if ( 'boat-dives' === $item['key'] ) : ?>
					<p><a class="dvs-link-arrow" href="<?php echo esc_url( dvs_url( 'page:certified-divers' ) ); ?>#packages"><?php dvs_e( 'pkg_title' ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a></p>
				<?php endif; ?>
			</div>
			<aside class="dvs-offer__aside" aria-labelledby="facts-title">
				<div class="dvs-facts-card">
					<h2 class="dvs-facts-card__title" id="facts-title"><?php dvs_e( 'facts_title' ); ?></h2>
					<dl class="dvs-facts">
						<?php foreach ( $facts as $k => $f ) : ?>
							<div class="dvs-facts__row dvs-facts__row--<?php echo esc_attr( $k ); ?>"><dt><?php echo esc_html( $f[0] ); ?></dt><dd><?php echo esc_html( $f[1] ); ?></dd></div>
						<?php endforeach; ?>
					</dl>
					<?php echo dvs_btn( 'on_request' === $price['kind'] ? dvs_t( 'request_info' ) : dvs_t( 'request_this' ), dvs_request_url( $item ), 'primary', array( 'data-full' => '1' ) ); // phpcs:ignore ?>
					<?php echo dvs_price_notice( array( $item ) ); // phpcs:ignore ?>
				</div>
			</aside>
		</div>
	</section>

	<?php if ( $multi ) : ?>
	<section class="dvs-section dvs-options dvs-tone--sand" id="options">
		<div class="dvs-wrap">
			<h2 class="dvs-section__title"><?php dvs_e( 'options' ); ?></h2>
			<ul class="dvs-options__list" role="list">
				<?php foreach ( $item['variants'] as $v ) : ?>
					<?php $vd = dvs_format_duration( $v['duration'] ?? null ) ?: dvs_item_duration( $item ); ?>
					<li class="dvs-option">
						<div class="dvs-option__main">
							<h3 class="dvs-option__title"><?php echo esc_html( dvs_variant_label( $v ) ?: dvs_t( 'option_single' ) ); ?></h3>
							<?php if ( $vd ) : ?><p class="dvs-option__dur"><?php echo esc_html( dvs_t( 'fact_duration' ) . ': ' . $vd ); ?></p><?php endif; ?>
						</div>
						<p class="dvs-option__price<?php echo dvs_variant_has_price( $v ) ? '' : ' is-consult'; ?>"><?php echo esc_html( dvs_variant_price_text( $v ) ); ?></p>
						<a class="dvs-btn dvs-btn--secondary dvs-option__cta" href="<?php echo esc_url( dvs_request_url( $item, $v['key'] ) ); ?>" aria-label="<?php echo esc_attr( dvs_t( 'request_option', get_the_title() . ( dvs_variant_label( $v ) ? ' — ' . dvs_variant_label( $v ) : '' ) ) ); ?>"><?php dvs_e( 'request_this' ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php echo dvs_price_notice( array( $item ) ); // phpcs:ignore ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="dvs-section dvs-offer-help">
		<div class="dvs-wrap dvs-offer-help__grid">
			<?php if ( $faq ) : ?>
				<div class="dvs-offer-help__faq">
					<h2 class="dvs-section__title dvs-section__title--sm"><?php dvs_e( 'offer_questions' ); ?></h2>
					<div class="dvs-faq__list">
						<?php foreach ( $faq as $qa ) : ?>
							<details class="dvs-faq__item"><summary class="dvs-faq__q"><?php echo esc_html( $qa[0] ); ?></summary><div class="dvs-faq__a"><p><?php echo esc_html( $qa[1] ); ?></p></div></details>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="dvs-offer-help__box">
				<h2 class="dvs-offer-help__title"><?php dvs_e( 'offer_help_title' ); ?></h2>
				<p><?php dvs_e( 'offer_help_text' ); ?></p>
				<p class="dvs-offer-help__actions">
					<?php echo dvs_btn( dvs_t( 'ask_question' ), dvs_request_url( $item ), 'secondary' ); // phpcs:ignore ?>
					<a class="dvs-link-arrow" href="<?php echo esc_attr( dvs_phone_href() ); ?>"><?php echo esc_html( dvs_business( 'phone' ) ); ?></a>
				</p>
				<p class="dvs-offer-help__small"><?php dvs_e( 'call_note' ); ?></p>
			</div>
		</div>
	</section>

	<?php
	$related = array_filter( dvs_items_by_category( $item['category'] ), static fn( $i ) => $i['key'] !== $item['key'] && dvs_offer_id( $i['key'] ) );
	$related = array_slice( $related, 0, 3 );
	if ( $related ) :
		?>
		<section class="dvs-section dvs-related dvs-tone--light">
			<div class="dvs-wrap">
				<header class="dvs-section__head dvs-section__head--split">
					<h2 class="dvs-section__title dvs-section__title--sm"><?php dvs_e( 'related' ); ?></h2>
					<a class="dvs-link-arrow" href="<?php echo esc_url( dvs_url( 'page:courses' ) ); ?>"><?php dvs_e( 'back_catalog' ); ?> <span class="dvs-arrow" aria-hidden="true">→</span></a>
				</header>
				<div class="dvs-cards dvs-cards--3">
					<?php foreach ( $related as $r ) : ?>
						<?php echo dvs_offer_card( $r ); // phpcs:ignore ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
endwhile;
get_footer();
