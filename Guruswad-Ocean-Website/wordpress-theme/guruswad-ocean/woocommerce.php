<?php
/**
 * WooCommerce wrapper (shop, categories, product, cart, checkout, account).
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="container" style="padding-bottom:var(--section)">
	<?php if ( is_shop() || is_product_taxonomy() ) : ?>
		<header class="shop-head">
			<span class="eyebrow"><?php esc_html_e( 'MilletRush · The Collection', 'guruswad' ); ?></span>
			<h1 data-split><?php woocommerce_page_title(); ?></h1>
			<nav class="shop-cats" aria-label="<?php esc_attr_e( 'Product categories', 'guruswad' ); ?>">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="<?php echo is_shop() ? 'is-active' : ''; ?>"><?php esc_html_e( 'All', 'guruswad' ); ?></a>
				<?php
				$gor_current = get_queried_object_id();
				foreach ( get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) ) as $t ) {
					if ( 'uncategorized' === $t->slug ) {
						continue;
					}
					printf( '<a href="%s" class="%s">%s</a>', esc_url( get_term_link( $t ) ), $gor_current === $t->term_id ? 'is-active' : '', esc_html( $t->name ) );
				}
				?>
			</nav>
		</header>
	<?php endif; ?>
	<?php woocommerce_content(); ?>
</div>
<?php
get_footer();
