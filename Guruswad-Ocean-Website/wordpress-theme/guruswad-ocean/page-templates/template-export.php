<?php
/**
 * Template Name: Import & Export
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Import & Export', 'guruswad' ), 'title' => __( 'Built for borders, not just <em>shelves</em>.', 'guruswad' ), 'lead' => __( 'Export-grade millets and millet products, private label and custom packaging, plus import sourcing — for buyers in every market.', 'guruswad' ) , 'variant' => 'trade') );
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

<!-- MARKET STATUS — client letter §6.
     Target and Potential are the only statuses in use. An "Active Market"
     status exists in inc/markets.php and is deliberately unused: a market
     moves to it when trade has actually commenced, and not before. -->
<section class="section" id="markets-note">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Market status', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Where we are going, stated as where we are going.', 'guruswad' ); ?></h2>
		</div>
		<?php
		gor_disclosure(
			'globe',
			'<p><strong>' . esc_html__( 'We do not present any country as an active export market.', 'guruswad' ) . '</strong> '
			. esc_html__( 'The territories on the map are described as Target Markets — the priority, where buyer engagement is furthest along — or as Potential Markets, which are under exploration.', 'guruswad' ) . '</p>'
			. '<p>' . esc_html__( 'A market will be shown as active only once commercial trade has commenced there, and the change is a setting the client controls. Enquiries from any territory are welcome in the meantime; our export capability does not depend on having shipped to your country before.', 'guruswad' ) . '</p>'
		);
		?>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
<section class="section"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<section class="section section--cream2"><div class="container"><?php get_template_part( 'template-parts/rfq-form' ); ?></div></section>

<?php
get_footer();
