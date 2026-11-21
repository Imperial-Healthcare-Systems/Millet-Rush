<?php
/**
 * Multi-step Request for Quotation (Export). Works without international payments.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="enquiry-hub" id="enquiry" data-enquiry-hub>
	<div>
		<span class="eyebrow" data-reveal><?php esc_html_e( 'Request for Quotation', 'guruswad' ); ?></span>
		<h2 class="h-lg" data-split><?php esc_html_e( 'Tell us what you need. We’ll quote within two working days.', 'guruswad' ); ?></h2>
		<ul class="check-list" data-reveal>
			<li><?php esc_html_e( 'Export-grade millets and millet products', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'Retail packs, bulk bags or private label', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'Samples available on request', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'No online payment needed to enquire', 'guruswad' ); ?></li>
		</ul>
	</div>
	<div data-reveal>
		<?php if ( isset( $_GET['sent'] ) ) : // phpcs:ignore ?>
			<div class="notice" role="status"><strong><?php esc_html_e( 'Thank you — your RFQ has been received.', 'guruswad' ); ?></strong></div>
		<?php elseif ( isset( $_GET['err'] ) ) : // phpcs:ignore ?>
			<div class="notice notice--error" role="alert"><?php esc_html_e( 'Please complete the required fields and try again.', 'guruswad' ); ?></div>
		<?php endif; ?>
		<form class="form" data-steps action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<div class="form-steps full" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
			<input type="hidden" name="action" value="gor_enquiry"><input type="hidden" name="enquiry_type" value="export">
			<input type="hidden" name="lang" value="<?php echo esc_attr( gor_current_lang() ); ?>"><input type="hidden" name="source" value="RFQ"><input type="hidden" name="ts" value="<?php echo esc_attr( time() ); ?>">
			<?php wp_nonce_field( 'gor_enquiry', 'gor_enquiry_nonce' ); ?>
			<div class="hp-field" aria-hidden="true"><input type="text" name="website_url" tabindex="-1" autocomplete="off"></div>

			<fieldset class="step-panel full form" style="border:0;padding:0;margin:0">
				<legend class="h-md" style="grid-column:1/-1"><?php esc_html_e( '1 · Product', 'guruswad' ); ?></legend>
				<div class="field full"><input id="r-product" name="product" type="text" placeholder=" " required><label for="r-product"><?php esc_html_e( 'Product(s) *', 'guruswad' ); ?></label><p class="hint"><?php esc_html_e( 'e.g. Sorghum puffs, pearl millet grain, millet flour', 'guruswad' ); ?></p></div>
			</fieldset>
			<fieldset class="step-panel full form" style="border:0;padding:0;margin:0" hidden>
				<legend class="h-md" style="grid-column:1/-1"><?php esc_html_e( '2 · Volume & packaging', 'guruswad' ); ?></legend>
				<div class="field"><input id="r-qty" name="quantity" type="text" placeholder=" " required><label for="r-qty"><?php esc_html_e( 'Quantity *', 'guruswad' ); ?></label></div>
				<div class="field"><select id="r-pack" name="packaging"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option><?php esc_html_e( 'Retail packs', 'guruswad' ); ?></option><option><?php esc_html_e( 'Bulk bags', 'guruswad' ); ?></option><option><?php esc_html_e( 'Private label', 'guruswad' ); ?></option><option><?php esc_html_e( 'Custom', 'guruswad' ); ?></option></select><label for="r-pack"><?php esc_html_e( 'Packaging', 'guruswad' ); ?></label></div>
				<div class="field full"><select id="r-time" name="timeline"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option><?php esc_html_e( 'Immediately', 'guruswad' ); ?></option><option><?php esc_html_e( '1–3 months', 'guruswad' ); ?></option><option><?php esc_html_e( '3–6 months', 'guruswad' ); ?></option><option><?php esc_html_e( 'Exploring', 'guruswad' ); ?></option></select><label for="r-time"><?php esc_html_e( 'Timeline', 'guruswad' ); ?></label></div>
			</fieldset>
			<fieldset class="step-panel full form" style="border:0;padding:0;margin:0" hidden>
				<legend class="h-md" style="grid-column:1/-1"><?php esc_html_e( '3 · Destination', 'guruswad' ); ?></legend>
				<div class="field"><input id="r-country" name="country" type="text" placeholder=" " required><label for="r-country"><?php esc_html_e( 'Destination country *', 'guruswad' ); ?></label></div>
				<div class="field"><input id="r-port" name="port" type="text" placeholder=" "><label for="r-port"><?php esc_html_e( 'Destination port', 'guruswad' ); ?></label></div>
				<div class="field full"><select id="r-inco" name="incoterm"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option>EXW</option><option>FOB</option><option>CIF</option><option>CFR</option><option>DAP</option><option><?php esc_html_e( 'Not sure', 'guruswad' ); ?></option></select><label for="r-inco"><?php esc_html_e( 'Preferred Incoterm', 'guruswad' ); ?></label></div>
			</fieldset>
			<fieldset class="step-panel full form" style="border:0;padding:0;margin:0" hidden>
				<legend class="h-md" style="grid-column:1/-1"><?php esc_html_e( '4 · Your details', 'guruswad' ); ?></legend>
				<div class="field"><input id="r-name" name="name" type="text" placeholder=" " required autocomplete="name"><label for="r-name"><?php esc_html_e( 'Full name *', 'guruswad' ); ?></label></div>
				<div class="field"><input id="r-company" name="company" type="text" placeholder=" " autocomplete="organization"><label for="r-company"><?php esc_html_e( 'Company', 'guruswad' ); ?></label></div>
				<div class="field"><input id="r-email" name="email" type="email" placeholder=" " required autocomplete="email"><label for="r-email"><?php esc_html_e( 'Email *', 'guruswad' ); ?></label></div>
				<div class="field"><input id="r-phone" name="phone" type="tel" placeholder=" " autocomplete="tel"><label for="r-phone"><?php esc_html_e( 'Phone / WhatsApp', 'guruswad' ); ?></label></div>
				<div class="field full"><textarea id="r-msg" name="message" placeholder=" "></textarea><label for="r-msg"><?php esc_html_e( 'Specifications or notes', 'guruswad' ); ?></label></div>
				<label class="consent full"><input type="checkbox" name="consent" value="1" required> <span><?php esc_html_e( 'I agree to be contacted about this request.', 'guruswad' ); ?></span></label>
			</fieldset>

			<div class="full btn-row">
				<button class="btn btn--ghost" data-prev type="button"><?php esc_html_e( 'Back', 'guruswad' ); ?></button>
				<button class="btn" data-next type="button"><?php esc_html_e( 'Continue', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></button>
				<?php gor_turnstile_field(); ?>
				<button class="btn btn--gold" type="submit"><?php esc_html_e( 'Submit RFQ', 'guruswad' ); ?></button>
			</div>
		</form>
	</div>
</div>
