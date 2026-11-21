<?php
/**
 * Cloudflare Turnstile — the invisible CAPTCHA the blueprint asks for (§10.3),
 * sitting alongside the honeypot and the minimum-fill-time check already in
 * inc/enquiries.php.
 *
 * The whole module is a no-op until the client pastes their keys into
 * Appearance → Customize → Guruswad Ocean → Spam protection, so the theme
 * works out of the box and the two existing guards keep running regardless.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

const GOR_TURNSTILE_VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
const GOR_TURNSTILE_SCRIPT_URL = 'https://challenges.cloudflare.com/turnstile/v0/api.js';

/**
 * Is the widget configured? Requires only the public site key.
 *
 * @return bool
 */
function gor_turnstile_enabled() {
	return '' !== trim( (string) gor_opt( 'turnstile_site' ) );
}

/**
 * Is server-side verification configured? Requires the secret key too.
 *
 * A site key without a secret renders the challenge but cannot check it, which
 * is worse than useless — so verification is skipped until both are present.
 *
 * @return bool
 */
function gor_turnstile_can_verify() {
	return gor_turnstile_enabled() && '' !== trim( (string) gor_opt( 'turnstile_secret' ) );
}

/**
 * Print the widget inside a form. Call just before the submit button.
 *
 * @return void
 */
function gor_turnstile_field() {
	if ( ! gor_turnstile_enabled() ) {
		return;
	}
	wp_enqueue_script( 'gor-turnstile', GOR_TURNSTILE_SCRIPT_URL, array(), null, true ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	printf(
		'<div class="full cf-turnstile" data-sitekey="%s" data-theme="light" data-size="flexible" data-appearance="interaction-only"></div>',
		esc_attr( trim( (string) gor_opt( 'turnstile_site' ) ) )
	);
}

/**
 * Load the Cloudflare script with defer, as their docs require.
 *
 * @param string $tag    Script tag.
 * @param string $handle Handle.
 * @return string
 */
function gor_turnstile_script_tag( $tag, $handle ) {
	if ( 'gor-turnstile' === $handle && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( '<script ', '<script defer ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'gor_turnstile_script_tag', 10, 2 );

/**
 * Verify a submitted token with Cloudflare.
 *
 * Fails CLOSED on an explicit negative verdict from Cloudflare — that is a real
 * signal. Fails OPEN when the request itself cannot be made (outage, blocked
 * egress, timeout), because locking every genuine enquiry out of the site for
 * the duration of someone else's outage costs more than the spam it stops. The
 * honeypot and timing guard still apply in that window.
 *
 * @param string $token Token from the widget.
 * @param string $ip    Remote IP, optional.
 * @return bool True when the submission may proceed.
 */
function gor_turnstile_verify( $token, $ip = '' ) {
	if ( ! gor_turnstile_can_verify() ) {
		return true;
	}
	if ( '' === trim( (string) $token ) ) {
		return false;
	}

	$body = array(
		'secret'   => trim( (string) gor_opt( 'turnstile_secret' ) ),
		'response' => $token,
	);
	if ( '' !== $ip ) {
		$body['remoteip'] = $ip;
	}

	$res = wp_remote_post(
		GOR_TURNSTILE_VERIFY_URL,
		array(
			'timeout' => 8,
			'body'    => $body,
		)
	);

	if ( is_wp_error( $res ) ) {
		return true; // Cannot reach Cloudflare — see note above.
	}

	$code = (int) wp_remote_retrieve_response_code( $res );
	if ( $code < 200 || $code >= 300 ) {
		return true; // Cloudflare answered but not usefully; do not punish the visitor.
	}

	$json = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( ! is_array( $json ) || ! isset( $json['success'] ) ) {
		return true;
	}

	return (bool) $json['success'];
}

/**
 * The visitor's IP, as far as it can be trusted.
 *
 * Only REMOTE_ADDR is used. Forwarded headers are attacker-controlled unless a
 * known proxy is in front, and Turnstile treats remoteip as optional anyway.
 *
 * @return string
 */
function gor_turnstile_remote_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '';
}

/**
 * Gate every enquiry submission. Runs after the nonce, honeypot and timing
 * checks in gor_handle_enquiry() via the gor_enquiry_spam_check filter.
 *
 * @param bool $is_spam Whether the submission has already been rejected.
 * @return bool
 */
function gor_turnstile_gate( $is_spam ) {
	if ( $is_spam || ! gor_turnstile_can_verify() ) {
		return $is_spam;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce already verified by the caller.
	$token = isset( $_POST['cf-turnstile-response'] ) ? sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) ) : '';
	return ! gor_turnstile_verify( $token, gor_turnstile_remote_ip() );
}
add_filter( 'gor_enquiry_spam_check', 'gor_turnstile_gate' );
