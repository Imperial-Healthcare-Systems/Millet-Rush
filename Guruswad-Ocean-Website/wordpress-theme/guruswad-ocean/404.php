<?php
/**
 * 404.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="hero" style="min-height:90svh;align-items:center">
	<div class="hero__art" aria-hidden="true"><?php gor_millet_art(); ?></div>
	<div class="container hero__inner">
		<span class="eyebrow">404</span>
		<h1 class="hero__title" style="max-width:12ch"><?php esc_html_e( 'This field is still', 'guruswad' ); ?> <em><?php esc_html_e( 'growing.', 'guruswad' ); ?></em></h1>
		<p class="hero__sub"><?php esc_html_e( 'The page you were looking for has moved or does not exist.', 'guruswad' ); ?></p>
		<div class="btn-row"><a class="btn btn--gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'guruswad' ); ?></a><a class="btn btn--ghost-light" href="<?php echo esc_url( gor_shop_url() ); ?>"><?php esc_html_e( 'Shop MilletRush', 'guruswad' ); ?></a></div>
	</div>
</section>
<?php
get_footer();
