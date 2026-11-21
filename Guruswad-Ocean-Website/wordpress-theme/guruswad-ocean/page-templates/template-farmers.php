<?php
/**
 * Template Name: Farmers & FPO Network
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Farmers & FPO Network', 'guruswad' ), 'title' => __( 'Growth that is shared at the <em>root</em>.', 'guruswad' ), 'lead' => __( 'Our Farmer Producer Company connects smallholder farmers, women’s self-help groups and local entrepreneurs into one millet value chain.', 'guruswad' ) ) );
?>

<section class="section">
	<div class="container">
		<div class="stats">
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_farmers' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmers in our network', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_shg' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Women Self-Help Groups', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_entre' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Small entrepreneurs', 'guruswad' ); ?></div></div>
			<div class="stat"><div class="stat__num" data-count="<?php echo esc_attr( (int) gor_opt( 'stat_fpo' ) ); ?>">0</div><div class="stat__label"><?php esc_html_e( 'Farmer Producer Company', 'guruswad' ); ?></div></div>
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

<?php
gor_cta_band();
get_footer();
