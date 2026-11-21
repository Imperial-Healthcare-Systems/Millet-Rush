<?php
/**
 * Theme setup, assets and performance.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme supports, menus, image sizes.
 */
function gor_setup() {
	load_theme_textdomain( 'guruswad', GOR_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 360, 'flex-height' => true, 'flex-width' => true ) );
	add_editor_style( array( 'assets/css/main.css' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary (header)', 'guruswad' ),
			'mega'    => __( 'Full-screen menu', 'guruswad' ),
			'footer'  => __( 'Footer — Company', 'guruswad' ),
			'footer2' => __( 'Footer — Business', 'guruswad' ),
			'legal'   => __( 'Footer — Legal', 'guruswad' ),
		)
	);

	add_image_size( 'gor-hero', 2400, 1400, true );
	add_image_size( 'gor-panel', 1200, 1500, true );
	add_image_size( 'gor-card', 800, 1000, true );
	add_image_size( 'gor-wide', 1600, 1100, true );
}
add_action( 'after_setup_theme', 'gor_setup' );

/**
 * Front-end assets. GSAP + Lenis are bundled locally in assets/vendor (no CDN dependency).
 */
function gor_assets() {
	$v = GOR_VERSION;

	wp_enqueue_style( 'gor-fonts', GOR_URI . '/assets/css/fonts.css', array(), $v );
	wp_enqueue_style( 'gor-main', GOR_URI . '/assets/css/main.css', array(), $v );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'gor-woo', GOR_URI . '/assets/css/woocommerce.css', array( 'gor-main' ), $v );
	}

	wp_enqueue_script( 'gsap', GOR_URI . '/assets/vendor/gsap.min.js', array(), '3.15.1', true );
	wp_enqueue_script( 'gsap-st', GOR_URI . '/assets/vendor/ScrollTrigger.min.js', array( 'gsap' ), '3.15.1', true );
	wp_enqueue_script( 'lenis', GOR_URI . '/assets/vendor/lenis.min.js', array(), '1.3.27', true );
	wp_enqueue_script( 'gor-main', GOR_URI . '/assets/js/main.js', array( 'gsap', 'gsap-st', 'lenis' ), $v, true );
	wp_localize_script(
		'gor-main',
		'GOR',
		array(
			'home'    => home_url( '/' ),
			'ajax'    => admin_url( 'admin-ajax.php' ),
			'lang'    => gor_current_lang(),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gor_assets' );

/**
 * Defer theme scripts.
 */
function gor_defer_scripts( $tag, $handle ) {
	if ( in_array( $handle, array( 'gsap', 'gsap-st', 'lenis', 'gor-main' ), true ) ) {
		return str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'gor_defer_scripts', 10, 2 );

/**
 * Preload the display font, and set the motion flag before paint (avoids flashes).
 */
function gor_head_early() {
	echo '<link rel="preload" href="' . esc_url( GOR_URI . '/assets/fonts/fraunces-latin-opsz-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo "<script>(function(d){var r=d.documentElement;if(!matchMedia('(prefers-reduced-motion: reduce)').matches){r.classList.add('js-motion');setTimeout(function(){if(!window.__gorReady){r.classList.remove('js-motion');}},3500);}})(document);</script>\n";
	echo '<meta name="theme-color" content="#032b08">' . "\n";

	// The hero poster is the largest thing painted on the front page, and the
	// preload scanner cannot see it: it is a video poster attribute, not an
	// <img>, so it is not discovered until the element is parsed.
	if ( is_front_page() ) {
		$gor_poster = gor_opt( 'hero_poster' );
		$gor_poster = $gor_poster ? $gor_poster : GOR_URI . '/assets/img/photo/hero-soil-to-signature.jpg';
		echo '<link rel="preload" as="image" href="' . esc_url( $gor_poster ) . '" fetchpriority="high">' . "\n";
	}
}
add_action( 'wp_head', 'gor_head_early', 1 );

/**
 * Body classes.
 */
function gor_body_class( $classes ) {
	$is_shop = function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
	if ( ! $is_shop && ! is_singular( 'gor_brand' ) ) {
		$classes[] = 'has-dark-hero';
	}
	$classes[] = 'lang-' . gor_current_lang();
	return $classes;
}
add_filter( 'body_class', 'gor_body_class' );

/**
 * Page templates that open with a dark hero.
 *
 * @return array
 */
function gor_dark_hero_templates() {
	return array(
		'page-templates/template-about.php',
		'page-templates/template-manufacturing.php',
		'page-templates/template-export.php',
		'page-templates/template-farmers.php',
		'page-templates/template-investment.php',
		'page-templates/template-contact.php',
	);
}

/**
 * Light clean-up for speed.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'excerpt_length', function () { return 24; } );
add_filter( 'excerpt_more', function () { return '…'; } );
