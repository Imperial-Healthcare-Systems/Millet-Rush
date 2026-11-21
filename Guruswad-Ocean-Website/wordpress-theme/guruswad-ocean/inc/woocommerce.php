<?php
/**
 * WooCommerce integration: retail + B2B on one product page.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'woocommerce', array( 'thumbnail_image_width' => 800, 'single_image_width' => 1200, 'product_grid' => array( 'default_columns' => 4, 'default_rows' => 3 ) ) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
);

// Our own wrappers live in woocommerce.php (theme root).
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
add_filter( 'woocommerce_enqueue_styles', function ( $s ) { unset( $s['woocommerce-layout'] ); return $s; } );
add_filter( 'loop_shop_per_page', function () { return 12; } );
add_filter( 'loop_shop_columns', function () { return 4; } );

/* ------------------------------------------------ Product data fields (admin) */

/**
 * Extra product fields.
 *
 * @return array key => [label, type]
 */
function gor_product_fields() {
	return array(
		'_gor_millet_type'  => array( __( 'Millet type', 'guruswad' ), 'text', __( 'e.g. Sorghum (Jowar)', 'guruswad' ) ),
		'_gor_origin'       => array( __( 'Sourcing region', 'guruswad' ), 'text', __( 'e.g. FPO villages, Maharashtra', 'guruswad' ) ),
		'_gor_pack_sizes'   => array( __( 'Pack sizes available', 'guruswad' ), 'text', __( 'e.g. 70 g, 200 g, 1 kg, 10 kg bulk', 'guruswad' ) ),
		'_gor_shelf_life'   => array( __( 'Shelf life', 'guruswad' ), 'text', __( 'e.g. 6 months', 'guruswad' ) ),
		'_gor_storage'      => array( __( 'Storage', 'guruswad' ), 'text', __( 'e.g. Store in a cool, dry place', 'guruswad' ) ),
		'_gor_hs_code'      => array( __( 'HS code (export)', 'guruswad' ), 'text', '' ),
		'_gor_moq'          => array( __( 'Export MOQ', 'guruswad' ), 'text', __( 'e.g. 1 × 20ft container', 'guruswad' ) ),
		'_gor_ingredients'  => array( __( 'Ingredients', 'guruswad' ), 'textarea', '' ),
		'_gor_nutrition'    => array( __( 'Nutrition (one per line: Energy | 410 kcal)', 'guruswad' ), 'textarea', '' ),
		'_gor_export_ready' => array( __( 'Export-ready product', 'guruswad' ), 'checkbox', '' ),
	);
}

add_action(
	'woocommerce_product_options_general_product_data',
	function () {
		echo '<div class="options_group"><p style="padding:0 12px;font-weight:600">' . esc_html__( 'Guruswad Ocean — product story & trade data', 'guruswad' ) . '</p>';
		foreach ( gor_product_fields() as $id => $f ) {
			$args = array( 'id' => $id, 'label' => $f[0], 'placeholder' => $f[2] );
			if ( 'textarea' === $f[1] ) {
				woocommerce_wp_textarea_input( $args );
			} elseif ( 'checkbox' === $f[1] ) {
				woocommerce_wp_checkbox( $args );
			} else {
				woocommerce_wp_text_input( $args );
			}
		}
		echo '</div>';
	}
);
add_action(
	'woocommerce_process_product_meta',
	function ( $post_id ) {
		foreach ( gor_product_fields() as $id => $f ) {
			if ( 'checkbox' === $f[1] ) {
				update_post_meta( $post_id, $id, isset( $_POST[ $id ] ) ? 'yes' : 'no' ); // phpcs:ignore WordPress.Security.NonceVerification
			} elseif ( isset( $_POST[ $id ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$v = wp_unslash( $_POST[ $id ] ); // phpcs:ignore
				update_post_meta( $post_id, $id, 'textarea' === $f[1] ? sanitize_textarea_field( $v ) : sanitize_text_field( $v ) );
			}
		}
	}
);

/* ------------------------------------------------ Single product */

/**
 * "Buy now" beside Add to cart.
 *
 * WooCommerce has no such button of its own. This one submits the normal
 * add-to-cart form with a flag, so stock checks, variations and validation all
 * still run — then the woocommerce_add_to_cart_redirect filter below sends the
 * shopper to checkout instead of back to the product.
 */
add_action(
	'woocommerce_after_add_to_cart_button',
	function () {
		global $product;
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return;
		}
		printf(
			'<button type="submit" name="gor_buy_now" value="1" class="btn btn--forest buy-now">%s</button>',
			esc_html__( 'Buy now', 'guruswad' )
		);
	}
);

/**
 * Send a "Buy now" straight to checkout once the item is in the cart.
 *
 * @param string $url Default redirect.
 * @return string
 */
add_filter(
	'woocommerce_add_to_cart_redirect',
	function ( $url ) {
		// Nonce: WooCommerce has already validated this request before the
		// filter runs; this only reads which button was pressed.
		if ( empty( $_REQUEST['gor_buy_now'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return $url;
		}
		$checkout = wc_get_checkout_url();
		return $checkout ? $checkout : $url;
	}
);

/**
 * B2B actions, WhatsApp and trust row under Add to Cart.
 */
add_action(
	'woocommerce_after_add_to_cart_form',
	function () {
		global $product;
		$name = $product->get_name();
		?>
		<div class="b2b-actions">
			<a class="btn btn--ghost" href="<?php echo esc_url( gor_enquiry_url( 'bulk', $name ) ); ?>"><?php esc_html_e( 'Bulk enquiry', 'guruswad' ); ?></a>
			<a class="btn btn--ghost" href="<?php echo esc_url( gor_enquiry_url( 'export', $name ) ); ?>"><?php esc_html_e( 'Export RFQ', 'guruswad' ); ?></a>
		</div>
		<a class="link-arrow" target="_blank" rel="noopener" href="<?php echo esc_url( gor_whatsapp_url( sprintf( __( 'Hi, I am interested in %s', 'guruswad' ), $name ) ) ); ?>"><?php gor_the_icon( 'whatsapp' ); ?> <?php esc_html_e( 'Ask on WhatsApp', 'guruswad' ); ?></a>
		<div class="trust-row">
			<div><?php gor_the_icon( 'shield' ); ?><?php esc_html_e( 'Secure UPI, card & net-banking', 'guruswad' ); ?></div>
			<div><?php gor_the_icon( 'receipt' ); ?><?php esc_html_e( 'GST invoice with every order', 'guruswad' ); ?></div>
			<div><?php gor_the_icon( 'truck' ); ?><?php esc_html_e( 'Tracked delivery across India', 'guruswad' ); ?></div>
		</div>
		<?php
	}
);

/**
 * "From the field" provenance strip.
 */
add_action(
	'woocommerce_after_single_product_summary',
	function () {
		global $product;
		$type   = get_post_meta( $product->get_id(), '_gor_millet_type', true );
		$origin = get_post_meta( $product->get_id(), '_gor_origin', true );
		?>
		<section class="from-the-field section--dark" style="padding:clamp(2rem,5vw,4rem)">
			<div class="split">
				<div>
					<span class="eyebrow"><?php esc_html_e( 'From the field', 'guruswad' ); ?></span>
					<h2 class="h-md" style="color:var(--cream)"><?php esc_html_e( 'Every grain has a name, a field and a family.', 'guruswad' ); ?></h2>
					<p class="lead"><?php echo esc_html( trim( ( $type ? $type . ' · ' : '' ) . ( $origin ? $origin : __( 'Sourced directly from our Farmer Producer Company network.', 'guruswad' ) ) ) ); ?></p>
				</div>
				<div style="text-align:right"><a class="btn btn--gold" href="<?php echo esc_url( gor_page_url( 'farmers-fpo-network' ) ); ?>"><?php esc_html_e( 'Meet our farmers', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></div>
			</div>
		</section>
		<?php
	},
	12
);

/**
 * Extra tabs: Ingredients & Nutrition, Specifications & Packaging.
 */
add_filter(
	'woocommerce_product_tabs',
	function ( $tabs ) {
		global $product;
		$id = $product->get_id();
		if ( get_post_meta( $id, '_gor_ingredients', true ) || get_post_meta( $id, '_gor_nutrition', true ) ) {
			$tabs['gor_nutrition'] = array(
				'title'    => __( 'Ingredients & Nutrition', 'guruswad' ),
				'priority' => 15,
				'callback' => function () use ( $id ) {
					$ing = get_post_meta( $id, '_gor_ingredients', true );
					if ( $ing ) {
						echo '<h3>' . esc_html__( 'Ingredients', 'guruswad' ) . '</h3><p>' . esc_html( $ing ) . '</p>';
					}
					$nut = get_post_meta( $id, '_gor_nutrition', true );
					if ( $nut ) {
						echo '<h3>' . esc_html__( 'Nutrition', 'guruswad' ) . '</h3><table class="shop_attributes"><tbody>';
						foreach ( preg_split( '/\r?\n/', $nut ) as $row ) {
							$c = array_map( 'trim', explode( '|', $row ) );
							if ( count( $c ) >= 2 ) {
								echo '<tr><th>' . esc_html( $c[0] ) . '</th><td>' . esc_html( $c[1] ) . '</td></tr>';
							}
						}
						echo '</tbody></table>';
					}
				},
			);
		}
		$tabs['gor_specs'] = array(
			'title'    => __( 'Specifications & Packaging', 'guruswad' ),
			'priority' => 25,
			'callback' => function () use ( $id ) {
				$rows = array( '_gor_millet_type', '_gor_origin', '_gor_pack_sizes', '_gor_shelf_life', '_gor_storage', '_gor_hs_code', '_gor_moq' );
				$f    = gor_product_fields();
				echo '<table class="shop_attributes"><tbody>';
				foreach ( $rows as $k ) {
					$v = get_post_meta( $id, $k, true );
					if ( $v ) {
						echo '<tr><th>' . esc_html( $f[ $k ][0] ) . '</th><td>' . esc_html( $v ) . '</td></tr>';
					}
				}
				echo '</tbody></table>';
			},
		);
		return $tabs;
	}
);

/**
 * "Inclusive of all taxes" note.
 */
add_filter(
	'woocommerce_get_price_suffix',
	function ( $suffix ) {
		return ( $suffix || ! wc_prices_include_tax() ) ? $suffix : ' <small>' . esc_html__( 'incl. GST', 'guruswad' ) . '</small>';
	}
);

/* ------------------------------------------------ Cart */

/**
 * Keep header cart count live.
 */
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $frags ) {
		$frags['span.cart-count'] = '<span class="cart-count">' . (int) WC()->cart->get_cart_contents_count() . '</span>';
		ob_start();
		woocommerce_mini_cart();
		$frags['div.widget_shopping_cart_content'] = '<div class="widget_shopping_cart_content">' . ob_get_clean() . '</div>';
		return $frags;
	}
);

/**
 * Slide-out mini cart markup (printed in footer).
 */
function gor_mini_cart() {
	?>
	<div class="mini-cart-scrim" data-cart-close></div>
	<aside class="mini-cart" aria-label="<?php esc_attr_e( 'Your cart', 'guruswad' ); ?>">
		<div class="mini-cart__head"><h3><?php esc_html_e( 'Your cart', 'guruswad' ); ?></h3><button class="icon-btn" data-cart-close aria-label="<?php esc_attr_e( 'Close cart', 'guruswad' ); ?>"><?php gor_the_icon( 'close' ); ?></button></div>
		<div class="mini-cart__body"><div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div></div>
	</aside>
	<?php
}

/* ------------------------------------------------ Checkout: optional GSTIN for B2B invoices */

add_filter(
	'woocommerce_billing_fields',
	function ( $f ) {
		$f['billing_gstin'] = array(
			'label'       => __( 'GSTIN (for business invoice, optional)', 'guruswad' ),
			'required'    => false,
			'class'       => array( 'form-row-wide' ),
			'priority'    => 35,
			'placeholder' => '27ABCDE1234F1Z5',
		);
		return $f;
	}
);
add_action(
	'woocommerce_after_checkout_validation',
	function ( $data, $errors ) {
		if ( ! empty( $data['billing_gstin'] ) && ! preg_match( '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/', strtoupper( $data['billing_gstin'] ) ) ) {
			$errors->add( 'gstin', __( 'Please enter a valid 15-character GSTIN.', 'guruswad' ) );
		}
	},
	10,
	2
);
add_action(
	'woocommerce_admin_order_data_after_billing_address',
	function ( $order ) {
		$g = $order->get_meta( '_billing_gstin' );
		if ( $g ) {
			echo '<p><strong>GSTIN:</strong> ' . esc_html( $g ) . '</p>';
		}
	}
);
add_filter(
	'woocommerce_email_order_meta_fields',
	function ( $fields, $sent_to_admin, $order ) {
		$g = $order->get_meta( '_billing_gstin' );
		if ( $g ) {
			$fields['gstin'] = array( 'label' => 'GSTIN', 'value' => $g );
		}
		return $fields;
	},
	10,
	3
);
