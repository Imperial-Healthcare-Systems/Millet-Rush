<?php
/**
 * Template Name: About Us
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'About Us', 'guruswad' ), 'title' => __( 'An ancient grain. A modern <em>company</em>.', 'guruswad' ), 'lead' => __( 'Guruswad Ocean Private Limited works across agriculture, millet processing, food manufacturing and global trade — building a long-term platform for millets from Maharashtra.', 'guruswad' ) ) );
?>

<section class="section">
	<div class="container split">
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Founder’s note', 'guruswad' ); ?></span>
			<blockquote class="quote-block" data-reveal><?php esc_html_e( '“Millets fed our grandparents. We want them to nourish the world again — and to make sure the farmers who grow them share in that future.”', 'guruswad' ); ?><cite><?php esc_html_e( 'Founder & Managing Director', 'guruswad' ); ?></cite></blockquote>
		</div>
		<div class="entry-content" style="margin:0" data-reveal>
			<?php
			if ( get_the_content() ) {
				the_content();
			} else {
				echo '<p>' . esc_html__( 'Use the page editor to add the company background, history and business activities. This text is a placeholder.', 'guruswad' ) . '</p>';
			}
			?>
		</div>
	</div>
</section>

<section class="section section--dark">
	<div class="container cards-2">
		<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'spark' ); ?></span><span class="eyebrow"><?php esc_html_e( 'Vision', 'guruswad' ); ?></span><h3 class="h-md"><?php esc_html_e( 'To make millets a globally loved everyday food — grown sustainably, processed scientifically and shared fairly.', 'guruswad' ); ?></h3></div>
		<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'handshake' ); ?></span><span class="eyebrow"><?php esc_html_e( 'Mission', 'guruswad' ); ?></span><h3 class="h-md"><?php esc_html_e( 'To connect farmers, science and markets through trusted brands, world-class processing and transparent trade.', 'guruswad' ); ?></h3></div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'Business activities', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'One company, seven connected businesses.', 'guruswad' ); ?></h2></div>
		<div class="cards-3">
			<?php
			$gor_acts = array(
				array( 'leaf', __( 'Agriculture & Agri Products', 'guruswad' ) ),
				array( 'grain', __( 'Millet & Food Processing', 'guruswad' ) ),
				array( 'factory', __( 'Food Manufacturing', 'guruswad' ) ),
				array( 'shop', __( 'Food Products & Trading', 'guruswad' ) ),
				array( 'ship', __( 'Import & Export', 'guruswad' ) ),
				array( 'globe', __( 'International Partnerships', 'guruswad' ) ),
				array( 'chart', __( 'Investment & Collaboration', 'guruswad' ) ),
			);
			foreach ( $gor_acts as $a ) :
				?>
				<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( $a[0] ); ?></span><h3><?php echo esc_html( $a[1] ); ?></h3></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$gor_team = get_posts( array( 'post_type' => 'gor_team', 'posts_per_page' => 12, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
if ( $gor_team ) :
	?>
<!-- Corporate objectives (client requirement 5) -->
<section class="section" id="objectives">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Corporate objectives', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'What we are building towards.', 'guruswad' ); ?></h2>
		</div>
		<div class="cards-2">
			<?php
			$gor_objectives = array(
				array( __( "Secure the farmer's income first", 'guruswad' ), __( 'Buy directly through the Farmer Producer Company at fair, announced prices, and widen the network without diluting what membership is worth.', 'guruswad' ) ),
				array( __( 'Process millets to export grade', 'guruswad' ), __( 'Hold every batch to one standard — cleaning, grading, de-hulling, puffing — so a buyer in Pune and a buyer in Dubai receive the same product.', 'guruswad' ) ),
				array( __( 'Build brands, not commodities', 'guruswad' ), __( 'Carry millets to the shelf under names people choose on purpose, beginning with MilletRush and extending to further brands on the same platform.', 'guruswad' ) ),
				array( __( 'Trade on documentation, not promises', 'guruswad' ), __( 'Meet international buyers with specifications, certifications and lead times ready, so an enquiry can become a shipment without a round of questions first.', 'guruswad' ) ),
			);
			foreach ( $gor_objectives as $gor_i => $gor_o ) :
				?>
				<article class="card" data-reveal>
					<span class="pillar__num"><?php echo esc_html( sprintf( '%02d', $gor_i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $gor_o[0] ); ?></h3>
					<p><?php echo esc_html( $gor_o[1] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<section class="section section--cream2">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'Leadership', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'The people behind the grain.', 'guruswad' ); ?></h2></div>
		<div class="team-grid">
			<?php foreach ( $gor_team as $m ) : ?>
				<div class="team-card" data-reveal>
					<div class="team-card__photo"><?php echo get_the_post_thumbnail( $m, 'gor-card' ); ?></div>
					<h4><?php echo esc_html( get_the_title( $m ) ); ?></h4>
					<span><?php echo esc_html( get_post_meta( $m->ID, 'gor_role', true ) ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'Expansion roadmap', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Now. Next. Future.', 'guruswad' ); ?></h2></div>
		<div class="cards-3">
			<div class="card" data-reveal><span class="eyebrow"><?php esc_html_e( 'Now', 'guruswad' ); ?></span><h3><?php esc_html_e( 'Build the core', 'guruswad' ); ?></h3><p><?php esc_html_e( 'FPO sourcing, millet processing and the MilletRush brand in India.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="eyebrow"><?php esc_html_e( 'Next', 'guruswad' ); ?></span><h3><?php esc_html_e( 'Scale and export', 'guruswad' ); ?></h3><p><?php esc_html_e( 'New product lines, wider distribution and first export markets.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="eyebrow"><?php esc_html_e( 'Future', 'guruswad' ); ?></span><h3><?php esc_html_e( 'A global millet house', 'guruswad' ); ?></h3><p><?php esc_html_e( 'A portfolio of brands and partnerships across international markets.', 'guruswad' ); ?></p></div>
		</div>
	</div>
</section>

<?php
gor_cta_band();
get_footer();
