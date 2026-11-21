<?php
/**
 * Trade section: editorial intro, a dotted world drawn from real coordinates,
 * and the capability row.
 *
 * Markets come from gor_markets() — the gor_market taxonomy, or the seed list
 * until the client adds their own. Nothing is hard-coded here any more, so a
 * new market is a term, not a deployment.
 *
 * The SVG is drawn by assets/js/main.js from the data attributes below. The
 * market list underneath is not a fallback bolted on afterwards: it is the
 * content, present for every reader, and the whole of the section on a phone
 * where a world map cannot be read anyway.
 *
 * @package Guruswad_Ocean
 *
 * @var string $gor_eyebrow Optional override.
 * @var string $gor_title_a Optional override, first line.
 * @var string $gor_title_b Optional override, second line (gold).
 * @var string $gor_lead    Optional override.
 */

defined( 'ABSPATH' ) || exit;

$gor_origin   = gor_market_origin();
$gor_markets  = gor_markets();
$gor_statuses = gor_market_statuses();

$gor_eyebrow = isset( $args['eyebrow'] ) ? $args['eyebrow'] : __( 'Import & Export', 'guruswad' );
$gor_title_a = isset( $args['title_a'] ) ? $args['title_a'] : __( 'From Indian farms', 'guruswad' );
$gor_title_b = isset( $args['title_b'] ) ? $args['title_b'] : __( 'to global markets', 'guruswad' );
$gor_lead    = isset( $args['lead'] ) ? $args['lead'] : __( 'Export-grade millets and millet products, private label and custom packaging — with documentation handled end to end.', 'guruswad' );

// Only what the map needs; no business claims travel to the front end.
$gor_payload = array();
foreach ( $gor_markets as $gor_m ) {
	$gor_payload[] = array(
		'name'   => $gor_m['name'],
		'slug'   => isset( $gor_m['slug'] ) ? $gor_m['slug'] : sanitize_title( $gor_m['name'] ),
		'lat'    => (float) $gor_m['lat'],
		'lng'    => (float) $gor_m['lng'],
		'status' => $gor_m['status'],
	);
}

$gor_features = array(
	array( 'grain',     __( 'Premium millets', 'guruswad' ) ),
	array( 'globe',     __( 'Global reach', 'guruswad' ) ),
	array( 'handshake', __( 'Trusted partnerships', 'guruswad' ) ),
	array( 'shield',    __( 'Quality assurance', 'guruswad' ) ),
	array( 'leaf',      __( 'Sustainable growth', 'guruswad' ) ),
);
?>
<section class="trade" id="trade">
	<div class="trade__bg" aria-hidden="true">
		<span class="trade__bg-port"></span>
		<span class="trade__bg-grain"></span>
	</div>

	<div class="container trade__inner">
		<div class="trade__intro">
			<span class="trade__eyebrow"><?php echo esc_html( $gor_eyebrow ); ?></span>
			<h2 class="trade__title" data-split><?php echo esc_html( $gor_title_a ); ?> <em><?php echo esc_html( $gor_title_b ); ?></em></h2>
			<p class="trade__lead" data-reveal><?php echo esc_html( $gor_lead ); ?></p>
			<div class="rule-lux" aria-hidden="true"><i></i></div>
			<div class="btn-row">
				<a class="btn btn--gold" href="<?php echo esc_url( gor_enquiry_url( 'export' ) ); ?>" data-magnetic><?php esc_html_e( 'Request a quote', 'guruswad' ); ?></a>
				<?php if ( gor_opt( 'catalogue_pdf' ) ) : ?>
					<a class="btn btn--ghost-light" href="<?php echo esc_url( gor_opt( 'catalogue_pdf' ) ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Export catalogue', 'guruswad' ); ?></a>
				<?php endif; ?>
			</div>
		</div>

		<div class="trade__map"
			data-origin="<?php echo esc_attr( wp_json_encode( $gor_origin ) ); ?>"
			data-markets="<?php echo esc_attr( wp_json_encode( $gor_payload ) ); ?>">
			<svg role="img" aria-label="<?php esc_attr_e( 'Export routes from Maharashtra, India', 'guruswad' ); ?>"></svg>
			<ul class="trade__markets">
				<?php foreach ( $gor_markets as $gor_m ) : ?>
					<li data-status="<?php echo esc_attr( $gor_m['status'] ); ?>">
						<b><?php echo esc_html( $gor_m['name'] ); ?></b>
						<span><?php echo esc_html( isset( $gor_statuses[ $gor_m['status'] ] ) ? $gor_statuses[ $gor_m['status'] ] : '' ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php
			// Client letter §6: the list must not read as a list of customers.
			$gor_has_active = false;
			foreach ( $gor_markets as $gor_m ) {
				if ( 'active' === $gor_m['status'] ) {
					$gor_has_active = true;
					break;
				}
			}
			?>
			<?php if ( ! $gor_has_active ) : ?>
				<p class="trade__note"><?php esc_html_e( 'No market below is presented as an active export market. These are the territories we are working towards; trade has not commenced in them.', 'guruswad' ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="container trade__foot">
		<ul class="trade__features">
			<?php foreach ( $gor_features as $gor_f ) : ?>
				<li><?php gor_the_icon( $gor_f[0] ); ?><span><?php echo esc_html( $gor_f[1] ); ?></span></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
