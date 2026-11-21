<?php
/**
 * Bilingual support (English / Marathi).
 * Works with Polylang (+ Polylang for WooCommerce) or WPML (+ WooCommerce Multilingual).
 * Theme strings are translated through languages/guruswad-mr.po.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Current language code: 'en' or 'mr'.
 *
 * @return string
 */
function gor_current_lang() {
	if ( function_exists( 'pll_current_language' ) && pll_current_language() ) {
		return pll_current_language();
	}
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return ICL_LANGUAGE_CODE;
	}
	return 0 === strpos( get_locale(), 'mr' ) ? 'mr' : 'en';
}

/**
 * Language list for the switcher.
 *
 * @return array[] each: code, label, url, active
 */
function gor_languages() {
	$out = array();
	if ( function_exists( 'pll_the_languages' ) ) {
		$langs = pll_the_languages( array( 'raw' => 1, 'hide_if_empty' => 0 ) );
		foreach ( (array) $langs as $l ) {
			$out[] = array( 'code' => $l['slug'], 'label' => 'mr' === $l['slug'] ? 'मराठी' : strtoupper( $l['slug'] ), 'url' => $l['url'], 'active' => ! empty( $l['current_lang'] ) );
		}
		return $out;
	}
	$wpml = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
	if ( ! empty( $wpml ) ) {
		foreach ( $wpml as $l ) {
			$out[] = array( 'code' => $l['code'], 'label' => 'mr' === $l['code'] ? 'मराठी' : strtoupper( $l['code'] ), 'url' => $l['url'], 'active' => ! empty( $l['active'] ) );
		}
		return $out;
	}
	// No multilingual plugin yet: show the switcher as a design placeholder.
	$cur = gor_current_lang();
	return array(
		array( 'code' => 'en', 'label' => 'EN', 'url' => home_url( '/' ), 'active' => 'en' === $cur ),
		array( 'code' => 'mr', 'label' => 'मराठी', 'url' => home_url( '/mr/' ), 'active' => 'mr' === $cur ),
	);
}

/**
 * Print the language switcher.
 */
function gor_lang_switcher() {
	echo '<nav class="lang-switch" aria-label="' . esc_attr__( 'Language', 'guruswad' ) . '">';
	foreach ( gor_languages() as $l ) {
		printf(
			'<a href="%1$s" hreflang="%2$s" lang="%2$s" class="%3$s"%4$s>%5$s</a>',
			esc_url( $l['url'] ),
			esc_attr( $l['code'] ),
			$l['active'] ? 'is-active' : '',
			$l['active'] ? ' aria-current="true"' : '',
			esc_html( $l['label'] )
		);
	}
	echo '</nav>';
}

/**
 * Register the theme's admin-editable strings with Polylang's String Translation screen.
 */
add_action(
	'init',
	function () {
		if ( function_exists( 'pll_register_string' ) ) {
			foreach ( array( 'company_name', 'address' ) as $k ) {
				pll_register_string( 'gor_' . $k, (string) gor_opt( $k ), 'Guruswad Ocean' );
			}
		}
	}
);
