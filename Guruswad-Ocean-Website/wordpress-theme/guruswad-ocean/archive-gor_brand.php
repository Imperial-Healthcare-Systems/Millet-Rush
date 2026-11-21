<?php
/**
 * Our Brands — portfolio.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
gor_page_hero( array( 'eyebrow' => __( 'Our Brands', 'guruswad' ), 'title' => __( 'A house of millet <em>brands</em>.', 'guruswad' ), 'lead' => __( 'Each brand carries the same promise — honest grain, crafted well — in its own voice.', 'guruswad' ) ) );
?>
<section class="section">
	<div class="container">
		<?php
		$gor_i = 0;
		while ( have_posts() ) :
			the_post();
			$gor_logo = get_post_meta( get_the_ID(), 'gor_logo_url', true );
			$gor_acc  = get_post_meta( get_the_ID(), 'gor_accent', true );
			?>
			<article class="split" style="padding-block:var(--s-6);border-bottom:1px solid var(--line)<?php echo $gor_i % 2 ? ';direction:rtl' : ''; ?>">
				<a href="<?php the_permalink(); ?>" class="brand-feature__stage" style="direction:ltr;background:radial-gradient(60% 60% at 50% 50%, <?php echo esc_attr( $gor_acc ? $gor_acc : '#e39a22' ); ?>33, transparent 70%)" data-cursor="<?php esc_attr_e( 'Explore', 'guruswad' ); ?>">
					<?php if ( $gor_logo ) : ?><img class="brand-feature__logo" src="<?php echo esc_url( $gor_logo ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy"><?php else : ?><?php the_post_thumbnail( 'gor-panel' ); ?><?php endif; ?>
				</a>
				<div style="direction:ltr">
					<span class="eyebrow" data-reveal><?php echo esc_html( get_post_meta( get_the_ID(), 'gor_tagline', true ) ); ?></span>
					<h2 class="h-lg" data-split><?php the_title(); ?></h2>
					<p class="lead" data-reveal><?php echo esc_html( get_the_excerpt() ); ?></p>
					<a class="btn" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Enter the brand world', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
				</div>
			</article>
			<?php
			++$gor_i;
		endwhile;
		?>
		<div class="section-head section-head--center" style="margin:var(--s-7) auto 0"><p class="lead" style="margin-inline:auto"><?php esc_html_e( 'More brands are on the way.', 'guruswad' ); ?></p></div>
	</div>
</section>
<?php
get_footer();
