<?php
/**
 * Enquiry Hub — one elegant form serving all seven enquiry types.
 * Usage: get_template_part( 'template-parts/enquiry-form', null, array( 'type' => 'export', 'picker' => true ) );
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

$gor_type   = isset( $args['type'] ) ? $args['type'] : 'general';
$gor_picker = ! isset( $args['picker'] ) || $args['picker'];
$gor_types  = gor_enquiry_types();
$gor_sent   = isset( $_GET['sent'] ); // phpcs:ignore WordPress.Security.NonceVerification
$gor_err    = isset( $_GET['err'] ); // phpcs:ignore WordPress.Security.NonceVerification
?>
<div class="enquiry-hub" id="enquiry" data-enquiry-hub>
	<div>
		<span class="eyebrow" data-reveal><?php esc_html_e( 'Enquiry Hub', 'guruswad' ); ?></span>
		<h2 class="h-lg" data-split><?php esc_html_e( 'How can we help?', 'guruswad' ); ?></h2>
		<p class="lead" data-reveal><?php esc_html_e( 'Choose what you need. Every enquiry reaches the right team and we reply within one working day.', 'guruswad' ); ?></p>
		<?php if ( $gor_picker ) : ?>
			<div class="type-picker" role="radiogroup" aria-label="<?php esc_attr_e( 'Enquiry type', 'guruswad' ); ?>" data-reveal>
				<?php foreach ( $gor_types as $k => $label ) : ?>
					<input type="radio" name="type_pick" id="tp-<?php echo esc_attr( $k ); ?>" value="<?php echo esc_attr( $k ); ?>" data-label="<?php echo esc_attr( $label ); ?>" <?php checked( $gor_type, $k ); ?>>
					<label for="tp-<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?> <?php gor_the_icon( 'arrow' ); ?></label>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div data-reveal>
		<?php if ( $gor_sent ) : ?>
			<div class="notice" role="status"><strong><?php esc_html_e( 'Thank you — your enquiry has been received.', 'guruswad' ); ?></strong> <?php esc_html_e( 'A confirmation has been sent to your email.', 'guruswad' ); ?></div>
		<?php elseif ( $gor_err ) : ?>
			<div class="notice notice--error" role="alert"><?php esc_html_e( 'Please complete your name, a valid email and the consent box, then try again.', 'guruswad' ); ?></div>
		<?php endif; ?>

		<h3 class="h-md" data-enquiry-title><?php echo esc_html( $gor_types[ $gor_type ] ); ?></h3>
		<form class="form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="gor_enquiry">
			<input type="hidden" name="enquiry_type" value="<?php echo esc_attr( $gor_type ); ?>">
			<input type="hidden" name="lang" value="<?php echo esc_attr( gor_current_lang() ); ?>">
			<input type="hidden" name="source" value="<?php echo esc_url( home_url( add_query_arg( array() ) ) ); ?>">
			<input type="hidden" name="utm" value="<?php echo esc_attr( isset( $_GET['utm_campaign'] ) ? sanitize_text_field( wp_unslash( $_GET['utm_campaign'] ) ) : '' ); // phpcs:ignore ?>">
			<input type="hidden" name="ts" value="<?php echo esc_attr( time() ); ?>">
			<?php wp_nonce_field( 'gor_enquiry', 'gor_enquiry_nonce' ); ?>
			<div class="hp-field" aria-hidden="true"><label>Website <input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off" aria-hidden="true"></label></div>

			<div class="field"><input id="f-name" name="name" type="text" placeholder=" " required autocomplete="name"><label for="f-name"><?php esc_html_e( 'Full name *', 'guruswad' ); ?></label></div>
			<div class="field"><input id="f-company" name="company" type="text" placeholder=" " autocomplete="organization"><label for="f-company"><?php esc_html_e( 'Company / organisation', 'guruswad' ); ?></label></div>
			<div class="field"><input id="f-email" name="email" type="email" placeholder=" " required autocomplete="email"><label for="f-email"><?php esc_html_e( 'Email *', 'guruswad' ); ?></label></div>
			<div class="field"><input id="f-phone" name="phone" type="tel" placeholder=" " autocomplete="tel"><label for="f-phone"><?php esc_html_e( 'Phone / WhatsApp', 'guruswad' ); ?></label></div>

			<div class="field" data-only="export import partner investor general"><input id="f-country" name="country" type="text" placeholder=" " autocomplete="country-name"><label for="f-country"><?php esc_html_e( 'Country', 'guruswad' ); ?></label></div>
			<div class="field" data-only="bulk product general"><input id="f-city" name="city" type="text" placeholder=" " autocomplete="address-level2"><label for="f-city"><?php esc_html_e( 'City', 'guruswad' ); ?></label></div>

			<div class="field" data-only="product bulk export import"><input id="f-product" name="product" type="text" placeholder=" "><label for="f-product"><?php esc_html_e( 'Product(s) of interest', 'guruswad' ); ?></label></div>
			<div class="field" data-only="bulk export import"><input id="f-qty" name="quantity" type="text" placeholder=" "><label for="f-qty"><?php esc_html_e( 'Quantity (kg / tonnes / containers)', 'guruswad' ); ?></label></div>

			<div class="field" data-only="export"><select id="f-inco" name="incoterm"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option>EXW</option><option>FOB</option><option>CIF</option><option>CFR</option><option>DAP</option><option><?php esc_html_e( 'Not sure', 'guruswad' ); ?></option></select><label for="f-inco"><?php esc_html_e( 'Preferred Incoterm', 'guruswad' ); ?></label></div>
			<div class="field" data-only="export"><input id="f-port" name="port" type="text" placeholder=" "><label for="f-port"><?php esc_html_e( 'Destination port', 'guruswad' ); ?></label></div>

			<div class="field" data-only="investor"><select id="f-inv" name="investment"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option><?php esc_html_e( 'Strategic investment', 'guruswad' ); ?></option><option><?php esc_html_e( 'Equity partnership', 'guruswad' ); ?></option><option><?php esc_html_e( 'Project / facility funding', 'guruswad' ); ?></option><option><?php esc_html_e( 'Other', 'guruswad' ); ?></option></select><label for="f-inv"><?php esc_html_e( 'Area of interest', 'guruswad' ); ?></label></div>
			<div class="field" data-only="investor partner"><select id="f-org" name="organisation"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option><?php esc_html_e( 'Individual investor', 'guruswad' ); ?></option><option><?php esc_html_e( 'Fund / institution', 'guruswad' ); ?></option><option><?php esc_html_e( 'Corporate', 'guruswad' ); ?></option><option><?php esc_html_e( 'Distributor / retailer', 'guruswad' ); ?></option><option><?php esc_html_e( 'Government / NGO', 'guruswad' ); ?></option></select><label for="f-org"><?php esc_html_e( 'Organisation type', 'guruswad' ); ?></label></div>
			<div class="field" data-only="partner"><select id="f-pm" name="partnership"><option value=""><?php esc_html_e( 'Select', 'guruswad' ); ?></option><option><?php esc_html_e( 'Distribution', 'guruswad' ); ?></option><option><?php esc_html_e( 'Co-manufacturing', 'guruswad' ); ?></option><option><?php esc_html_e( 'Private label', 'guruswad' ); ?></option><option><?php esc_html_e( 'Technology', 'guruswad' ); ?></option><option><?php esc_html_e( 'Retail / modern trade', 'guruswad' ); ?></option><option><?php esc_html_e( 'Export partnership', 'guruswad' ); ?></option></select><label for="f-pm"><?php esc_html_e( 'Partnership model', 'guruswad' ); ?></label></div>

			<div class="field full"><textarea id="f-msg" name="message" placeholder=" "></textarea><label for="f-msg"><?php esc_html_e( 'Your message', 'guruswad' ); ?></label></div>

			<label class="consent full" for="gor-consent"><input type="checkbox" id="gor-consent" name="consent" value="1" required> <span><?php esc_html_e( 'I agree that Guruswad Ocean may contact me about this enquiry and store my details as described in the privacy policy.', 'guruswad' ); ?></span></label>
			<div class="full btn-row">
				<?php gor_turnstile_field(); ?>
				<button class="btn" type="submit" data-magnetic><?php esc_html_e( 'Send enquiry', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></button>
				<a class="btn btn--ghost" href="<?php echo esc_url( gor_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php gor_the_icon( 'whatsapp' ); ?> WhatsApp</a>
			</div>
		</form>
	</div>
</div>
