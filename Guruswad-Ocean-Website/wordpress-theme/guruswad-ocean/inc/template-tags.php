<?php
/**
 * Template helpers.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Link to a page by slug (falls back to /slug/).
 *
 * @param string $slug Page slug.
 * @return string
 */
function gor_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		$id = function_exists( 'pll_get_post' ) ? ( pll_get_post( $page->ID ) ? pll_get_post( $page->ID ) : $page->ID ) : $page->ID;
		return get_permalink( $id );
	}
	return home_url( '/' . $slug . '/' );
}

/**
 * Shop URL.
 *
 * @return string
 */
function gor_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'shop' );
	}
	return gor_page_url( 'shop' );
}

/**
 * Contact / Enquiry Hub URL with a preselected type.
 *
 * @param string $type    Enquiry type.
 * @param string $product Optional product name.
 * @return string
 */
function gor_enquiry_url( $type = 'general', $product = '' ) {
	$args = array( 'type' => $type );
	if ( $product ) {
		$args['product'] = $product;
	}
	return add_query_arg( $args, gor_page_url( 'contact-us' ) ) . '#enquiry';
}

/**
 * WhatsApp click-to-chat link with a prefilled message.
 *
 * @param string $msg Message.
 * @return string
 */
function gor_whatsapp_url( $msg = '' ) {
	$num = preg_replace( '/\D/', '', (string) gor_opt( 'whatsapp' ) );
	if ( ! $msg ) {
		$msg = __( 'Hello Guruswad Ocean, I would like to know more about your products.', 'guruswad' );
	}
	return 'https://wa.me/' . $num . '?text=' . rawurlencode( $msg );
}

/**
 * Default menu (used until the client builds menus in Appearance → Menus).
 *
 * @return array label => url
 */
function gor_default_menu() {
	return array(
		__( 'About', 'guruswad' )         => gor_page_url( 'about-us' ),
		__( 'Products', 'guruswad' )      => gor_shop_url(),
		__( 'Manufacturing', 'guruswad' ) => gor_page_url( 'manufacturing' ),
		__( 'Import & Export', 'guruswad' ) => gor_page_url( 'import-export' ),
		__( 'Our Brands', 'guruswad' )    => get_post_type_archive_link( 'gor_brand' ) ? get_post_type_archive_link( 'gor_brand' ) : home_url( '/brands/' ),
		__( 'Farmers & FPO', 'guruswad' ) => gor_page_url( 'farmers-fpo-network' ),
	);
}

/**
 * Full mega menu default.
 *
 * @return array
 */
function gor_default_mega_menu() {
	return array_merge(
		array( __( 'Home', 'guruswad' ) => home_url( '/' ) ),
		gor_default_menu(),
		array(
			__( 'Investment & Partnership', 'guruswad' ) => gor_page_url( 'investment-partnership' ),
			__( 'News & Updates', 'guruswad' )           => gor_page_url( 'news' ),
			__( 'Contact Us', 'guruswad' )               => gor_page_url( 'contact-us' ),
		)
	);
}

/**
 * Render a menu location or the default fallback list.
 *
 * @param string $location Location.
 * @param array  $fallback Fallback items.
 * @param string $class    UL class.
 */
function gor_menu( $location, $fallback, $class = '' ) {
	if ( has_nav_menu( $location ) ) {
		wp_nav_menu( array( 'theme_location' => $location, 'container' => false, 'menu_class' => $class, 'depth' => 1 ) );
		return;
	}
	echo '<ul class="' . esc_attr( $class ) . '">';
	foreach ( $fallback as $label => $url ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Inner page hero.
 *
 * `variant` names a page-hero--* modifier, which is what carries the
 * photograph behind the heading (main.css, section 33.6). Without it every
 * inner page in the WordPress build rendered on the plain gradient while the
 * static preview showed the picture — the templates simply had no way to say
 * which photograph they wanted.
 *
 * @param array $a eyebrow, title, lead, art (bool), variant (string).
 */
function gor_page_hero( $a = array() ) {
	$a = wp_parse_args(
		$a,
		array(
			'eyebrow' => '',
			'title'   => get_the_title(),
			'lead'    => has_excerpt() ? get_the_excerpt() : '',
			'art'     => true,
			'variant' => '',
		)
	);
	$gor_hero_class = 'page-hero';
	if ( $a['variant'] ) {
		$gor_hero_class .= ' page-hero--' . sanitize_html_class( $a['variant'] );
	}
	?>
	<section class="<?php echo esc_attr( $gor_hero_class ); ?>">
		<?php if ( $a['art'] ) : ?><div class="page-hero__art" data-speed="-.15"><?php gor_millet_art(); ?></div><?php endif; ?>
		<div class="container">
			<div class="breadcrumbs" data-reveal="fade"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'guruswad' ); ?></a> &nbsp;/&nbsp; <?php echo esc_html( wp_strip_all_tags( $a['title'] ) ); ?></div>
			<?php if ( $a['eyebrow'] ) : ?><span class="eyebrow" data-reveal><?php echo esc_html( $a['eyebrow'] ); ?></span><?php endif; ?>
			<h1 data-split><?php echo wp_kses( $a['title'], array( 'em' => array(), 'br' => array() ) ); ?></h1>
			<?php if ( $a['lead'] ) : ?><p class="lead" data-reveal><?php echo esc_html( $a['lead'] ); ?></p><?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Sample products shown until WooCommerce has real products.
 * Pack visuals are CSS placeholders; names/sizes are for layout only.
 *
 * @return array
 */
function gor_demo_products() {
	return array(
		// No 'Bestseller' or 'New' tag: client letter §5 asks that only what can
		// be evidenced is shown, and a range that is not yet retailing has no
		// bestseller.
		array( 'name' => __( 'Sorghum Puffs — Classic Salted', 'guruswad' ), 'sub' => __( 'Jowar · Puffed snack', 'guruswad' ), 'tag' => '', 'color' => '#e39a22', 'light' => true ),
		array( 'name' => __( 'Sorghum Puffs — Peri-Peri', 'guruswad' ), 'sub' => __( 'Jowar · Puffed snack', 'guruswad' ), 'tag' => '', 'color' => '#631c0a', 'light' => false ),
		array( 'name' => __( 'Sorghum Puffs — Desi Masala', 'guruswad' ), 'sub' => __( 'Jowar · Puffed snack', 'guruswad' ), 'tag' => '', 'color' => '#689600', 'light' => false ),
		array( 'name' => __( 'Pearl Millet Flour', 'guruswad' ), 'sub' => __( 'Bajra · Stone-ground', 'guruswad' ), 'tag' => '', 'color' => '#c68d4f', 'light' => true ),
	);
}

/**
 * Render a product card (WooCommerce product or demo array).
 *
 * @param WC_Product|array $p Product.
 */
function gor_product_card( $p ) {
	if ( is_object( $p ) ) {
		$url   = get_permalink( $p->get_id() );
		$name  = $p->get_name();
		$sub   = wp_strip_all_tags( wc_get_product_category_list( $p->get_id(), ', ' ) );
		$price = $p->get_price_html();
		$tag   = $p->is_on_sale() ? __( 'Offer', 'guruswad' ) : ( $p->is_featured() ? __( 'Signature', 'guruswad' ) : '' );
		$img   = $p->get_image( 'gor-card' );
		$add   = $p->is_purchasable() && $p->is_in_stock() && $p->is_type( 'simple' );
		?>
		<article class="p-card" data-reveal>
			<div class="p-card__mediawrap">
				<a class="p-card__media" href="<?php echo esc_url( $url ); ?>" data-cursor="<?php esc_attr_e( 'View', 'guruswad' ); ?>">
					<?php if ( $tag ) : ?><span class="p-card__tag"><?php echo esc_html( $tag ); ?></span><?php endif; ?>
					<?php echo $img; // phpcs:ignore ?>
				</a>
				<?php if ( $add ) : ?>
					<a href="<?php echo esc_url( $p->add_to_cart_url() ); ?>" data-quantity="1" data-product_id="<?php echo esc_attr( $p->get_id() ); ?>" class="btn btn--gold p-card__quick add_to_cart_button ajax_add_to_cart" rel="nofollow"><?php esc_html_e( 'Quick add', 'guruswad' ); ?></a>
				<?php endif; ?>
			</div>
			<div class="p-card__meta">
				<div><h3><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $name ); ?></a></h3><div class="p-card__sub"><?php echo esc_html( $sub ); ?></div></div>
				<div class="p-card__price"><?php echo wp_kses_post( $price ); ?></div>
			</div>
		</article>
		<?php
		return;
	}
	?>
	<article class="p-card" data-reveal>
		<div class="p-card__mediawrap">
			<a class="p-card__media" href="<?php echo esc_url( gor_shop_url() ); ?>" data-cursor="<?php esc_attr_e( 'View', 'guruswad' ); ?>">
				<?php if ( $p['tag'] ) : ?><span class="p-card__tag"><?php echo esc_html( $p['tag'] ); ?></span><?php endif; ?>
				<span class="pack<?php echo $p['light'] ? ' pack--light' : ''; ?>" style="--pc: <?php echo esc_attr( $p['color'] ); ?>">
					<img src="<?php echo esc_url( GOR_URI . '/assets/img/logo/' . ( $p['light'] ? 'milletrush-wordmark.svg' : 'milletrush-white.svg' ) ); ?>" alt="" width="900" height="454">
					<span><?php echo esc_html( $p['sub'] ); ?></span>
				</span>
			</a>
			<a href="<?php echo esc_url( gor_shop_url() ); ?>" class="btn btn--gold p-card__quick"><?php esc_html_e( 'Shop now', 'guruswad' ); ?></a>
		</div>
		<div class="p-card__meta">
			<div><h3><a href="<?php echo esc_url( gor_shop_url() ); ?>"><?php echo esc_html( $p['name'] ); ?></a></h3><div class="p-card__sub"><?php echo esc_html( $p['sub'] ); ?></div></div>
		</div>
	</article>
	<?php
}

/**
 * Home product rail: featured WooCommerce products, else latest, else demo.
 */
function gor_home_products() {
	if ( function_exists( 'wc_get_products' ) ) {
		$items = wc_get_products( array( 'limit' => 4, 'status' => 'publish', 'featured' => true ) );
		if ( count( $items ) < 4 ) {
			$items = wc_get_products( array( 'limit' => 4, 'status' => 'publish', 'orderby' => 'date' ) );
		}
		if ( $items ) {
			foreach ( $items as $p ) {
				gor_product_card( $p );
			}
			return;
		}
	}
	foreach ( gor_demo_products() as $p ) {
		gor_product_card( $p );
	}
}

/**
 * Art panel placeholder: uses an image if given, else an elegant illustrated panel.
 *
 * @param string $icon    Icon key.
 * @param string $caption Caption.
 * @param int    $img_id  Attachment ID.
 * @param string $variant '' | 'cream'.
 */
function gor_art_panel( $icon, $caption = '', $img_id = 0, $variant = '' ) {
	echo '<div class="art-panel' . ( $variant ? ' art-panel--' . esc_attr( $variant ) : '' ) . '" data-clip><div style="position:absolute;inset:0;display:grid;place-items:center">';
	if ( $img_id ) {
		echo wp_get_attachment_image( $img_id, 'gor-panel', false, array( 'style' => 'width:100%;height:100%;object-fit:cover' ) );
	} else {
		gor_the_icon( $icon );
	}
	echo '</div>';
	if ( $caption ) {
		echo '<span class="art-panel__caption">' . esc_html( $caption ) . '</span>';
	}
	echo '</div>';
}

/**
 * CTA band (Invest / Partner / Enquire).
 */
function gor_cta_band() {
	get_template_part( 'template-parts/cta-band' );
}
