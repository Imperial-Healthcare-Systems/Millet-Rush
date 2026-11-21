<?php
/**
 * Content model: Brands, Stories, Team, Certifications, Markets, Enquiries.
 * Adding a new brand, story or market needs no code — the client adds an entry in the admin.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register post types and taxonomies.
 */
function gor_register_content_model() {
	$common = array( 'public' => true, 'show_in_rest' => true, 'has_archive' => true, 'menu_position' => 21 );

	register_post_type(
		'gor_brand',
		array_merge(
			$common,
			array(
				'labels'      => array( 'name' => __( 'Brands', 'guruswad' ), 'singular_name' => __( 'Brand', 'guruswad' ), 'add_new_item' => __( 'Add new brand', 'guruswad' ) ),
				'menu_icon'   => 'dashicons-awards',
				'rewrite'     => array( 'slug' => 'brands' ),
				'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
			)
		)
	);

	register_post_type(
		'gor_story',
		array_merge(
			$common,
			array(
				'labels'    => array( 'name' => __( 'Farmer Stories', 'guruswad' ), 'singular_name' => __( 'Story', 'guruswad' ), 'add_new_item' => __( 'Add new story', 'guruswad' ) ),
				'menu_icon' => 'dashicons-groups',
				'rewrite'   => array( 'slug' => 'stories' ),
				'supports'  => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			)
		)
	);

	register_post_type(
		'gor_team',
		array(
			'labels'       => array( 'name' => __( 'Leadership', 'guruswad' ), 'singular_name' => __( 'Leader', 'guruswad' ), 'add_new_item' => __( 'Add leader', 'guruswad' ) ),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-businessperson',
			'supports'     => array( 'title', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'gor_cert',
		array(
			'labels'       => array( 'name' => __( 'Certifications', 'guruswad' ), 'singular_name' => __( 'Certification', 'guruswad' ) ),
			'public'       => false,
			'show_ui'      => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-yes-alt',
			'supports'     => array( 'title', 'excerpt', 'thumbnail' ),
		)
	);

	register_taxonomy(
		'gor_market',
		array( 'product', 'gor_story', 'post' ),
		array(
			'labels'            => array( 'name' => __( 'Export Markets', 'guruswad' ), 'singular_name' => __( 'Market', 'guruswad' ) ),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'markets' ),
		)
	);

	// Brand display fields (shown in REST so the block editor sidebar "Custom fields" panel can edit them).
	foreach ( array( 'gor_tagline', 'gor_accent', 'gor_logo_url', 'gor_product_cat' ) as $key ) {
		register_post_meta( 'gor_brand', $key, array( 'show_in_rest' => true, 'single' => true, 'type' => 'string' ) );
	}
	register_post_meta( 'gor_team', 'gor_role', array( 'show_in_rest' => true, 'single' => true, 'type' => 'string' ) );
}
add_action( 'init', 'gor_register_content_model' );

/**
 * Simple meta box for Brand & Leader fields (classic UI for non-technical editors).
 */
function gor_meta_boxes() {
	add_meta_box( 'gor_brand_fields', __( 'Brand details', 'guruswad' ), 'gor_brand_box', 'gor_brand', 'side' );
	add_meta_box( 'gor_team_fields', __( 'Role', 'guruswad' ), 'gor_team_box', 'gor_team', 'side' );
}
add_action( 'add_meta_boxes', 'gor_meta_boxes' );

/**
 * Brand box.
 *
 * @param WP_Post $post Post.
 */
function gor_brand_box( $post ) {
	wp_nonce_field( 'gor_meta', 'gor_meta_nonce' );
	$f = array(
		'gor_tagline'     => __( 'Tagline', 'guruswad' ),
		'gor_accent'      => __( 'Accent colour (hex)', 'guruswad' ),
		'gor_logo_url'    => __( 'Logo URL (transparent PNG/SVG)', 'guruswad' ),
		'gor_product_cat' => __( 'WooCommerce category slug for this brand', 'guruswad' ),
	);
	foreach ( $f as $k => $label ) {
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label><br><input type="text" class="widefat" id="%1$s" name="%1$s" value="%3$s"></p>', esc_attr( $k ), esc_html( $label ), esc_attr( get_post_meta( $post->ID, $k, true ) ) );
	}
}

/**
 * Team box.
 *
 * @param WP_Post $post Post.
 */
function gor_team_box( $post ) {
	wp_nonce_field( 'gor_meta', 'gor_meta_nonce' );
	printf( '<input type="text" class="widefat" name="gor_role" value="%s" placeholder="%s">', esc_attr( get_post_meta( $post->ID, 'gor_role', true ) ), esc_attr__( 'e.g. Managing Director', 'guruswad' ) );
}

/**
 * Save meta boxes.
 *
 * @param int $post_id Post ID.
 */
function gor_save_meta( $post_id ) {
	if ( ! isset( $_POST['gor_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gor_meta_nonce'] ) ), 'gor_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'gor_tagline', 'gor_accent', 'gor_logo_url', 'gor_product_cat', 'gor_role' ) as $k ) {
		if ( isset( $_POST[ $k ] ) ) {
			$val = 'gor_logo_url' === $k ? esc_url_raw( wp_unslash( $_POST[ $k ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $k ] ) );
			update_post_meta( $post_id, $k, $val );
		}
	}
}
add_action( 'save_post', 'gor_save_meta' );

/**
 * One-time setup on theme activation: create core pages, a MilletRush brand entry, and set the front page.
 */
function gor_on_activate() {
	if ( get_option( 'gor_seeded' ) ) {
		return;
	}
	gor_register_content_model();
	$pages = array(
		'about-us'               => array( __( 'About Us', 'guruswad' ), 'page-templates/template-about.php' ),
		'manufacturing'          => array( __( 'Manufacturing & Processing', 'guruswad' ), 'page-templates/template-manufacturing.php' ),
		'import-export'          => array( __( 'Import & Export', 'guruswad' ), 'page-templates/template-export.php' ),
		'farmers-fpo-network'    => array( __( 'Farmers & FPO Network', 'guruswad' ), 'page-templates/template-farmers.php' ),
		'investment-partnership' => array( __( 'Investment & Partnership', 'guruswad' ), 'page-templates/template-investment.php' ),
		'contact-us'             => array( __( 'Contact Us', 'guruswad' ), 'page-templates/template-contact.php' ),
		'news'                   => array( __( 'News & Updates', 'guruswad' ), '' ),
		'home'                   => array( __( 'Home', 'guruswad' ), '' ),
	);
	foreach ( $pages as $slug => $p ) {
		if ( get_page_by_path( $slug ) ) {
			continue;
		}
		$id = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => $p[0], 'post_name' => $slug ) );
		if ( $id && $p[1] ) {
			update_post_meta( $id, '_wp_page_template', $p[1] );
		}
	}
	$home = get_page_by_path( 'home' );
	$news = get_page_by_path( 'news' );
	if ( $home && $news ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
		update_option( 'page_for_posts', $news->ID );
	}
	if ( ! get_page_by_path( 'milletrush', OBJECT, 'gor_brand' ) ) {
		$b = wp_insert_post(
			array(
				'post_type'    => 'gor_brand',
				'post_status'  => 'publish',
				'post_title'   => 'MilletRush',
				'post_name'    => 'milletrush',
				'post_excerpt' => __( 'A millet-based food brand focused on sorghum puffs and other millet snacks — everyday goodness, crafted with care.', 'guruswad' ),
				'post_content' => '',
			)
		);
		update_post_meta( $b, 'gor_tagline', 'Everyday Goodness of Millets' );
		update_post_meta( $b, 'gor_accent', '#e39a22' );
		update_post_meta( $b, 'gor_logo_url', GOR_URI . '/assets/img/logo/milletrush-color.svg' );
	}
	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules();
	update_option( 'gor_seeded', 1 );
}
add_action( 'after_switch_theme', 'gor_on_activate' );
