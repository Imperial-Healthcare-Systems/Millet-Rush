<?php
/**
 * Template Name: Investment & Partnership
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Investment & Partnership', 'guruswad' ), 'title' => __( 'The future grows in <em>millets</em>.', 'guruswad' ), 'lead' => __( 'An integrated millet company with a farmer base, processing capability and its own brands — open to investors and strategic partners.', 'guruswad' ) , 'variant' => 'invest') );
?>

<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'The opportunity', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Why millets. Why now. Why us.', 'guruswad' ); ?></h2></div>
		<div class="cards-3">
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'chart' ); ?></span><h3><?php esc_html_e( 'A growing global category', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Independent research firms forecast steady growth in the global millet market as demand for healthy, climate-resilient grains rises.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'farmer' ); ?></span><h3><?php esc_html_e( 'Secure sourcing', 'guruswad' ); ?></h3><p><?php printf( esc_html__( 'A Farmer Producer Company with %s farmers gives traceable, reliable supply.', 'guruswad' ), esc_html( number_format_i18n( (int) gor_opt( 'stat_farmers' ) ) ) ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'flask' ); ?></span><h3><?php esc_html_e( 'Science & brands', 'guruswad' ); ?></h3><p><?php esc_html_e( 'ICAR-IIMR technology collaboration and a consumer brand, MilletRush, already in market.', 'guruswad' ); ?></p></div>
		</div>
	</div>
</section>

<section class="section section--dark">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'Partnership models', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Ways to grow together.', 'guruswad' ); ?></h2></div>
		<div class="cards-3">
			<?php foreach ( array( array( 'shop', __( 'Distribution', 'guruswad' ) ), array( 'factory', __( 'Co-manufacturing', 'guruswad' ) ), array( 'doc', __( 'Private label', 'guruswad' ) ), array( 'flask', __( 'Technology', 'guruswad' ) ), array( 'ship', __( 'Export partnerships', 'guruswad' ) ), array( 'handshake', __( 'Strategic investment', 'guruswad' ) ) ) as $m ) : ?>
				<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( $m[0] ); ?></span><h3><?php echo esc_html( $m[1] ); ?></h3></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
<section class="section"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<section class="section section--cream2"><div class="container"><?php get_template_part( 'template-parts/enquiry-form', null, array( 'type' => 'investor' ) ); ?></div></section>

<?php
get_footer();
