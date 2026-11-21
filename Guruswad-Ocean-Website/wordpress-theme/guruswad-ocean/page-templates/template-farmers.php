<?php
/**
 * Template Name: Farmers & FPO Network
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Farmers & FPO Network', 'guruswad' ), 'title' => __( 'Growth that is shared at the <em>root</em>.', 'guruswad' ), 'lead' => __( 'CSC Sangli Jath MH Vishwabhumi Farmer Producer Company Ltd. brings smallholder farmers into membership and aggregates what they grow. Guruswad Agriculture and Food Association trains them, organises women’s self-help groups and promotes organic practice. Together they are the base of the chain.', 'guruswad' ) , 'variant' => 'farmers') );
?>

<section class="section">
	<div class="container">
		<div class="stats">
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_farmers' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmers in our network', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_shg' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Women Self-Help Groups', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_entre' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Small entrepreneurs', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_fpo' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmer Producer Company', 'guruswad' ); ?><br><span style="opacity:.6"><?php esc_html_e( 'CSC Sangli Jath MH Vishwabhumi FPC Ltd.', 'guruswad' ); ?></span></div></div>
		</div>
	</div>
</section>

<section class="section section--cream2">
	<div class="container split">
		<div><?php gor_art_panel( 'farmer', __( 'Farmer portrait — add with consent', 'guruswad' ), get_post_thumbnail_id() ); ?></div>
		<div>
			<blockquote class="quote-block" data-reveal><?php esc_html_e( '“The grain from our fields now reaches the world.”', 'guruswad' ); ?><cite><span lang="mr">“आमच्या शेतातील धान्य आता जगभर पोहोचते.”</span> <?php esc_html_e( '— FPO member (placeholder)', 'guruswad' ); ?></cite></blockquote>
		</div>
	</div>
</section>

<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'What we do together', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'An ecosystem, not a supply chain.', 'guruswad' ); ?></h2></div>
		<div class="cards-3">
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'farmer' ); ?></span><h3><?php esc_html_e( 'Direct sourcing', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Fair, transparent procurement straight from member farmers.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'women' ); ?></span><h3><?php esc_html_e( 'Women SHGs', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Self-help groups engaged in processing and value addition.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'spark' ); ?></span><h3><?php esc_html_e( 'Entrepreneurs', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Small rural enterprises building millet businesses.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'leaf' ); ?></span><h3><?php esc_html_e( 'Farmer development', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Training, better seed and climate-smart practices.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'grain' ); ?></span><h3><?php esc_html_e( 'Value addition', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Turning raw millets into higher-value products.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'phone' ); ?></span><h3><?php esc_html_e( 'Join the network', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Farmers can call or WhatsApp us directly.', 'guruswad' ); ?></p><a class="link-arrow" href="<?php echo esc_url( gor_whatsapp_url( 'नमस्कार, मला शेतकरी नेटवर्कमध्ये सहभागी व्हायचे आहे.' ) ); ?>" target="_blank" rel="noopener">WhatsApp <?php gor_the_icon( 'arrow' ); ?></a></div>
		</div>
	</div>
</section>

<?php
$gor_stories = get_posts( array( 'post_type' => 'gor_story', 'posts_per_page' => 8 ) );
if ( $gor_stories ) :
	?>
<section class="section section--dark">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'Stories', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Voices from the field.', 'guruswad' ); ?></h2></div>
		<div class="story-rail">
			<?php foreach ( $gor_stories as $st ) : ?>
				<a class="story" href="<?php echo esc_url( get_permalink( $st ) ); ?>" data-cursor="<?php esc_attr_e( 'Read', 'guruswad' ); ?>">
					<div class="story__media reveal-img"><?php echo get_the_post_thumbnail( $st, 'gor-wide' ); ?></div>
					<div class="story__body"><h3><?php echo esc_html( get_the_title( $st ) ); ?></h3><p><?php echo esc_html( get_the_excerpt( $st ) ); ?></p></div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( get_the_content() ) : ?>
<section class="section"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<!-- WOMEN'S EMPOWERMENT & RURAL EMPLOYMENT — client letter §2.
     Asked for as a section of its own, not as one card in a grid of six,
     because it is a result of the business rather than a feature of it. -->
<section class="section section--dark" id="women">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Women’s empowerment & rural employment', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Paid work, in the village, for the women who do it.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Processing is where a crop gains most of its value, and processing is work. Keeping that work in the district — and routing it through women’s self-help groups rather than through a contractor — is how the value reaches the people who grew the grain.', 'guruswad' ); ?></p>
		</div>
		<div class="cards-2">
			<article class="card" data-reveal>
				<span class="card__icon"><?php gor_the_icon( 'women' ); ?></span>
				<h3><?php esc_html_e( 'Women’s self-help groups', 'guruswad' ); ?></h3>
				<p><?php esc_html_e( 'Groups organised through Guruswad Agriculture and Food Association, engaged in the work rather than consulted about it.', 'guruswad' ); ?></p>
				<ul class="check-list">
					<li><?php esc_html_e( 'Cleaning, grading and value addition', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Packing and finishing work contracted to the group', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Training in processing, hygiene and food safety', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Earnings paid to the group, not to an intermediary', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'First call on capacity as the CFC is commissioned', 'guruswad' ); ?></li>
				</ul>
			</article>
			<article class="card" data-reveal>
				<span class="card__icon"><?php gor_the_icon( 'handshake' ); ?></span>
				<h3><?php esc_html_e( 'Rural employment generation', 'guruswad' ); ?></h3>
				<p><?php esc_html_e( 'A millet processed in the district employs people in the district. One moved to a city plant employs them there instead.', 'guruswad' ); ?></p>
				<ul class="check-list">
					<li><?php esc_html_e( 'Processing and packing work kept local', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Small rural enterprises supported, not displaced', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Shared equipment, so a small operator can take real orders', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Skills that remain after a season ends', 'guruswad' ); ?></li>
				</ul>
			</article>
		</div>
	</div>
</section>

<!-- PRODUCE AND WHAT IS MADE FROM IT — client letter §2, and §5 for the
     statuses: nothing still in development may read as available. -->
<section class="section" id="produce">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Produce and products', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'What the fields give us, and what we make of it.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Grain is bought from member farmers through the producer company, then cleaned, graded and processed. What the grain becomes is where the farmer’s price comes from.', 'guruswad' ); ?></p>
		</div>
		<div class="yield-flow" data-reveal>
			<div>
				<h3><?php esc_html_e( 'Sourced from our farmers', 'guruswad' ); ?></h3>
				<ul class="check-list">
					<li><?php esc_html_e( 'Sorghum — jowar', 'guruswad' ); ?></li>
					<li><?php esc_html_e( 'Pearl millet — bajra', 'guruswad' ); ?></li>
				</ul>
				<p class="team-note"><?php esc_html_e( 'The full cropping list, season by season, to be supplied by the client.', 'guruswad' ); ?></p>
			</div>
			<div class="yield-flow__arrow" aria-hidden="true"><?php gor_the_icon( 'arrow' ); ?></div>
			<div>
				<h3><?php esc_html_e( 'Value added from it', 'guruswad' ); ?></h3>
				<ul class="check-list">
					<li><?php esc_html_e( 'Puffed sorghum snacks', 'guruswad' ); ?> <span class="status-pill status-pill--live"><?php esc_html_e( 'Available', 'guruswad' ); ?></span></li>
					<li><?php esc_html_e( 'Stone-ground millet flours', 'guruswad' ); ?> <span class="status-pill status-pill--live"><?php esc_html_e( 'Available', 'guruswad' ); ?></span></li>
					<li><?php esc_html_e( 'Rava', 'guruswad' ); ?> <span class="status-pill status-pill--planned"><?php esc_html_e( 'Planned', 'guruswad' ); ?></span></li>
					<li><?php esc_html_e( 'Instant mixes', 'guruswad' ); ?> <span class="status-pill status-pill--planned"><?php esc_html_e( 'Planned', 'guruswad' ); ?></span></li>
				</ul>
				<p class="team-note"><?php esc_html_e( 'Rava and instant mixes are planned through the Common Facility Centre and are not yet on sale.', 'guruswad' ); ?></p>
			</div>
		</div>
	</div>
</section>

<!-- ORGANIC FARMING — client letter §2. The client was explicit: promote
     organic farming, and do not describe products as Certified Organic
     without valid certification. Do not soften the disclosure below. -->
<section class="section section--cream2" id="organic">
	<div class="container split">
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Organic farming', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Organic practice, promoted field by field.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Guruswad Agriculture and Food Association promotes organic farming across the network — the method, the inputs and the training behind it, rather than a label on a pack.', 'guruswad' ); ?></p>
			<ul class="check-list" data-reveal>
				<li><?php esc_html_e( 'Training in organic method and input preparation', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'Soil health and crop rotation on rain-fed land', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'Millets as a crop that already asks little of the soil', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'Farmers supported towards certification where they want it', 'guruswad' ); ?></li>
			</ul>
		</div>
		<div>
			<?php
			gor_disclosure(
				'shield',
				'<p><strong>' . esc_html__( 'We do not describe any product on this site as Certified Organic.', 'guruswad' ) . '</strong> '
				. esc_html__( 'Promotion of organic farming is a development activity: it is what the Association teaches and encourages across the network.', 'guruswad' ) . '</p>'
				. '<p>' . esc_html__( 'Produce or a product will be called organic here only when a valid certification covers that specific lot, and the certificate will be named when it is. Until then, organic practice is described as practice — which is what it is.', 'guruswad' ) . '</p>'
			);
			?>
		</div>
	</div>
</section>

<?php
gor_cta_band();
get_footer();
