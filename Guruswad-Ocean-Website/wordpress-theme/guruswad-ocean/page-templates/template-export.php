<?php
/**
 * Template Name: Import & Export
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Import & Export', 'guruswad' ), 'title' => __( 'Built for borders, not just <em>shelves</em>.', 'guruswad' ), 'lead' => __( 'Export-grade millets and millet products, private label and custom packaging, plus import sourcing — for buyers in every market.', 'guruswad' ) ) );
?>

<section class="section">
	<div class="container">
		<div class="cards-3">
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'grain' ); ?></span><h3><?php esc_html_e( 'Export catalogue', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Millet grains, flours, snacks and processed foods with full specifications.', 'guruswad' ); ?></p>
				<?php if ( gor_opt( 'catalogue_pdf' ) ) : ?><a class="link-arrow" href="<?php echo esc_url( gor_opt( 'catalogue_pdf' ) ); ?>" download><?php gor_the_icon( 'download' ); ?> <?php esc_html_e( 'Download PDF', 'guruswad' ); ?></a><?php else : ?><a class="link-arrow" href="<?php echo esc_url( gor_shop_url() ); ?>"><?php esc_html_e( 'Browse products', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a><?php endif; ?></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'doc' ); ?></span><h3><?php esc_html_e( 'Private label & packaging', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Your brand, our millets — retail packs, bulk bags and custom formats.', 'guruswad' ); ?></p><a class="link-arrow" href="<?php echo esc_url( gor_enquiry_url( 'partner' ) ); ?>"><?php esc_html_e( 'Discuss', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'ship' ); ?></span><h3><?php esc_html_e( 'Import enquiries', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Sourcing products and inputs into India through trusted partners.', 'guruswad' ); ?></p><a class="link-arrow" href="<?php echo esc_url( gor_enquiry_url( 'import' ) ); ?>"><?php esc_html_e( 'Enquire', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></div>
		</div>
	</div>
</section>

<?php
get_template_part(
	'template-parts/trade-map',
	null,
	array(
		'eyebrow' => __( 'Markets', 'guruswad' ),
		'title_a' => __( 'From Maharashtra', 'guruswad' ),
		'title_b' => __( 'to the world', 'guruswad' ),
	)
);
?>

<?php if ( get_the_content() ) : ?>
<section class="section"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<section class="section section--cream2"><div class="container"><?php get_template_part( 'template-parts/rfq-form' ); ?></div></section>

<?php
get_footer();
