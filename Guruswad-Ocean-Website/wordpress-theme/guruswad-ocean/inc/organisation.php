<?php
/**
 * The group's organisational structure, and the two patterns that present it.
 *
 * The client's letter (§1) asked for four organisations to be shown separately
 * and in a fixed sequence, because the site previously credited the whole
 * group's work to Guruswad Ocean Private Limited alone. Each mandate below is
 * taken from that letter. Nothing is inferred, and a legal form is named only
 * where the registered name states it — which is why the Association is
 * described by what it does rather than by what it is registered as.
 *
 * The order matters and is not alphabetical: produce is mobilised by the
 * producer company, developed and processed through the Association, traded by
 * the company, and sold under the brand.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * The four organisations, in sequence.
 *
 * @return array List of array{num,kind,name,blurb,short,does,page}.
 */
function gor_organisations() {
	$out = array(
		array(
			'num'   => '01',
			'kind'  => __( 'Farmer Producer Company (FPO)', 'guruswad' ),
			'name'  => __( 'CSC Sangli Jath MH Vishwabhumi Farmer Producer Company Ltd.', 'guruswad' ),
			'blurb' => __( 'The producer-owned company at the base of the chain. It brings farmers into membership, aggregates what they grow, and is the body that deals with the growers directly.', 'guruswad' ),
			'short' => __( 'Mobilises farmers, aggregates their produce, and deals directly with the growers.', 'guruswad' ),
			'does'  => array(
				__( 'Farmer mobilisation', 'guruswad' ),
				__( 'Aggregation of agricultural produce', 'guruswad' ),
				__( 'Coordination with producers', 'guruswad' ),
			),
			'page'  => 'farmers',
		),
		array(
			'num'   => '02',
			'kind'  => __( 'Farmer development & CFC services', 'guruswad' ),
			'name'  => __( 'Guruswad Agriculture and Food Association', 'guruswad' ),
			'blurb' => __( 'The development body. It trains, organises women’s self-help groups, promotes organic farming practice, and is the organisation through which the Common Facility Centre will offer its services.', 'guruswad' ),
			'short' => __( 'Training, women’s self-help groups, organic farming practice, value addition and the CFC.', 'guruswad' ),
			'does'  => array(
				__( 'Farmer development and training', 'guruswad' ),
				__( 'Women’s self-help groups', 'guruswad' ),
				__( 'Promotion of organic farming', 'guruswad' ),
				__( 'Value addition', 'guruswad' ),
				__( 'Common Facility Centre (CFC) services', 'guruswad' ),
			),
			'page'  => 'cfc',
		),
		array(
			'num'   => '03',
			'kind'  => __( 'Private limited company', 'guruswad' ),
			'name'  => __( 'Guruswad Ocean Private Limited', 'guruswad' ),
			'blurb' => __( 'The commercial arm. It develops the business, holds and manages the brands, and carries the goods to market at home and abroad.', 'guruswad' ),
			'short' => __( 'Business development, brand management, and trade at home and abroad.', 'guruswad' ),
			'does'  => array(
				__( 'Business development', 'guruswad' ),
				__( 'Brand management', 'guruswad' ),
				__( 'Domestic and international trade', 'guruswad' ),
				__( 'Import and export', 'guruswad' ),
			),
			'page'  => 'export',
		),
		array(
			'num'   => '04',
			'kind'  => __( 'Our brand', 'guruswad' ),
			'name'  => __( 'MilletRush', 'guruswad' ),
			'blurb' => __( 'The name the grain reaches a shelf under — millet-based and value-added food products, made from what the network grows.', 'guruswad' ),
			'short' => __( 'The brand our millet-based and value-added food products are sold under.', 'guruswad' ),
			'does'  => array(
				__( 'Millet-based food products', 'guruswad' ),
				__( 'Value-added food products', 'guruswad' ),
			),
			'page'  => 'brand',
			'brand' => true,
		),
	);

	/**
	 * Filter the organisations shown in the structure chain.
	 *
	 * @param array $out Organisations.
	 */
	return apply_filters( 'gor_organisations', $out );
}

/**
 * Resolve an organisation's key to a URL on this site.
 *
 * Each one is a page the client may rename or move, so the link is resolved at
 * render time rather than hard-coded.
 *
 * @param string $key One of farmers, cfc, export, brand.
 * @return string URL, or '' when no such page exists yet.
 */
function gor_org_link( $key ) {
	$map = array(
		'farmers' => 'template-farmers.php',
		'cfc'     => 'template-cfc.php',
		'export'  => 'template-export.php',
	);

	if ( 'brand' === $key ) {
		$brand = get_posts(
			array(
				'post_type'      => 'gor_brand',
				'posts_per_page' => 1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'fields'         => 'ids',
			)
		);
		return $brand ? (string) get_permalink( $brand[0] ) : '';
	}

	if ( ! isset( $map[ $key ] ) ) {
		return '';
	}

	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-templates/' . $map[ $key ],
			'number'     => 1,
		)
	);
	return $pages ? (string) get_permalink( $pages[0]->ID ) : '';
}

/**
 * The organisation chain.
 *
 * @param bool $compact Drop the mandate lists and run four across — the home
 *                      page form, where the detail belongs on About Us.
 * @return void
 */
function gor_org_chain( $compact = false ) {
	$orgs = gor_organisations();
	if ( ! $orgs ) {
		return;
	}
	?>
	<ol class="org-chain<?php echo $compact ? ' org-chain--compact' : ''; ?>">
		<?php
		foreach ( $orgs as $gor_o ) :
			$gor_url = gor_org_link( isset( $gor_o['page'] ) ? $gor_o['page'] : '' );
			?>
			<li class="org-entity<?php echo empty( $gor_o['brand'] ) ? '' : ' org-entity--brand'; ?>" data-reveal>
				<span class="org-entity__num" aria-hidden="true"><?php echo esc_html( $gor_o['num'] ); ?></span>
				<div class="org-entity__body">
					<span class="org-entity__kind"><?php echo esc_html( $gor_o['kind'] ); ?></span>
					<h3>
						<?php if ( $compact && $gor_url ) : ?>
							<a href="<?php echo esc_url( $gor_url ); ?>"><?php echo esc_html( $gor_o['name'] ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $gor_o['name'] ); ?>
						<?php endif; ?>
					</h3>
					<p><?php echo esc_html( $compact ? $gor_o['short'] : $gor_o['blurb'] ); ?></p>
					<?php if ( ! $compact && ! empty( $gor_o['does'] ) ) : ?>
						<ul class="check-list">
							<?php foreach ( $gor_o['does'] as $gor_d ) : ?>
								<li><?php echo esc_html( $gor_d ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ol>
	<?php
}

/**
 * A disclosure strip: where something is planned rather than built, or a claim
 * is narrower than it looks, the page says so at the size of the claim.
 *
 * @param string $icon  Icon key from gor_icon().
 * @param string $html  Already-escaped markup, normally one or two <p>.
 * @return void
 */
function gor_disclosure( $icon, $html ) {
	?>
	<div class="disclosure" data-reveal>
		<?php gor_the_icon( $icon ); ?>
		<div><?php echo wp_kses_post( $html ); ?></div>
	</div>
	<?php
}
