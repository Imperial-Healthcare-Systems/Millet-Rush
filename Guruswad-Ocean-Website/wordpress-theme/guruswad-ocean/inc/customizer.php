<?php
/**
 * Customizer: business details, hero media, impact numbers, enquiry routing.
 * Everything here is editable by the client under Appearance → Customize → Guruswad Ocean.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Defaults (placeholders the client replaces).
 *
 * @return array
 */
function gor_defaults() {
	return array(
		'company_name'    => 'Guruswad Ocean Private Limited',
		'phone'           => '+91 XXXXX XXXXX',
		'whatsapp'        => '91XXXXXXXXXX',
		'email'           => 'info@yourdomain.com',
		'address'         => 'Registered office, Maharashtra, India',
		'hero_video'      => '',
		'hero_poster'     => '',
		'stat_farmers'    => 650,
		'stat_shg'        => 50,
		'stat_entre'      => 50,
		'stat_fpo'        => 1,
		'catalogue_pdf'   => '',
		'mail_general'    => '',
		'mail_product'    => '',
		'mail_export'     => '',
		'mail_bulk'       => '',
		'mail_import'     => '',
		'mail_investor'   => '',
		'mail_partner'    => '',
		'social_facebook' => '#',
		'social_instagram'=> '#',
		'social_linkedin' => '#',
		'social_x'        => '#',
		'social_youtube'  => '',
		'social_gbp'      => '',
		'icar_note'       => '',
		'turnstile_site'  => '',
		'turnstile_secret'=> '',
		'ga4_id'          => '',
		'gsc_verify'      => '',
	);
}

/**
 * Get a theme option with default.
 *
 * @param string $key Key.
 * @return mixed
 */
function gor_opt( $key ) {
	$d = gor_defaults();
	return get_theme_mod( 'gor_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
}

/**
 * Register Customizer panel.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function gor_customize_register( $wp_customize ) {
	$wp_customize->add_panel( 'gor_panel', array( 'title' => __( 'Guruswad Ocean', 'guruswad' ), 'priority' => 20 ) );

	$sections = array(
		'gor_business' => array( __( 'Business details', 'guruswad' ), array(
			'company_name' => array( __( 'Company name', 'guruswad' ), 'text' ),
			'phone'        => array( __( 'Phone', 'guruswad' ), 'text' ),
			'whatsapp'     => array( __( 'WhatsApp number (digits with country code, e.g. 919800000000)', 'guruswad' ), 'text' ),
			'email'        => array( __( 'Public email', 'guruswad' ), 'email' ),
			'address'      => array( __( 'Address', 'guruswad' ), 'textarea' ),
			'catalogue_pdf'=> array( __( 'Export catalogue PDF URL', 'guruswad' ), 'url' ),
		) ),
		'gor_hero' => array( __( 'Home hero', 'guruswad' ), array(
			'hero_video'  => array( __( 'Hero background video (MP4 URL, optional)', 'guruswad' ), 'url' ),
			'hero_poster' => array( __( 'Hero poster / image URL (optional)', 'guruswad' ), 'url' ),
		) ),
		'gor_stats' => array( __( 'Impact numbers', 'guruswad' ), array(
			'stat_farmers' => array( __( 'Farmers in network', 'guruswad' ), 'number' ),
			'stat_shg'     => array( __( 'Women Self-Help Groups', 'guruswad' ), 'number' ),
			'stat_entre'   => array( __( 'Small entrepreneurs', 'guruswad' ), 'number' ),
			'stat_fpo'     => array( __( 'Farmer Producer Companies', 'guruswad' ), 'number' ),
		) ),
		'gor_routing' => array( __( 'Enquiry routing (blank = site admin email)', 'guruswad' ), array(
			'mail_general'  => array( __( 'General enquiries', 'guruswad' ), 'email' ),
			'mail_product'  => array( __( 'Product enquiries', 'guruswad' ), 'email' ),
			'mail_export'   => array( __( 'Export enquiries / RFQ', 'guruswad' ), 'email' ),
			'mail_bulk'     => array( __( 'Bulk orders', 'guruswad' ), 'email' ),
			'mail_import'   => array( __( 'Import enquiries', 'guruswad' ), 'email' ),
			'mail_investor' => array( __( 'Investor enquiries', 'guruswad' ), 'email' ),
			'mail_partner'  => array( __( 'Business partnerships', 'guruswad' ), 'email' ),
		) ),
		'gor_spam' => array( __( 'Spam protection (Cloudflare Turnstile)', 'guruswad' ), array(
			'turnstile_site'   => array( __( 'Turnstile site key (public) — leave blank to disable', 'guruswad' ), 'text' ),
			'turnstile_secret' => array( __( 'Turnstile secret key (needed to actually verify)', 'guruswad' ), 'text' ),
		) ),
		'gor_social' => array( __( 'Social links', 'guruswad' ), array(
			'social_facebook'  => array( 'Facebook', 'url' ),
			'social_instagram' => array( 'Instagram', 'url' ),
			'social_linkedin'  => array( 'LinkedIn', 'url' ),
			'social_x'         => array( 'X (Twitter)', 'url' ),
			'social_youtube'   => array( 'YouTube', 'url' ),
			'social_gbp'       => array( __( 'Google Business Profile', 'guruswad' ), 'url' ),
		) ),
		/* Both IDs belong to the company's own Google account, never the
		   developer's — §19 "no essential functionality on a developer account". */
		'gor_analytics' => array( __( 'Analytics & Search Console', 'guruswad' ), array(
			'ga4_id'     => array( __( 'Google Analytics 4 measurement ID (G-XXXXXXXXXX)', 'guruswad' ), 'text' ),
			'gsc_verify' => array( __( 'Search Console verification code', 'guruswad' ), 'text' ),
		) ),
	);

	$d = gor_defaults();
	foreach ( $sections as $sid => $sec ) {
		$wp_customize->add_section( $sid, array( 'title' => $sec[0], 'panel' => 'gor_panel' ) );
		foreach ( $sec[1] as $key => $f ) {
			$san = 'sanitize_text_field';
			if ( 'url' === $f[1] ) {
				$san = 'esc_url_raw';
			} elseif ( 'email' === $f[1] ) {
				$san = 'sanitize_email';
			} elseif ( 'number' === $f[1] ) {
				$san = 'absint';
			} elseif ( 'textarea' === $f[1] ) {
				$san = 'sanitize_textarea_field';
			}
			$wp_customize->add_setting( 'gor_' . $key, array( 'default' => $d[ $key ], 'sanitize_callback' => $san ) );
			$wp_customize->add_control( 'gor_' . $key, array( 'label' => $f[0], 'section' => $sid, 'type' => $f[1] ) );
		}
	}
}
add_action( 'customize_register', 'gor_customize_register' );
