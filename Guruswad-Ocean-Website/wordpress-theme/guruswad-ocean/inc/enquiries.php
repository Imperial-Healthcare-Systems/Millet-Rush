<?php
/**
 * Enquiry engine: 7 enquiry types + multi-step RFQ.
 * Stores every submission in the admin (Enquiries menu), emails the routed address,
 * sends an acknowledgement to the sender, and exports to CSV.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enquiry types.
 *
 * @return array key => label
 */
function gor_enquiry_types() {
	return array(
		'general'  => __( 'General enquiry', 'guruswad' ),
		'product'  => __( 'Product enquiry', 'guruswad' ),
		'export'   => __( 'Export / Request for Quotation', 'guruswad' ),
		'bulk'     => __( 'Bulk order (India)', 'guruswad' ),
		'import'   => __( 'Import enquiry', 'guruswad' ),
		'investor' => __( 'Investor enquiry', 'guruswad' ),
		'partner'  => __( 'Business partnership', 'guruswad' ),
		'cfc'      => __( 'Common Facility Centre (CFC)', 'guruswad' ),
	);
}

/**
 * Field labels (used in admin, email and CSV).
 *
 * @return array
 */
function gor_enquiry_fields() {
	return array(
		'name'         => __( 'Name', 'guruswad' ),
		'company'      => __( 'Company', 'guruswad' ),
		'email'        => __( 'Email', 'guruswad' ),
		'phone'        => __( 'Phone / WhatsApp', 'guruswad' ),
		'country'      => __( 'Country', 'guruswad' ),
		'city'         => __( 'City', 'guruswad' ),
		'product'      => __( 'Product(s)', 'guruswad' ),
		'quantity'     => __( 'Quantity', 'guruswad' ),
		'packaging'    => __( 'Packaging', 'guruswad' ),
		'incoterm'     => __( 'Incoterm', 'guruswad' ),
		'port'         => __( 'Destination port', 'guruswad' ),
		'timeline'     => __( 'Timeline', 'guruswad' ),
		'investment'   => __( 'Investment range', 'guruswad' ),
		'organisation' => __( 'Organisation type', 'guruswad' ),
		'partnership'  => __( 'Partnership model', 'guruswad' ),
		'message'      => __( 'Message', 'guruswad' ),
		'lang'         => __( 'Language', 'guruswad' ),
		'source'       => __( 'Source page', 'guruswad' ),
		'utm'          => __( 'Campaign', 'guruswad' ),
	);
}

/**
 * Register the private Enquiry post type.
 */
function gor_register_enquiry_cpt() {
	register_post_type(
		'gor_enquiry',
		array(
			'labels'          => array( 'name' => __( 'Enquiries', 'guruswad' ), 'singular_name' => __( 'Enquiry', 'guruswad' ), 'menu_name' => __( 'Enquiries', 'guruswad' ) ),
			'public'          => false,
			'show_ui'         => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 25,
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
	register_taxonomy( 'gor_enquiry_type', 'gor_enquiry', array( 'label' => __( 'Type', 'guruswad' ), 'public' => false, 'show_ui' => true, 'show_admin_column' => true, 'hierarchical' => true ) );
}
add_action( 'init', 'gor_register_enquiry_cpt' );

/**
 * Handle submissions (logged-in and guests).
 */
function gor_handle_enquiry() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( array( 'sent', 'err' ), $back );

	if ( ! isset( $_POST['gor_enquiry_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gor_enquiry_nonce'] ) ), 'gor_enquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'nonce', $back ) . '#enquiry' );
		exit;
	}
	// Honeypot + minimum fill time (bots), then any filter-added check such as
	// Turnstile. Rejections redirect as a success so a bot learns nothing.
	$gor_is_spam = ! empty( $_POST['website_url'] ) || ( isset( $_POST['ts'] ) && ( time() - (int) $_POST['ts'] ) < 3 );
	/** This filter lets inc/turnstile.php add verification without touching this handler. */
	$gor_is_spam = (bool) apply_filters( 'gor_enquiry_spam_check', $gor_is_spam );
	if ( $gor_is_spam ) {
		wp_safe_redirect( add_query_arg( 'sent', '1', $back ) . '#enquiry' );
		exit;
	}

	$types = gor_enquiry_types();
	$type  = isset( $_POST['enquiry_type'] ) ? sanitize_key( wp_unslash( $_POST['enquiry_type'] ) ) : 'general';
	if ( ! isset( $types[ $type ] ) ) {
		$type = 'general';
	}

	$data = array();
	foreach ( array_keys( gor_enquiry_fields() ) as $k ) {
		if ( isset( $_POST[ $k ] ) ) {
			$raw        = wp_unslash( $_POST[ $k ] );
			$data[ $k ] = is_array( $raw ) ? implode( ', ', array_map( 'sanitize_text_field', $raw ) ) : ( 'message' === $k ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw ) );
		}
	}
	$data['email'] = isset( $data['email'] ) ? sanitize_email( $data['email'] ) : '';

	if ( empty( $data['name'] ) || ! is_email( $data['email'] ) || empty( $_POST['consent'] ) ) {
		wp_safe_redirect( add_query_arg( 'err', 'fields', $back ) . '#enquiry' );
		exit;
	}

	$id = wp_insert_post(
		array(
			'post_type'   => 'gor_enquiry',
			'post_status' => 'private',
			'post_title'  => sprintf( '%s — %s%s', $types[ $type ], $data['name'], ! empty( $data['company'] ) ? ' (' . $data['company'] . ')' : '' ),
		)
	);
	if ( $id && ! is_wp_error( $id ) ) {
		foreach ( $data as $k => $v ) {
			update_post_meta( $id, '_gor_' . $k, $v );
		}
		update_post_meta( $id, '_gor_status', 'new' );
		wp_set_object_terms( $id, $types[ $type ], 'gor_enquiry_type' );
	}

	// Route to the configured inbox.
	$to = gor_opt( 'mail_' . $type );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$lines = array();
	foreach ( gor_enquiry_fields() as $k => $label ) {
		if ( ! empty( $data[ $k ] ) ) {
			$lines[] = $label . ': ' . $data[ $k ];
		}
	}
	$lines[] = '';
	$lines[] = admin_url( 'post.php?post=' . $id . '&action=edit' );
	wp_mail( $to, '[' . get_bloginfo( 'name' ) . '] ' . $types[ $type ] . ' — ' . $data['name'], implode( "\n", $lines ), array( 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>' ) );

	// Acknowledgement.
	wp_mail(
		$data['email'],
		sprintf( __( 'Thank you for contacting %s', 'guruswad' ), gor_opt( 'company_name' ) ),
		sprintf( __( "Dear %1\$s,\n\nThank you for your %2\$s. Our team will respond within one working day.\n\nWarm regards,\n%3\$s", 'guruswad' ), $data['name'], strtolower( $types[ $type ] ), gor_opt( 'company_name' ) )
	);

	wp_safe_redirect( add_query_arg( 'sent', '1', $back ) . '#enquiry' );
	exit;
}
add_action( 'admin_post_nopriv_gor_enquiry', 'gor_handle_enquiry' );
add_action( 'admin_post_gor_enquiry', 'gor_handle_enquiry' );

/* ---------------------------------------------------------------- Admin UI */

/**
 * Columns.
 */
add_filter(
	'manage_gor_enquiry_posts_columns',
	function ( $cols ) {
		return array(
			'cb'                         => $cols['cb'],
			'title'                      => __( 'Enquiry', 'guruswad' ),
			'taxonomy-gor_enquiry_type'  => __( 'Type', 'guruswad' ),
			'gor_email'                  => __( 'Email', 'guruswad' ),
			'gor_country'                => __( 'Country', 'guruswad' ),
			'gor_status'                 => __( 'Status', 'guruswad' ),
			'date'                       => __( 'Received', 'guruswad' ),
		);
	}
);
add_action(
	'manage_gor_enquiry_posts_custom_column',
	function ( $col, $id ) {
		if ( 'gor_email' === $col ) {
			$e = get_post_meta( $id, '_gor_email', true );
			echo '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>';
		} elseif ( 'gor_country' === $col ) {
			echo esc_html( get_post_meta( $id, '_gor_country', true ) );
		} elseif ( 'gor_status' === $col ) {
			$s = get_post_meta( $id, '_gor_status', true );
			$c = array( 'new' => '#e39a22', 'contacted' => '#689600', 'closed' => '#999' );
			echo '<span style="padding:2px 8px;border-radius:9px;color:#fff;background:' . esc_attr( isset( $c[ $s ] ) ? $c[ $s ] : '#999' ) . '">' . esc_html( ucfirst( $s ? $s : 'new' ) ) . '</span>';
		}
	},
	10,
	2
);

/**
 * Detail meta box.
 */
add_action(
	'add_meta_boxes_gor_enquiry',
	function () {
		add_meta_box(
			'gor_enquiry_detail',
			__( 'Enquiry details', 'guruswad' ),
			function ( $post ) {
				wp_nonce_field( 'gor_enq_status', 'gor_enq_status_nonce' );
				echo '<table class="widefat striped"><tbody>';
				foreach ( gor_enquiry_fields() as $k => $label ) {
					$v = get_post_meta( $post->ID, '_gor_' . $k, true );
					if ( '' !== $v ) {
						echo '<tr><th style="width:180px">' . esc_html( $label ) . '</th><td>' . nl2br( esc_html( $v ) ) . '</td></tr>';
					}
				}
				echo '</tbody></table>';
				$s = get_post_meta( $post->ID, '_gor_status', true );
				echo '<p><label><strong>' . esc_html__( 'Status', 'guruswad' ) . '</strong> <select name="gor_status">';
				foreach ( array( 'new', 'contacted', 'closed' ) as $o ) {
					echo '<option value="' . esc_attr( $o ) . '"' . selected( $s, $o, false ) . '>' . esc_html( ucfirst( $o ) ) . '</option>';
				}
				echo '</select></label></p>';
				$e = get_post_meta( $post->ID, '_gor_email', true );
				$p = preg_replace( '/\D/', '', (string) get_post_meta( $post->ID, '_gor_phone', true ) );
				echo '<p><a class="button button-primary" href="mailto:' . esc_attr( $e ) . '">' . esc_html__( 'Reply by email', 'guruswad' ) . '</a> ';
				if ( $p ) {
					echo '<a class="button" target="_blank" rel="noopener" href="https://wa.me/' . esc_attr( $p ) . '">' . esc_html__( 'Open WhatsApp', 'guruswad' ) . '</a>';
				}
				echo '</p>';
			},
			'gor_enquiry',
			'normal',
			'high'
		);
	}
);
add_action(
	'save_post_gor_enquiry',
	function ( $id ) {
		if ( isset( $_POST['gor_enq_status_nonce'], $_POST['gor_status'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gor_enq_status_nonce'] ) ), 'gor_enq_status' ) && current_user_can( 'edit_post', $id ) ) {
			update_post_meta( $id, '_gor_status', sanitize_key( wp_unslash( $_POST['gor_status'] ) ) );
		}
	}
);

/**
 * CSV export button + handler.
 */
add_action(
	'restrict_manage_posts',
	function ( $post_type ) {
		if ( 'gor_enquiry' === $post_type ) {
			$url = wp_nonce_url( admin_url( 'admin-post.php?action=gor_enquiry_csv' ), 'gor_csv' );
			echo '<a href="' . esc_url( $url ) . '" class="button" style="margin-left:6px">' . esc_html__( 'Download CSV', 'guruswad' ) . '</a>';
		}
	}
);
add_action(
	'admin_post_gor_enquiry_csv',
	function () {
		if ( ! current_user_can( 'edit_posts' ) || ! check_admin_referer( 'gor_csv' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'guruswad' ) );
		}
		$fields = gor_enquiry_fields();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=enquiries-' . gmdate( 'Y-m-d' ) . '.csv' );
		$out = fopen( 'php://output', 'w' );
		fwrite( $out, "\xEF\xBB\xBF" ); // UTF-8 BOM so Excel shows Marathi correctly.
		fputcsv( $out, array_merge( array( 'Date', 'Type', 'Status' ), array_values( $fields ) ) );
		$q = new WP_Query( array( 'post_type' => 'gor_enquiry', 'post_status' => 'any', 'posts_per_page' => -1, 'fields' => 'ids' ) );
		foreach ( $q->posts as $id ) {
			$terms = wp_get_object_terms( $id, 'gor_enquiry_type', array( 'fields' => 'names' ) );
			$row   = array( get_the_date( 'Y-m-d H:i', $id ), implode( ', ', $terms ), get_post_meta( $id, '_gor_status', true ) );
			foreach ( array_keys( $fields ) as $k ) {
				$row[] = get_post_meta( $id, '_gor_' . $k, true );
			}
			fputcsv( $out, $row );
		}
		fclose( $out );
		exit;
	}
);

/**
 * New-enquiry count bubble in the admin menu.
 */
add_action(
	'admin_menu',
	function () {
		global $menu;
		$count = count( get_posts( array( 'post_type' => 'gor_enquiry', 'post_status' => 'private', 'meta_key' => '_gor_status', 'meta_value' => 'new', 'fields' => 'ids', 'posts_per_page' => 99 ) ) );
		if ( ! $count || ! is_array( $menu ) ) {
			return;
		}
		foreach ( $menu as $i => $item ) {
			if ( 'edit.php?post_type=gor_enquiry' === $item[2] ) {
				$menu[ $i ][0] .= ' <span class="awaiting-mod">' . (int) $count . '</span>'; // phpcs:ignore
			}
		}
	},
	99
);
