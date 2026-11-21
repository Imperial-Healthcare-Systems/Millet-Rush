<?php
/**
 * Template Name: Manufacturing & Processing
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();
gor_page_hero( array( 'eyebrow' => __( 'Manufacturing & Processing', 'guruswad' ), 'title' => __( 'Tradition, refined by <em>research</em>.', 'guruswad' ), 'lead' => __( 'Millet processing infrastructure, product development and quality control — built for retail, bulk and export.', 'guruswad' ) ) );
?>

<section class="section">
	<div class="container">
		<div class="section-head"><span class="eyebrow" data-reveal><?php esc_html_e( 'The process', 'guruswad' ); ?></span><h2 class="h-lg" data-split><?php esc_html_e( 'Six steps. Zero shortcuts.', 'guruswad' ); ?></h2></div>
		<div class="process">
			<?php
			$gor_steps = array(
				array( __( 'Cleaning', 'guruswad' ), __( 'Removal of stones, dust and foreign matter.', 'guruswad' ) ),
				array( __( 'Grading', 'guruswad' ), __( 'Sorting by size and quality for uniform processing.', 'guruswad' ) ),
				array( __( 'De-hulling', 'guruswad' ), __( 'Removing the outer husk while keeping nutrition.', 'guruswad' ) ),
				array( __( 'Processing', 'guruswad' ), __( 'Puffing, milling and value-added production.', 'guruswad' ) ),
				array( __( 'Quality control', 'guruswad' ), __( 'Batch checks for safety, moisture and taste.', 'guruswad' ) ),
				array( __( 'Packing', 'guruswad' ), __( 'Retail, bulk and export-grade packaging.', 'guruswad' ) ),
			);
			foreach ( $gor_steps as $s ) :
				?>
				<div class="process__step" data-reveal><h4><?php echo esc_html( $s[0] ); ?></h4><p><?php echo esc_html( $s[1] ); ?></p></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--cream2">
	<div class="container split">
		<div><?php gor_art_panel( 'factory', __( 'Facility & machinery — add photos', 'guruswad' ), get_post_thumbnail_id(), 'cream' ); ?></div>
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Infrastructure', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Built for consistency at scale.', 'guruswad' ); ?></h2>
			<div class="entry-content" style="margin:0" data-reveal>
				<?php
				if ( get_the_content() ) {
					the_content();
				} else {
					echo '<p>' . esc_html__( 'Add details of the processing unit, machinery and capacity in the page editor.', 'guruswad' ) . '</p>';
				}
				?>
			</div>
		</div>
	</div>
</section>

<section class="section section--dark">
	<div class="container split">
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Technology collaboration', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Backed by ICAR – Indian Institute of Millets Research.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'ICAR-IIMR, Hyderabad, is India’s national centre for millet research. Our collaboration brings research-backed processing know-how to our products.', 'guruswad' ); ?></p>
			<p data-reveal style="opacity:.6;font-size:.85rem"><?php esc_html_e( 'Describe the exact scope of the collaboration as documented in the agreement.', 'guruswad' ); ?></p>
		</div>
		<div class="cards-2">
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'flask' ); ?></span><h3><?php esc_html_e( 'Product development', 'guruswad' ); ?></h3><p><?php esc_html_e( 'New millet formats, recipes and shelf-life studies.', 'guruswad' ); ?></p></div>
			<div class="card" data-reveal><span class="card__icon"><?php gor_the_icon( 'shield' ); ?></span><h3><?php esc_html_e( 'Quality & safety', 'guruswad' ); ?></h3><p><?php esc_html_e( 'Documented standards and testing at every batch.', 'guruswad' ); ?></p></div>
		</div>
	</div>
</section>

<?php
$gor_certs = get_posts( array( 'post_type' => 'gor_cert', 'posts_per_page' => 12 ) );
if ( $gor_certs ) :
	?>
<section class="section">
	<div class="container">
		<div class="section-head section-head--center"><span class="eyebrow" data-reveal><?php esc_html_e( 'Certifications', 'guruswad' ); ?></span></div>
		<div class="cards-3">
			<?php foreach ( $gor_certs as $c ) : ?>
				<div class="card" data-reveal><?php echo get_the_post_thumbnail( $c, 'medium', array( 'style' => 'height:56px;width:auto;margin-bottom:1rem' ) ); ?><h3><?php echo esc_html( get_the_title( $c ) ); ?></h3><p><?php echo esc_html( get_the_excerpt( $c ) ); ?></p></div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
gor_cta_band();
get_footer();
