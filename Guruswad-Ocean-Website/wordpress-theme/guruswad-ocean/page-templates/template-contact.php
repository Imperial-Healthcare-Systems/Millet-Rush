<?php
/**
 * Template Name: Contact / Enquiry Hub
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Contact Us', 'guruswad' ), 'title' => __( 'Let’s <em>talk</em>.', 'guruswad' ), 'lead' => __( 'Shopping, sourcing, exporting, investing or partnering — one place for every conversation.', 'guruswad' ) ) );
?>

<section class="section">
	<div class="container">
		<div class="cards-3" style="margin-bottom:var(--section)">
			<a class="contact-card" href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', gor_opt( 'phone' ) ) ); ?>" data-reveal><span class="card__icon" style="display:block;width:32px;color:var(--olive)"><?php gor_the_icon( 'phone' ); ?></span><h4><?php esc_html_e( 'Call', 'guruswad' ); ?></h4><p><?php echo esc_html( gor_opt( 'phone' ) ); ?></p></a>
			<a class="contact-card" href="mailto:<?php echo esc_attr( gor_opt( 'email' ) ); ?>" data-reveal><span class="card__icon" style="display:block;width:32px;color:var(--olive)"><?php gor_the_icon( 'mail' ); ?></span><h4><?php esc_html_e( 'Email', 'guruswad' ); ?></h4><p><?php echo esc_html( gor_opt( 'email' ) ); ?></p></a>
			<div class="contact-card" data-reveal><span class="card__icon" style="display:block;width:32px;color:var(--olive)"><?php gor_the_icon( 'pin' ); ?></span><h4><?php esc_html_e( 'Visit', 'guruswad' ); ?></h4><p><?php echo esc_html( gor_opt( 'address' ) ); ?></p></div>
		</div>
		<?php get_template_part( 'template-parts/enquiry-form', null, array( 'type' => 'general' ) ); ?>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
<section class="section section--cream2"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<?php
get_footer();
