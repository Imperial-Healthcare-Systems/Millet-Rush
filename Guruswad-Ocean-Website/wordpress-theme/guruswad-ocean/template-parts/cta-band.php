<?php
/**
 * Invest / Partner / Enquire band.
 *
 * @package Guruswad_Ocean
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="section section--dark cta-band" id="partner">
	<div class="container cta-band__inner">
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Invest & Partner', 'guruswad' ); ?></span>
			<h2 class="h-xl" data-split><?php esc_html_e( 'The future grows in millets.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'We welcome investors, distributors, co-manufacturers and technology partners who share our ambition for a fast-growing global category.', 'guruswad' ); ?></p>
			<div class="seal" aria-hidden="true"><svg viewBox="0 0 200 200" focusable="false"><defs><path id="gor-seal-path" fill="none" d="M100,100 m-74,0 a74,74 0 1,1 148,0 a74,74 0 1,1 -148,0"></path></defs><text><textPath href="#gor-seal-path" startOffset="0"><?php echo esc_html( get_bloginfo( 'name' ) ); ?> &#183; <?php esc_html_e( 'From Soil to Signature', 'guruswad' ); ?> &#183; </textPath></text></svg><span class="seal__mark"></span></div>
		</div>
		<div class="cta-band__actions" data-reveal>
			<a class="btn btn--gold" href="<?php echo esc_url( gor_enquiry_url( 'investor' ) ); ?>" data-magnetic><?php esc_html_e( 'Investor enquiry', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
			<a class="btn btn--ghost-light" href="<?php echo esc_url( gor_enquiry_url( 'partner' ) ); ?>" data-magnetic><?php esc_html_e( 'Business partnership', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
			<a class="btn btn--ghost-light" href="<?php echo esc_url( gor_enquiry_url( 'bulk' ) ); ?>" data-magnetic><?php esc_html_e( 'Bulk order', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
			<a class="btn btn--ghost-light" href="<?php echo esc_url( gor_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WhatsApp us', 'guruswad' ); ?> <?php gor_the_icon( 'whatsapp' ); ?></a>
		</div>
	</div>
</section>
