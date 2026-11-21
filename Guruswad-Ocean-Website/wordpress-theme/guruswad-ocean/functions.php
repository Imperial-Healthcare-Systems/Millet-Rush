<?php
/**
 * Guruswad Ocean theme bootstrap.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

define( 'GOR_VERSION', '1.0.0' );
define( 'GOR_DIR', get_template_directory() );
define( 'GOR_URI', get_template_directory_uri() );

require_once GOR_DIR . '/inc/icons.php';
require_once GOR_DIR . '/inc/setup.php';
require_once GOR_DIR . '/inc/customizer.php';
require_once GOR_DIR . '/inc/template-tags.php';
require_once GOR_DIR . '/inc/post-types.php';
require_once GOR_DIR . '/inc/markets.php';

if ( is_admin() ) {
	require_once GOR_DIR . '/inc/admin.php';
}
require_once GOR_DIR . '/inc/enquiries.php';
require_once GOR_DIR . '/inc/turnstile.php';
require_once GOR_DIR . '/inc/i18n.php';
require_once GOR_DIR . '/inc/seo.php';

if ( class_exists( 'WooCommerce' ) ) {
	require_once GOR_DIR . '/inc/woocommerce.php';
}
