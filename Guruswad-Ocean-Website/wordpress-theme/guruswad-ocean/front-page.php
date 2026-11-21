<?php
/**
 * Home — "From Soil to Signature". Nine movements (see Blueprint §7.1).
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
$gor_video  = gor_opt( 'hero_video' );
$gor_poster = gor_opt( 'hero_poster' );
?>

<!-- 1. HERO -->
<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'guruswad' ); ?>">
	<?php
	// The film is texture; the poster carries the image on its own. main.js
	// attaches the source only on a wide screen and an unmetered connection,
	// so a phone is never handed several megabytes for a background.
	$gor_hero_poster = $gor_poster ? $gor_poster : GOR_URI . '/assets/img/photo/hero-soil-to-signature.jpg';
	$gor_hero_video  = $gor_video ? $gor_video : GOR_URI . '/assets/video/hero-millet.mp4';
	?>
	<div class="hero__media" aria-hidden="true">
		<video class="hero__video" data-lazy-video muted loop playsinline preload="none"
			poster="<?php echo esc_url( $gor_hero_poster ); ?>">
			<source data-src="<?php echo esc_url( $gor_hero_video ); ?>" type="video/mp4">
		</video>
	</div>
	<canvas class="hero__canvas" aria-hidden="true"></canvas>
	<div class="hero__art" aria-hidden="true"><?php gor_millet_art(); ?></div>

	<div class="container hero__inner">
		<span class="eyebrow"><?php esc_html_e( 'Guruswad Ocean · Est. in Maharashtra', 'guruswad' ); ?></span>
		<h1 class="hero__title">
			<span class="line"><span><?php esc_html_e( 'From Soil', 'guruswad' ); ?></span></span>
			<span class="line"><span><?php esc_html_e( 'to', 'guruswad' ); ?> <em><?php esc_html_e( 'Signature.', 'guruswad' ); ?></em></span></span>
		</h1>
			<span class="hero__rule" aria-hidden="true"></span>
		<div class="hero__foot">
			<div>
				<p class="hero__sub"><?php printf( esc_html__( 'Millets grown by %s farmers, processed with science and crafted for tables across the world.', 'guruswad' ), esc_html( number_format_i18n( (int) gor_opt( 'stat_farmers' ) ) ) ); ?></p>
				<div class="btn-row">
					<a class="btn btn--gold" href="<?php echo esc_url( gor_shop_url() ); ?>" data-magnetic><?php esc_html_e( 'Shop MilletRush', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
					<a class="btn btn--ghost-light" href="<?php echo esc_url( gor_page_url( 'investment-partnership' ) ); ?>" data-magnetic><?php esc_html_e( 'Partner with us', 'guruswad' ); ?></a>
				</div>
			</div>
			<div class="hero__meta">
				<div><strong><?php echo esc_html( number_format_i18n( (int) gor_opt( 'stat_farmers' ) ) ); ?></strong><?php esc_html_e( 'Farmers', 'guruswad' ); ?></div>
				<div><strong><?php echo esc_html( number_format_i18n( (int) gor_opt( 'stat_shg' ) ) ); ?></strong><?php esc_html_e( 'Women SHGs', 'guruswad' ); ?></div>
				<div><strong>ICAR</strong><?php esc_html_e( 'IIMR collaboration', 'guruswad' ); ?></div>
			</div>
		</div>
	</div>
	<div class="scroll-cue" aria-hidden="true"><?php esc_html_e( 'Scroll', 'guruswad' ); ?><i></i></div>
</section>

<!-- 2. MANIFESTO -->
<section class="manifesto" id="story">
	<div class="container">
		<div class="manifesto__grid">
			<div class="manifesto__aside">
				<span class="eyebrow" data-reveal><?php esc_html_e( 'Who we are', 'guruswad' ); ?></span>
				<span class="manifesto__folio" aria-hidden="true">01</span>
			</div>
			<div class="manifesto__body">
				<p class="manifesto__text"><?php
					printf(
						/* translators: %s is the emphasised phrase "from soil to signature". */
						esc_html__( 'An ancient grain, carried %s.', 'guruswad' ),
						'<em>' . esc_html__( 'from soil to signature', 'guruswad' ) . '</em>'
					);
				?></p>
				<p class="manifesto__lead" data-reveal><?php esc_html_e( 'Guruswad Ocean works with farmers at the root, refines millets with research-backed processing, and carries them to the world as brands people trust.', 'guruswad' ); ?></p>
				<div class="manifesto__sign" data-reveal><span><?php esc_html_e( 'Guruswad Ocean Private Limited', 'guruswad' ); ?></span></div>
			</div>
		</div>
		<div class="pillars">
			<div class="pillar" data-reveal><span class="pillar__num">01</span><h3><?php esc_html_e( 'Agriculture', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Direct sourcing through our Farmer Producer Company and farmer development programmes.', 'guruswad' ); ?></p></div>
			<div class="pillar" data-reveal><span class="pillar__num">02</span><h3><?php esc_html_e( 'Food Processing', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Millet processing and manufacturing with technology from ICAR–IIMR collaboration.', 'guruswad' ); ?></p></div>
			<div class="pillar" data-reveal><span class="pillar__num">03</span><h3><?php esc_html_e( 'Global Trade', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Export, import and private-label partnerships for buyers across markets.', 'guruswad' ); ?></p></div>
		</div>
	</div>
</section>

<!-- 3. THE GRAIN JOURNEY -->
<section class="journey" aria-label="<?php esc_attr_e( 'The grain journey', 'guruswad' ); ?>">
	<div class="container journey__head">
		<span class="eyebrow" data-reveal><?php esc_html_e( 'The Grain Journey', 'guruswad' ); ?></span>
		<h2 class="h-lg" data-split><?php esc_html_e( 'Five steps from a field in Maharashtra to a table anywhere.', 'guruswad' ); ?></h2>
	</div>
	<div class="journey__pin">
		<div class="journey__track">
			<?php
			$gor_steps = array(
				array( 'leaf', __( 'Field', 'guruswad' ), __( 'Climate-smart millets grown on rain-fed land — less water, more resilience.', 'guruswad' ) ),
				array( 'farmer', __( 'Farmer', 'guruswad' ), __( 'Our FPO buys directly from member farmers, with fair prices and field support.', 'guruswad' ) ),
				array( 'factory', __( 'Processing', 'guruswad' ), __( 'Cleaning, grading, de-hulling and puffing under strict quality control.', 'guruswad' ) ),
				array( 'grain', __( 'Product', 'guruswad' ), __( 'Crafted into MilletRush snacks, flours and ingredients people love.', 'guruswad' ) ),
				array( 'globe', __( 'World', 'guruswad' ), __( 'Packed for retail, bulk and export — ready for markets everywhere.', 'guruswad' ) ),
			);
			foreach ( $gor_steps as $i => $s ) :
				?>
				<article class="journey__panel">
					<div class="journey__visual" data-cursor="<?php echo esc_attr( $s[1] ); ?>"><?php gor_the_icon( $s[0] ); ?></div>
					<div class="journey__copy">
						<span class="journey__step"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<div><h3><?php echo esc_html( $s[1] ); ?></h3><p><?php echo esc_html( $s[2] ); ?></p></div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<svg class="journey__swoosh" viewBox="0 0 1600 120" preserveAspectRatio="none" aria-hidden="true"><path d="M0 90 C 300 20, 520 120, 820 70 S 1350 10, 1600 60"/></svg>
		<div class="journey__progress" aria-hidden="true"><i></i></div>
	</div>
</section>

<!-- 4. OUR BRANDS — MilletRush -->
<section class="section brand-feature" id="brands">
	<div class="brand-feature__bg" aria-hidden="true"></div>
	<div class="container split">
		<div class="brand-feature__stage">
			<span class="brand-feature__ring" aria-hidden="true"></span>
			<span class="brand-feature__ring" aria-hidden="true"></span>
			<img class="brand-feature__logo" src="<?php echo esc_url( GOR_URI . '/assets/img/logo/milletrush-color.svg' ); ?>" alt="MilletRush — Everyday Goodness of Millets" width="988" height="500">
		</div>
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Our Brands', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'MilletRush — everyday goodness, beautifully made.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Our first brand turns sorghum and other millets into light, crunchy puffs and wholesome staples — the taste of tradition, made for modern life.', 'guruswad' ); ?></p>
			<div class="chips" data-reveal>
				<span class="chip" style="--c:#e39a22"><i></i><?php esc_html_e( 'Sorghum (Jowar)', 'guruswad' ); ?></span>
				<span class="chip" style="--c:#631c0a"><i></i><?php esc_html_e( 'Pearl Millet (Bajra)', 'guruswad' ); ?></span>
				<span class="chip" style="--c:#c68d4f"><i></i><?php esc_html_e( 'Millet Snacks', 'guruswad' ); ?></span>
				<span class="chip" style="--c:#8db300"><i></i><?php esc_html_e( 'Processed Foods', 'guruswad' ); ?></span>
			</div>
			<div class="btn-row" data-reveal>
				<a class="btn" href="<?php echo esc_url( home_url( '/brands/milletrush/' ) ); ?>"><?php esc_html_e( 'Explore the brand', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( gor_shop_url() ); ?>"><?php esc_html_e( 'Shop now', 'guruswad' ); ?></a>
			</div>
		</div>
	</div>
</section>

<!-- Marquee -->
<div class="marquee" aria-hidden="true">
	<div class="marquee__track">
		<?php for ( $k = 0; $k < 2; $k++ ) : foreach ( array( __( 'Grown with care', 'guruswad' ), __( 'Processed with science', 'guruswad' ), __( 'Crafted into brands', 'guruswad' ), __( 'Shared at the root', 'guruswad' ), __( 'Ready for the world', 'guruswad' ) ) as $m ) : ?>
			<span class="marquee__item"><?php echo esc_html( $m ); ?> <?php gor_grain_mark(); ?></span>
		<?php endforeach; endfor; ?>
	</div>
</div>

<!-- 5. PRODUCT HIGHLIGHTS -->
<section class="section" id="products">
	<div class="container">
		<div class="split" style="align-items:end;margin-bottom:var(--s-6)">
			<div><span class="eyebrow" data-reveal><?php esc_html_e( 'The Collection', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Crunch that remembers where it came from.', 'guruswad' ); ?></h2></div>
			<div style="text-align:right" data-reveal><a class="link-arrow" href="<?php echo esc_url( gor_shop_url() ); ?>"><?php esc_html_e( 'View all products', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></div>
		</div>
		<div class="product-grid"><?php gor_home_products(); ?></div>
	</div>
</section>

<!-- 6. FARMER NETWORK IN NUMBERS -->
<section class="section section--dark" id="farmers">
	<div class="container">
		<div class="split split--wide-left" style="margin-bottom:var(--s-6)">
			<div><span class="eyebrow" data-reveal><?php esc_html_e( 'Farmers & FPO Network', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Growth that is shared at the root.', 'guruswad' ); ?></h2></div>
			<p class="lead" data-reveal><?php esc_html_e( 'Our Farmer Producer Company links smallholder farmers, women’s self-help groups and local entrepreneurs into one value chain — so the value of every grain returns to the village.', 'guruswad' ); ?></p>
		</div>
		<div class="stats">
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_farmers' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmers in our network', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_shg' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Women Self-Help Groups', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_entre' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Small entrepreneurs', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_fpo' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmer Producer Company', 'guruswad' ); ?></div></div>
		</div>
		<p style="margin-top:var(--s-5)" data-reveal><a class="btn btn--ghost-light" href="<?php echo esc_url( gor_page_url( 'farmers-fpo-network' ) ); ?>"><?php esc_html_e( 'Meet the network', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></p>
	</div>
</section>

<!-- 7. MANUFACTURING & SCIENCE -->
<section class="section" id="manufacturing">
	<div class="container split">
		<div><?php gor_art_panel( 'factory', __( 'Processing unit — replace with facility photography', 'guruswad' ), 0, 'cream' ); ?></div>
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Manufacturing & Processing', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Tradition, refined by research.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'From cleaning and grading to puffing and packing, every step is engineered for consistency, food safety and flavour.', 'guruswad' ); ?></p>
			<ul class="check-list" data-reveal>
				<li><?php esc_html_e( 'Millet cleaning, grading & de-hulling', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'Puffing and value-added product lines', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'In-house quality control at every batch', 'guruswad' ); ?></li>
				<li><?php esc_html_e( 'Product development for retail, bulk & export', 'guruswad' ); ?></li>
			</ul>
			<div class="badge-seal" data-reveal><?php gor_the_icon( 'flask' ); ?><div><strong><?php esc_html_e( 'Technology collaboration', 'guruswad' ); ?></strong><span><?php esc_html_e( 'ICAR – Indian Institute of Millets Research, Hyderabad', 'guruswad' ); ?></span></div></div>
			<p style="margin-top:var(--s-4)" data-reveal><a class="link-arrow" href="<?php echo esc_url( gor_page_url( 'manufacturing' ) ); ?>"><?php esc_html_e( 'Inside our facility', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a></p>
		</div>
	</div>
</section>

<!-- 8. GLOBAL TRADE -->
<?php get_template_part( 'template-parts/trade-map' ); ?>

<!-- 9. INVEST & PARTNER -->
<?php gor_cta_band(); ?>

<?php
get_footer();
