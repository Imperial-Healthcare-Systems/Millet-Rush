<?php
/**
 * Export markets: the data layer behind the trade map.
 *
 * The map used to carry its markets as hard-coded SVG coordinates, which meant
 * adding a market needed a developer — against the brief's "scalable ... without
 * rebuilding" requirement (client doc 1) and the blueprint's modular content
 * model (12.2). The `gor_market` taxonomy was already registered but nothing
 * read from it, so this file connects the two.
 *
 * Editors set a real latitude and longitude per market under Products → Export
 * Markets; the map projects them. Nothing here invents a statistic, a volume or
 * a certification — only the market's name, where it is on the globe, and
 * whether the company is serving it or entering it.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * The origin every route leaves from.
 *
 * @return array
 */
function gor_market_origin() {
	return array(
		'name'   => __( 'Maharashtra, India', 'guruswad' ),
		'lat'    => 19.75,
		'lng'    => 75.71,
		'status' => 'origin',
	);
}

/**
 * Statuses a market may carry. Deliberately only two, plus the origin.
 *
 * @return array
 */
function gor_market_statuses() {
	return array(
		'serving'  => __( 'Serving', 'guruswad' ),
		'entering' => __( 'Entering', 'guruswad' ),
	);
}

/**
 * The seed list, used until the client adds their own markets.
 *
 * These are destinations the brief itself names as the export focus, placed at
 * their real coordinates. They are a starting point for the taxonomy, not a
 * claim: the moment one `gor_market` term exists, this list is ignored.
 *
 * @return array
 */
function gor_market_defaults() {
	return array(
		array( 'name' => __( 'United Kingdom', 'guruswad' ),  'lat' => 51.51,  'lng' => -0.13,  'status' => 'entering' ),
		array( 'name' => __( 'Europe', 'guruswad' ),          'lat' => 51.92,  'lng' => 4.48,   'status' => 'entering' ),
		array( 'name' => __( 'USA', 'guruswad' ),             'lat' => 40.71,  'lng' => -74.01, 'status' => 'entering' ),
		array( 'name' => __( 'Saudi Arabia', 'guruswad' ),    'lat' => 24.71,  'lng' => 46.68,  'status' => 'serving' ),
		array( 'name' => __( 'UAE', 'guruswad' ),             'lat' => 25.20,  'lng' => 55.27,  'status' => 'serving' ),
		array( 'name' => __( 'South-East Asia', 'guruswad' ), 'lat' => 1.35,   'lng' => 103.82, 'status' => 'serving' ),
		array( 'name' => __( 'Australia', 'guruswad' ),       'lat' => -33.87, 'lng' => 151.21, 'status' => 'entering' ),
	);
}

/**
 * Every export market, from the taxonomy when it has terms, else the seed list.
 *
 * @return array List of array{name,lat,lng,status,slug}.
 */
function gor_markets() {
	$out   = array();
	$terms = get_terms(
		array(
			'taxonomy'   => 'gor_market',
			'hide_empty' => false,
			'orderby'    => 'term_order',
		)
	);

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		foreach ( $terms as $t ) {
			$lat = get_term_meta( $t->term_id, 'gor_lat', true );
			$lng = get_term_meta( $t->term_id, 'gor_lng', true );
			if ( '' === $lat || '' === $lng ) {
				continue; // Un-placed markets simply do not appear on the map.
			}
			$status = get_term_meta( $t->term_id, 'gor_status', true );
			$out[]  = array(
				'name'   => $t->name,
				'slug'   => $t->slug,
				'lat'    => (float) $lat,
				'lng'    => (float) $lng,
				'status' => array_key_exists( $status, gor_market_statuses() ) ? $status : 'serving',
			);
		}
	}

	if ( empty( $out ) ) {
		$out = gor_market_defaults();
		foreach ( $out as $i => $m ) {
			$out[ $i ]['slug'] = sanitize_title( $m['name'] );
		}
	}

	/**
	 * Filter the markets drawn on the trade map.
	 *
	 * @param array $out Markets.
	 */
	return apply_filters( 'gor_markets', $out );
}

/* -------------------------------------------------------------------------
 * Projection
 *
 * Equirectangular, trimmed to the latitudes that actually carry trade so the
 * composition is not half empty ice. One function, used by the template and
 * mirrored in JS, so a marker and its route always agree.
 * ---------------------------------------------------------------------- */

const GOR_MAP_W        = 1000;  // viewBox width
const GOR_MAP_LAT_TOP  = 76;    // northern edge of the frame
const GOR_MAP_LAT_BOT  = -52;   // southern edge

/**
 * Frame height implied by the latitude window.
 *
 * @return float
 */
function gor_map_height() {
	// Same units-per-degree as x, or the projection stretches vertically.
	return ( GOR_MAP_LAT_TOP - GOR_MAP_LAT_BOT ) / 360 * GOR_MAP_W;
}

/**
 * Project a coordinate into the map's viewBox.
 *
 * @param float $lat Latitude.
 * @param float $lng Longitude.
 * @return array{0:float,1:float} x, y rounded to one decimal.
 */
function gor_project( $lat, $lng ) {
	$x = ( (float) $lng + 180 ) / 360 * GOR_MAP_W;
	$y = ( GOR_MAP_LAT_TOP - (float) $lat ) / ( GOR_MAP_LAT_TOP - GOR_MAP_LAT_BOT ) * gor_map_height();
	return array( round( $x, 1 ), round( $y, 1 ) );
}

/**
 * A quadratic control point that bows a route away from the straight line.
 *
 * Long routes bow more than short ones, and every route bows towards the pole,
 * which is roughly how a great circle looks on this projection and reads as a
 * shipping lane rather than a rubber band.
 *
 * @param float $x1 Origin x.
 * @param float $y1 Origin y.
 * @param float $x2 Destination x.
 * @param float $y2 Destination y.
 * @return array{0:float,1:float}
 */
function gor_route_control( $x1, $y1, $x2, $y2 ) {
	$mx   = ( $x1 + $x2 ) / 2;
	$my   = ( $y1 + $y2 ) / 2;
	$dx   = $x2 - $x1;
	$dy   = $y2 - $y1;
	$dist = sqrt( $dx * $dx + $dy * $dy );
	$bow  = min( 0.22, 0.10 + $dist / 2600 );   // gentle, and capped
	return array( round( $mx, 1 ), round( $my - $dist * $bow, 1 ) );
}

/* -------------------------------------------------------------------------
 * Admin: latitude, longitude and status on the market term
 * ---------------------------------------------------------------------- */

/**
 * Fields on the "add market" form.
 *
 * @return void
 */
function gor_market_add_fields() {
	?>
	<div class="form-field">
		<label for="gor_lat"><?php esc_html_e( 'Latitude', 'guruswad' ); ?></label>
		<input type="text" name="gor_lat" id="gor_lat" value="">
		<p><?php esc_html_e( 'Decimal degrees, e.g. 25.20 for Dubai. Needed for the market to appear on the trade map.', 'guruswad' ); ?></p>
	</div>
	<div class="form-field">
		<label for="gor_lng"><?php esc_html_e( 'Longitude', 'guruswad' ); ?></label>
		<input type="text" name="gor_lng" id="gor_lng" value="">
		<p><?php esc_html_e( 'Decimal degrees, e.g. 55.27 for Dubai. West of Greenwich is negative.', 'guruswad' ); ?></p>
	</div>
	<div class="form-field">
		<label for="gor_status"><?php esc_html_e( 'Status', 'guruswad' ); ?></label>
		<select name="gor_status" id="gor_status">
			<?php foreach ( gor_market_statuses() as $k => $label ) : ?>
				<option value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
}
add_action( 'gor_market_add_form_fields', 'gor_market_add_fields' );

/**
 * Fields on the "edit market" form.
 *
 * @param WP_Term $term Term.
 * @return void
 */
function gor_market_edit_fields( $term ) {
	$lat    = get_term_meta( $term->term_id, 'gor_lat', true );
	$lng    = get_term_meta( $term->term_id, 'gor_lng', true );
	$status = get_term_meta( $term->term_id, 'gor_status', true );
	?>
	<tr class="form-field">
		<th scope="row"><label for="gor_lat"><?php esc_html_e( 'Latitude', 'guruswad' ); ?></label></th>
		<td>
			<input type="text" name="gor_lat" id="gor_lat" value="<?php echo esc_attr( $lat ); ?>">
			<p class="description"><?php esc_html_e( 'Decimal degrees, e.g. 25.20 for Dubai.', 'guruswad' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="gor_lng"><?php esc_html_e( 'Longitude', 'guruswad' ); ?></label></th>
		<td>
			<input type="text" name="gor_lng" id="gor_lng" value="<?php echo esc_attr( $lng ); ?>">
			<p class="description"><?php esc_html_e( 'Decimal degrees. West of Greenwich is negative.', 'guruswad' ); ?></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><label for="gor_status"><?php esc_html_e( 'Status', 'guruswad' ); ?></label></th>
		<td>
			<select name="gor_status" id="gor_status">
				<?php foreach ( gor_market_statuses() as $k => $label ) : ?>
					<option value="<?php echo esc_attr( $k ); ?>" <?php selected( $status, $k ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<?php
}
add_action( 'gor_market_edit_form_fields', 'gor_market_edit_fields' );

/**
 * Save the market's coordinates and status.
 *
 * @param int $term_id Term ID.
 * @return void
 */
function gor_market_save_fields( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- core verifies the term form nonce before these hooks run.
	foreach ( array( 'gor_lat', 'gor_lng' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$raw = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
			if ( '' === $raw ) {
				delete_term_meta( $term_id, $key );
			} elseif ( is_numeric( $raw ) ) {
				$val   = (float) $raw;
				$limit = ( 'gor_lat' === $key ) ? 90 : 180;
				if ( $val >= -$limit && $val <= $limit ) {
					update_term_meta( $term_id, $key, $val );
				}
			}
		}
	}
	if ( isset( $_POST['gor_status'] ) ) {
		$status = sanitize_key( wp_unslash( $_POST['gor_status'] ) );
		if ( array_key_exists( $status, gor_market_statuses() ) ) {
			update_term_meta( $term_id, 'gor_status', $status );
		}
	}
	// phpcs:enable
}
add_action( 'created_gor_market', 'gor_market_save_fields' );
add_action( 'edited_gor_market', 'gor_market_save_fields' );
