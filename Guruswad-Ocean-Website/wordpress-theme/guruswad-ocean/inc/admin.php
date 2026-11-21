<?php
/**
 * Admin panel — the single place the client runs the site from.
 *
 * Client requirement 14 lists eleven things the team must be able to do
 * without a developer. Almost all of them already exist somewhere in
 * WordPress or WooCommerce; what was missing was a front door. Scattering
 * those eleven tasks across the sidebar is exactly how a non-technical editor
 * ends up calling the agency, so this adds one screen that gathers them,
 * shows what still needs filling in, and links straight to the real tools.
 *
 * Nothing here duplicates WordPress. Every tile is a link to the native
 * screen that already does the job.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the top-level menu.
 *
 * @return void
 */
function gor_admin_menu() {
	add_menu_page(
		__( 'Guruswad Ocean', 'guruswad' ),
		__( 'Guruswad Ocean', 'guruswad' ),
		'edit_posts',
		'gor-dashboard',
		'gor_admin_dashboard',
		'dashicons-admin-site-alt3',
		3
	);
	add_submenu_page( 'gor-dashboard', __( 'Dashboard', 'guruswad' ), __( 'Dashboard', 'guruswad' ), 'edit_posts', 'gor-dashboard', 'gor_admin_dashboard' );
	add_submenu_page( 'gor-dashboard', __( 'Setup checklist', 'guruswad' ), __( 'Setup checklist', 'guruswad' ), 'manage_options', 'gor-setup', 'gor_admin_setup' );
}
add_action( 'admin_menu', 'gor_admin_menu' );

/**
 * Count posts of a type, published and draft.
 *
 * @param string $type Post type.
 * @return int
 */
function gor_admin_count( $type ) {
	$c = wp_count_posts( $type );
	if ( ! $c ) {
		return 0;
	}
	$n = 0;
	foreach ( array( 'publish', 'draft', 'private', 'pending' ) as $s ) {
		$n += isset( $c->$s ) ? (int) $c->$s : 0;
	}
	return $n;
}

/**
 * How many enquiries are still marked new.
 *
 * @return int
 */
function gor_admin_new_enquiries() {
	$q = new WP_Query(
		array(
			'post_type'      => 'gor_enquiry',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => false,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_gor_status',
					'value' => 'new',
				),
			),
		)
	);
	return (int) $q->found_posts;
}

/**
 * The tasks from client requirement 14, each pointing at the screen that does it.
 *
 * @return array
 */
function gor_admin_tasks() {
	$woo = class_exists( 'WooCommerce' );

	$tasks = array(
		array(
			'title' => __( 'Enquiries', 'guruswad' ),
			'desc'  => __( 'All seven enquiry types, with status and CSV export.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=gor_enquiry' ),
			'count' => gor_admin_new_enquiries(),
			'badge' => __( 'new', 'guruswad' ),
			'icon'  => 'dashicons-email-alt',
		),
		array(
			'title' => __( 'Pages', 'guruswad' ),
			'desc'  => __( 'Add and edit site pages and their content blocks.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=page' ),
			'icon'  => 'dashicons-admin-page',
		),
		array(
			'title' => __( 'News & announcements', 'guruswad' ),
			'desc'  => __( 'Publish updates, recognitions and press.', 'guruswad' ),
			'url'   => admin_url( 'edit.php' ),
			'count' => gor_admin_count( 'post' ),
			'icon'  => 'dashicons-megaphone',
		),
		array(
			'title' => __( 'Brands', 'guruswad' ),
			'desc'  => __( 'MilletRush and any future brand, each with its own world.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=gor_brand' ),
			'count' => gor_admin_count( 'gor_brand' ),
			'icon'  => 'dashicons-tag',
		),
		array(
			'title' => __( 'Export markets', 'guruswad' ),
			'desc'  => __( 'Markets on the trade map. Give each a latitude, longitude and status.', 'guruswad' ),
			'url'   => admin_url( 'edit-tags.php?taxonomy=gor_market' ),
			'icon'  => 'dashicons-admin-site',
		),
		array(
			'title' => __( 'Farmer & SHG stories', 'guruswad' ),
			'desc'  => __( 'The impact stories shown across the Farmers pages.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=gor_story' ),
			'count' => gor_admin_count( 'gor_story' ),
			'icon'  => 'dashicons-groups',
		),
		array(
			'title' => __( 'Leadership', 'guruswad' ),
			'desc'  => __( 'Board and management shown on About Us.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=gor_team' ),
			'count' => gor_admin_count( 'gor_team' ),
			'icon'  => 'dashicons-businessperson',
		),
		array(
			'title' => __( 'Certifications', 'guruswad' ),
			'desc'  => __( 'Badges and collaborations shown as proof.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=gor_cert' ),
			'count' => gor_admin_count( 'gor_cert' ),
			'icon'  => 'dashicons-awards',
		),
		array(
			'title' => __( 'Media library', 'guruswad' ),
			'desc'  => __( 'Upload and manage photographs and video.', 'guruswad' ),
			'url'   => admin_url( 'upload.php' ),
			'icon'  => 'dashicons-format-gallery',
		),
		array(
			// §13 and §14 both require English and Marathi to be editable here,
			// not handed back to a developer.
			'title' => __( 'English & Marathi content', 'guruswad' ),
			'desc'  => __( 'Every piece of site copy in both languages, side by side.', 'guruswad' ),
			'url'   => admin_url( 'edit.php?post_type=page' ),
			'icon'  => 'dashicons-translation',
		),
		array(
			'title' => __( 'Banners & hero media', 'guruswad' ),
			'desc'  => __( 'Home hero video or image, and the impact numbers beneath it.', 'guruswad' ),
			'url'   => admin_url( 'customize.php?autofocus[section]=gor_hero' ),
			'icon'  => 'dashicons-format-image',
		),
		array(
			'title' => __( 'Business details', 'guruswad' ),
			'desc'  => __( 'Phone, WhatsApp, email, address and the export catalogue.', 'guruswad' ),
			'url'   => admin_url( 'customize.php?autofocus[section]=gor_business' ),
			'icon'  => 'dashicons-store',
		),
		array(
			'title' => __( 'Enquiry routing', 'guruswad' ),
			'desc'  => __( 'Which inbox each of the seven enquiry types reaches.', 'guruswad' ),
			'url'   => admin_url( 'customize.php?autofocus[section]=gor_routing' ),
			'icon'  => 'dashicons-randomize',
		),
	);

	if ( $woo ) {
		array_splice(
			$tasks,
			1,
			0,
			array(
				array(
					'title' => __( 'Products, prices & stock', 'guruswad' ),
					'desc'  => __( 'Add products, set prices, manage inventory and packaging.', 'guruswad' ),
					'url'   => admin_url( 'edit.php?post_type=product' ),
					'count' => gor_admin_count( 'product' ),
					'icon'  => 'dashicons-products',
				),
				array(
					'title' => __( 'Orders', 'guruswad' ),
					'desc'  => __( 'Customer orders, invoices and fulfilment.', 'guruswad' ),
					'url'   => admin_url( 'admin.php?page=wc-orders' ),
					'icon'  => 'dashicons-cart',
				),
			)
		);
	}

	return apply_filters( 'gor_admin_tasks', $tasks );
}

/**
 * The dashboard screen.
 *
 * @return void
 */
function gor_admin_dashboard() {
	$csv = wp_nonce_url( admin_url( 'admin-post.php?action=gor_enquiry_csv' ), 'gor_csv' );
	?>
	<div class="wrap gor-admin">
		<div class="gor-masthead">
			<span class="gor-masthead__eyebrow"><?php esc_html_e( 'Site control', 'guruswad' ); ?></span>
			<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
			<p>
				<?php
				$gor_new = gor_admin_new_enquiries();
				if ( $gor_new ) {
					printf(
						/* translators: %s is a count of unread enquiries. */
						esc_html( _n( '%s enquiry is waiting. Everything the site needs day to day is below — each card opens the screen that does the job.', '%s enquiries are waiting. Everything the site needs day to day is below — each card opens the screen that does the job.', $gor_new, 'guruswad' ) ),
						'<strong>' . esc_html( number_format_i18n( $gor_new ) ) . '</strong>'
					);
				} else {
					esc_html_e( 'No enquiries waiting. Everything the site needs day to day is below — each card opens the screen that does the job.', 'guruswad' );
				}
				?>
			</p>
		</div>

		<div class="gor-admin__grid">
			<?php foreach ( gor_admin_tasks() as $t ) : ?>
				<a class="gor-card" href="<?php echo esc_url( $t['url'] ); ?>">
					<span class="gor-card__icon dashicons <?php echo esc_attr( $t['icon'] ); ?>" aria-hidden="true"></span>
					<span class="gor-card__body">
						<strong><?php echo esc_html( $t['title'] ); ?>
							<?php if ( ! empty( $t['count'] ) ) : ?>
								<em class="gor-card__count"><?php
									echo esc_html( number_format_i18n( $t['count'] ) );
									if ( ! empty( $t['badge'] ) ) {
										echo ' ' . esc_html( $t['badge'] );
									}
								?></em>
							<?php endif; ?>
						</strong>
						<span><?php echo esc_html( $t['desc'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<h2><?php esc_html_e( 'Reporting', 'guruswad' ); ?></h2>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( $csv ); ?>"><?php esc_html_e( 'Download all enquiries (CSV)', 'guruswad' ); ?></a>
			<a class="button" href="<?php echo esc_url( admin_url( 'index.php' ) ); ?>"><?php esc_html_e( 'Site activity', 'guruswad' ); ?></a>
		</p>
		<p class="description">
			<?php esc_html_e( 'Website analytics live in Google Analytics and Search Console, which are connected during handover. Their dashboards stay outside WordPress so the data is owned by the company, not the theme.', 'guruswad' ); ?>
		</p>
	</div>
	<?php
}

/**
 * Setup checklist: what is filled in, and what is still a placeholder.
 *
 * @return void
 */
function gor_admin_setup() {
	$d = gor_defaults();

	// Each row: label, whether it is done, and where to fix it.
	$rows = array(
		array( __( 'Company phone number', 'guruswad' ), gor_opt( 'phone' ) !== $d['phone'], 'gor_business' ),
		array( __( 'WhatsApp number', 'guruswad' ), gor_opt( 'whatsapp' ) !== $d['whatsapp'], 'gor_business' ),
		array( __( 'Public email address', 'guruswad' ), gor_opt( 'email' ) !== $d['email'], 'gor_business' ),
		array( __( 'Registered address', 'guruswad' ), gor_opt( 'address' ) !== $d['address'], 'gor_business' ),
		array( __( 'Export catalogue PDF', 'guruswad' ), '' !== gor_opt( 'catalogue_pdf' ), 'gor_business' ),
		array( __( 'Home hero video or image', 'guruswad' ), '' !== gor_opt( 'hero_video' ) || '' !== gor_opt( 'hero_poster' ), 'gor_hero' ),
		array( __( 'Enquiry routing addresses', 'guruswad' ), '' !== gor_opt( 'mail_export' ) || '' !== gor_opt( 'mail_general' ), 'gor_routing' ),
		array( __( 'Google Analytics 4 measurement ID', 'guruswad' ), '' !== gor_opt( 'ga4_id' ), 'gor_analytics' ),
		array( __( 'Search Console verification', 'guruswad' ), '' !== gor_opt( 'gsc_verify' ), 'gor_analytics' ),
		array( __( 'Social links', 'guruswad' ), gor_opt( 'social_facebook' ) !== $d['social_facebook'], 'gor_social' ),
		array( __( 'Spam protection keys (Turnstile)', 'guruswad' ), '' !== gor_opt( 'turnstile_site' ) && '' !== gor_opt( 'turnstile_secret' ), 'gor_spam' ),
	);

	$terms   = get_terms( array( 'taxonomy' => 'gor_market', 'hide_empty' => false ) );
	$markets = ( ! is_wp_error( $terms ) && ! empty( $terms ) );
	$done    = 0;
	foreach ( $rows as $r ) {
		if ( $r[1] ) {
			$done++;
		}
	}
	$total = count( $rows ) + 3;
	$done += $markets ? 1 : 0;
	$done += gor_admin_count( 'gor_team' ) ? 1 : 0;
	$done += gor_admin_count( 'gor_brand' ) ? 1 : 0;
	?>
	<div class="wrap gor-admin">
		<div class="gor-masthead">
			<span class="gor-masthead__eyebrow"><?php esc_html_e( 'Before launch', 'guruswad' ); ?></span>
			<h1><?php esc_html_e( 'Setup checklist', 'guruswad' ); ?></h1>
			<p><?php esc_html_e( 'Anything unticked is still showing a placeholder on the live site. Each row links to the screen that fixes it.', 'guruswad' ); ?></p>
		</div>

		<div class="gor-progress">
			<div class="gor-progress__bar" role="progressbar"
				aria-valuenow="<?php echo (int) $done; ?>" aria-valuemin="0" aria-valuemax="<?php echo (int) $total; ?>"
				aria-label="<?php esc_attr_e( 'Setup progress', 'guruswad' ); ?>">
				<i style="width: <?php echo esc_attr( $total ? round( $done / $total * 100 ) : 0 ); ?>%"></i>
			</div>
			<div class="gor-progress__label">
				<span>
					<?php
					printf(
						/* translators: 1: completed count, 2: total count. */
						esc_html__( '%1$s of %2$s set', 'guruswad' ),
						'<b>' . esc_html( number_format_i18n( $done ) ) . '</b>',
						esc_html( number_format_i18n( $total ) )
					);
					?>
				</span>
				<span><?php echo esc_html( $done === $total ? __( 'Ready for handover', 'guruswad' ) : __( 'Still to supply', 'guruswad' ) ); ?></span>
			</div>
		</div>

		<table class="widefat gor-admin__table">
			<tbody>
			<?php foreach ( $rows as $r ) : ?>
				<tr data-done="<?php echo $r[1] ? '1' : '0'; ?>">
					<td class="gor-admin__state" data-done="<?php echo $r[1] ? '1' : '0'; ?>"><?php echo $r[1] ? '&#10003;' : '&#9675;'; ?></td>
					<td><?php echo esc_html( $r[0] ); ?></td>
					<td><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=' . $r[2] ) ); ?>"><?php esc_html_e( 'Edit', 'guruswad' ); ?></a></td>
				</tr>
			<?php endforeach; ?>
				<tr data-done="<?php echo $markets ? '1' : '0'; ?>">
					<td class="gor-admin__state" data-done="<?php echo $markets ? '1' : '0'; ?>"><?php echo $markets ? '&#10003;' : '&#9675;'; ?></td>
					<td><?php esc_html_e( 'Export markets added (with coordinates)', 'guruswad' ); ?></td>
					<td><a href="<?php echo esc_url( admin_url( 'edit-tags.php?taxonomy=gor_market' ) ); ?>"><?php esc_html_e( 'Edit', 'guruswad' ); ?></a></td>
				</tr>
				<tr data-done="<?php echo gor_admin_count( 'gor_team' ) ? '1' : '0'; ?>">
					<td class="gor-admin__state" data-done="<?php echo gor_admin_count( 'gor_team' ) ? '1' : '0'; ?>"><?php echo gor_admin_count( 'gor_team' ) ? '&#10003;' : '&#9675;'; ?></td>
					<td><?php esc_html_e( 'Leadership added', 'guruswad' ); ?></td>
					<td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=gor_team' ) ); ?>"><?php esc_html_e( 'Edit', 'guruswad' ); ?></a></td>
				</tr>
				<tr data-done="<?php echo gor_admin_count( 'gor_brand' ) ? '1' : '0'; ?>">
					<td class="gor-admin__state" data-done="<?php echo gor_admin_count( 'gor_brand' ) ? '1' : '0'; ?>"><?php echo gor_admin_count( 'gor_brand' ) ? '&#10003;' : '&#9675;'; ?></td>
					<td><?php esc_html_e( 'Brands added', 'guruswad' ); ?></td>
					<td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=gor_brand' ) ); ?>"><?php esc_html_e( 'Edit', 'guruswad' ); ?></a></td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * A little styling for the two screens above. Inline because it is ~30 lines
 * and loading a stylesheet for them would cost more than it saves.
 *
 * @param string $hook Current admin page.
 * @return void
 */
function gor_admin_styles( $hook ) {
	if ( false === strpos( $hook, 'gor-dashboard' ) && false === strpos( $hook, 'gor-setup' ) ) {
		return;
	}
	// Only on these two screens — the brand font and palette have no business
	// restyling the rest of wp-admin.
	wp_enqueue_style( 'gor-admin-fonts', GOR_URI . '/assets/css/fonts.css', array(), GOR_VERSION );
	wp_enqueue_style( 'gor-admin', GOR_URI . '/assets/css/admin.css', array( 'gor-admin-fonts' ), GOR_VERSION );
}
add_action( 'admin_enqueue_scripts', 'gor_admin_styles' );

/**
 * Mark our own screens so the stylesheet can take the background.
 *
 * @param string $classes Body classes.
 * @return string
 */
function gor_admin_body_class( $classes ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && false !== strpos( (string) $screen->id, 'gor-' ) ) {
		$classes .= ' gor-screen';
	}
	return $classes;
}
add_filter( 'admin_body_class', 'gor_admin_body_class' );

/**
 * Surface the count of new enquiries next to the menu item, so nobody has to
 * remember to go and look.
 *
 * @return void
 */
function gor_admin_menu_bubble() {
	global $menu;
	$n = gor_admin_new_enquiries();
	if ( ! $n || ! is_array( $menu ) ) {
		return;
	}
	foreach ( $menu as $i => $item ) {
		if ( isset( $item[2] ) && 'gor-dashboard' === $item[2] ) {
			$menu[ $i ][0] .= ' <span class="update-plugins count-' . (int) $n . '"><span class="update-count">' . number_format_i18n( $n ) . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			break;
		}
	}
}
add_action( 'admin_menu', 'gor_admin_menu_bubble', 999 );
