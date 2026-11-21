<?php
/**
 * Brand World template (cloned automatically for every brand).
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
$gor_logo = get_post_meta( get_the_ID(), 'gor_logo_url', true );
$gor_tag  = get_post_meta( get_the_ID(), 'gor_tagline', true );
$gor_cat  = get_post_meta( get_the_ID(), 'gor_product_cat', true );
$gor_acc  = get_post_meta( get_the_ID(), 'gor_accent', true );
?>
<section class="hero">
	<div class="container split hero__inner">
		<div>
			<span class="eyebrow"><?php esc_html_e( 'A Guruswad Ocean brand', 'guruswad' ); ?></span>
			<h1 class="hero__title" style="color:var(--forest);font-size:clamp(3rem,7vw,7rem)"><span class="line"><span><?php the_title(); ?></span></span></h1>
			<?php if ( $gor_tag ) : ?><p class="hero__sub" style="color:var(--ink-soft)"><?php echo esc_html( $gor_tag ); ?></p><?php endif; ?>
			<div class="btn-row"><a class="btn" href="<?php echo esc_url( $gor_cat && function_exists( 'get_term_link' ) && term_exists( $gor_cat, 'product_cat' ) ? get_term_link( $gor_cat, 'product_cat' ) : gor_shop_url() ); ?>" data-magnetic><?php esc_html_e( 'Shop the range', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a><a class="btn btn--ghost" href="<?php echo esc_url( gor_enquiry_url( 'bulk', get_the_title() ) ); ?>"><?php esc_html_e( 'Bulk & trade', 'guruswad' ); ?></a></div>
		</div>
		<div class="brand-feature__stage">
			<span class="brand-feature__ring"></span><span class="brand-feature__ring"></span>
			<?php if ( $gor_logo ) : ?><img class="brand-feature__logo" src="<?php echo esc_url( $gor_logo ); ?>" alt="<?php the_title_attribute(); ?>"><?php endif; ?>
		</div>
	</div>
</section>

<section class="section manifesto">
	<div class="container">
		<div class="manifesto__grid">
			<div class="manifesto__aside">
				<span class="eyebrow" data-reveal><?php esc_html_e( 'The brand', 'guruswad' ); ?></span>
				<span class="manifesto__folio" aria-hidden="true">01</span>
			</div>
			<div class="manifesto__body">
				<p class="manifesto__text manifesto__text--md"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<div class="manifesto__sign" data-reveal><span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span></div>
			</div>
		</div>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
<section class="section section--cream2"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<?php if ( function_exists( 'wc_get_products' ) ) : $gor_items = wc_get_products( array( 'limit' => 8, 'status' => 'publish', 'category' => $gor_cat ? array( $gor_cat ) : array() ) ); else : $gor_items = array(); endif; ?>
<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'The range', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Made to be reached for, every day.', 'guruswad' ); ?></h2></div>
		<div class="product-grid">
			<?php
			if ( $gor_items ) {
				foreach ( $gor_items as $p ) {
					gor_product_card( $p );
				}
			} else {
				foreach ( gor_demo_products() as $p ) {
					gor_product_card( $p );
				}
			}
			?>
		</div>
	</div>
</section>
<?php
gor_cta_band();
get_footer();
