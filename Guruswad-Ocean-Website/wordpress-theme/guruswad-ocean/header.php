<?php
/**
 * Header.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
$gor_cart_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
<link rel="icon" href="<?php echo esc_url( GOR_URI . '/assets/img/logo/favicon.svg' ); ?>" type="image/svg+xml">
<link rel="apple-touch-icon" href="<?php echo esc_url( GOR_URI . '/assets/img/logo/apple-touch-icon.png' ); ?>">
<link rel="mask-icon" href="<?php echo esc_url( GOR_URI . '/assets/img/logo/favicon.svg' ); ?>" color="#032b08">
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'guruswad' ); ?></a>

<header class="site-header" role="banner">
	<div class="container">
		<a class="brand-mark brand-mark--logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'MilletRush — Everyday Goodness of Millets', 'guruswad' ); ?>"><img class="brand-mark__img brand-mark__img--on-light" src="<?php echo esc_url( GOR_URI . '/assets/img/logo/milletrush-color.svg' ); ?>" alt="<?php esc_attr_e( 'MilletRush — Everyday Goodness of Millets', 'guruswad' ); ?>" width="988" height="500"><img class="brand-mark__img brand-mark__img--on-dark" src="<?php echo esc_url( GOR_URI . '/assets/img/logo/milletrush-white.svg' ); ?>" alt="" aria-hidden="true" width="983" height="500"></a>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'guruswad' ); ?>">
			<?php gor_menu( 'primary', gor_default_menu() ); ?>
		</nav>

		<div class="header-utils">
			<?php gor_lang_switcher(); ?>
			<a class="icon-btn" href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" aria-label="<?php esc_attr_e( 'Search', 'guruswad' ); ?>"><?php gor_the_icon( 'search' ); ?></a>
			<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
				<a class="icon-btn" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'My account', 'guruswad' ); ?>"><?php gor_the_icon( 'user' ); ?></a>
				<a class="icon-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" data-cart-open aria-label="<?php esc_attr_e( 'Cart', 'guruswad' ); ?>"><?php gor_the_icon( 'cart' ); ?><span class="cart-count"><?php echo (int) $gor_cart_count; ?></span></a>
			<?php else : ?>
				<a class="icon-btn" href="<?php echo esc_url( gor_shop_url() ); ?>" aria-label="<?php esc_attr_e( 'Shop', 'guruswad' ); ?>"><?php gor_the_icon( 'cart' ); ?></a>
			<?php endif; ?>
			<a class="btn btn--gold header-cta" href="<?php echo esc_url( gor_enquiry_url() ); ?>" data-magnetic><?php esc_html_e( 'Enquire', 'guruswad' ); ?></a>
			<button class="menu-toggle" aria-expanded="false" aria-controls="mega" aria-label="<?php esc_attr_e( 'Menu', 'guruswad' ); ?>"><span></span><span></span></button>
		</div>
	</div>
</header>

<div class="mega" id="mega" aria-hidden="true" inert>
	<div class="container mega__grid">
		<nav aria-label="<?php esc_attr_e( 'Full menu', 'guruswad' ); ?>"><?php gor_menu( 'mega', gor_default_mega_menu(), 'mega__list' ); ?></nav>
		<div class="mega__aside">
			<img src="<?php echo esc_url( GOR_URI . '/assets/img/logo/milletrush-color.svg' ); ?>" alt="MilletRush" width="988" height="500" loading="lazy">
			<p><?php esc_html_e( 'Millets grown by our farmer network, crafted for tables across the world.', 'guruswad' ); ?></p>
			<div class="btn-row">
				<a class="btn" href="<?php echo esc_url( gor_shop_url() ); ?>"><?php esc_html_e( 'Shop MilletRush', 'guruswad' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( gor_enquiry_url( 'export' ) ); ?>"><?php esc_html_e( 'Export RFQ', 'guruswad' ); ?></a>
			</div>
			<?php gor_lang_switcher(); ?>
		</div>
	</div>
</div>

<div class="curtain" aria-hidden="true"><?php gor_grain_mark(); ?></div>
<div class="cursor" aria-hidden="true"><span></span></div>

<main id="main" class="site-main">
