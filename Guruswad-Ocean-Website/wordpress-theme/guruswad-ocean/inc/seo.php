<?php
/**
 * Search and social metadata.
 *
 * Covers WEBSITE DEVELOPMENT.docx §16: on-page SEO, social sharing, Google
 * Analytics and Search Console. Deliberately small — if the company later
 * installs Yoast or Rank Math, gor_seo_disabled() stands down so the two do
 * not emit competing tags.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stand aside when a dedicated SEO plugin is active.
 *
 * @return bool
 */
function gor_seo_disabled() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' );
}

/**
 * The description for whatever is being viewed.
 *
 * @return string
 */
function gor_seo_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		$d    = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( $post->post_content );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$d = term_description();
	} else {
		$d = get_bloginfo( 'description' );
	}
	$d = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $d ) ) );
	if ( '' === $d ) {
		$d = __( 'Guruswad Ocean Private Limited — a vertically integrated millet company connecting Maharashtra\'s farmers to tables across the world.', 'guruswad' );
	}
	// 155 characters is where Google starts truncating; cut on a word.
	if ( mb_strlen( $d ) > 155 ) {
		$d = rtrim( mb_substr( $d, 0, 155 ), " \t\n\r\0\x0B,.;:" );
		$d = mb_substr( $d, 0, max( 1, mb_strrpos( $d, ' ' ) ) ) . '…';
	}
	return $d;
}

/**
 * The sharing image for whatever is being viewed.
 *
 * @return string URL, possibly empty.
 */
function gor_seo_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $src ) {
			return $src[0];
		}
	}
	$poster = gor_opt( 'hero_poster' );
	if ( $poster ) {
		return $poster;
	}
	return GOR_URI . '/assets/img/photo/hero-soil-to-signature.jpg';
}

/**
 * Canonical URL for the current view.
 *
 * @return string
 */
function gor_seo_canonical() {
	if ( is_singular() ) {
		return get_permalink();
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$l = get_term_link( get_queried_object() );
		return is_wp_error( $l ) ? home_url( '/' ) : $l;
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return wc_get_page_permalink( 'shop' );
	}
	if ( is_home() || is_front_page() ) {
		return home_url( '/' );
	}
	return home_url( add_query_arg( array() ) );
}

/**
 * Open Graph, Twitter cards and canonical.
 */
function gor_seo_meta() {
	if ( gor_seo_disabled() ) {
		return;
	}
	$title = wp_get_document_title();
	$desc  = gor_seo_description();
	$img   = gor_seo_image();
	$url   = gor_seo_canonical();
	$type  = is_singular() && ! is_front_page() ? 'article' : 'website';
	if ( function_exists( 'is_product' ) && is_product() ) {
		$type = 'product';
	}

	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( 'mr' === gor_current_lang() ? 'mr_IN' : 'en_IN' ) );
	if ( $img ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $img ) );
		printf( '<meta property="og:image:alt" content="%s">' . "\n", esc_attr( $title ) );
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", $img ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $desc ) );
	if ( $img ) {
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $img ) );
	}
}
add_action( 'wp_head', 'gor_seo_meta', 2 );

/**
 * Organization / Product / Breadcrumb structured data.
 */
function gor_seo_jsonld() {
	if ( gor_seo_disabled() ) {
		return;
	}
	$graph = array();

	$org = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#organization' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'logo'  => GOR_URI . '/assets/img/logo/milletrush-color.svg',
	);
	$addr = gor_opt( 'address' );
	if ( $addr ) {
		$org['address'] = array( '@type' => 'PostalAddress', 'streetAddress' => $addr, 'addressCountry' => 'IN' );
	}
	$phone = gor_opt( 'phone' );
	$email = gor_opt( 'email' );
	if ( $phone || $email ) {
		$org['contactPoint'] = array_filter(
			array(
				'@type'       => 'ContactPoint',
				'contactType' => 'sales',
				'telephone'   => $phone,
				'email'       => $email,
			)
		);
	}
	$social = array_filter(
		array(
			gor_opt( 'social_facebook' ),
			gor_opt( 'social_instagram' ),
			gor_opt( 'social_linkedin' ),
			gor_opt( 'social_x' ),
			gor_opt( 'social_youtube' ),
		)
	);
	if ( $social ) {
		$org['sameAs'] = array_values( $social );
	}
	$graph[] = $org;

	$graph[] = array(
		'@type'           => 'WebSite',
		'@id'             => home_url( '/#website' ),
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name' ),
		'publisher'       => array( '@id' => home_url( '/#organization' ) ),
		'inLanguage'      => 'mr' === gor_current_lang() ? 'mr-IN' : 'en-IN',
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	if ( function_exists( 'is_product' ) && is_product() ) {
		global $product;
		if ( $product ) {
			$p = array(
				'@type'       => 'Product',
				'name'        => $product->get_name(),
				'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
				'sku'         => $product->get_sku(),
				'brand'       => array( '@type' => 'Brand', 'name' => 'MilletRush' ),
				'url'         => get_permalink( $product->get_id() ),
			);
			$img = wp_get_attachment_image_src( $product->get_image_id(), 'full' );
			if ( $img ) {
				$p['image'] = $img[0];
			}
			// Only claim an offer when there is a real price behind it.
			if ( '' !== $product->get_price() ) {
				$p['offers'] = array(
					'@type'         => 'Offer',
					'price'         => $product->get_price(),
					'priceCurrency' => get_woocommerce_currency(),
					'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
					'url'           => get_permalink( $product->get_id() ),
				);
			}
			$graph[] = $p;
		}
	}

	if ( is_singular() && ! is_front_page() ) {
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Home', 'guruswad' ), 'item' => home_url( '/' ) ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => wp_strip_all_tags( get_the_title() ) ),
			),
		);
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode(
			array( '@context' => 'https://schema.org', '@graph' => $graph ),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		)
		. "</script>\n";
}
add_action( 'wp_head', 'gor_seo_jsonld', 3 );

/**
 * Google Analytics (GA4) and Search Console verification.
 *
 * Both IDs are entered by the company in the Customizer; nothing is hard-coded,
 * so the measurement property stays under Guruswad Ocean's own account (§19).
 */
function gor_seo_analytics() {
	$gsc = gor_opt( 'gsc_verify' );
	if ( $gsc ) {
		printf( '<meta name="google-site-verification" content="%s">' . "\n", esc_attr( $gsc ) );
	}
	$ga = gor_opt( 'ga4_id' );
	if ( ! $ga || ! preg_match( '/^G-[A-Z0-9]{4,}$/i', $ga ) ) {
		return;
	}
	printf( '<script async src="https://www.googletagmanager.com/gtag/js?id=%s"></script>' . "\n", rawurlencode( $ga ) );
	printf(
		'<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config",%s);</script>' . "\n",
		wp_json_encode( $ga )
	);
}
add_action( 'wp_head', 'gor_seo_analytics', 4 );

/**
 * WordPress core has shipped wp-sitemap.xml since 5.5. Keep it, but drop the
 * entries that are noise for a corporate site.
 */
add_filter( 'wp_sitemaps_post_types', function ( $types ) {
	unset( $types['attachment'] );
	return $types;
} );
add_filter( 'wp_sitemaps_taxonomies', function ( $taxes ) {
	unset( $taxes['post_tag'] );
	return $taxes;
} );

/**
 * Point robots.txt at the sitemap and keep crawlers out of checkout.
 *
 * @param string $txt Existing robots.txt body.
 * @return string
 */
add_filter( 'robots_txt', function ( $txt ) {
	$add = "\nDisallow: /cart/\nDisallow: /checkout/\nDisallow: /my-account/\nDisallow: /*?add-to-cart=\n";
	$add .= "\nSitemap: " . home_url( '/wp-sitemap.xml' ) . "\n";
	return $txt . $add;
}, 10, 1 );
