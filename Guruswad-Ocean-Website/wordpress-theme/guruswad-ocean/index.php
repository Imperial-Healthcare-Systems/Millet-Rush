<?php
/**
 * News & Updates / archives (Journal layout).
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();

if ( is_search() ) {
	/* translators: %s search term */
	$gor_title = sprintf( __( 'Results for “%s”', 'guruswad' ), get_search_query() );
} elseif ( is_archive() ) {
	$gor_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$gor_title = __( 'News & <em>Updates</em>', 'guruswad' );
}
gor_page_hero( array( 'eyebrow' => __( 'The Millet Journal', 'guruswad' ), 'title' => $gor_title, 'lead' => is_home() ? __( 'Company news, trade fairs, farmer stories and everything millets.', 'guruswad' ) : '' ) );
?>
<section class="section">
	<div class="container">
		<?php if ( is_search() ) : ?>
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="newsletter" style="max-width:640px;margin-bottom:var(--s-6);border-color:var(--line)">
				<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search products, news, stories…', 'guruswad' ); ?>" style="color:var(--ink)">
				<button type="submit" style="color:var(--forest)"><?php esc_html_e( 'Search', 'guruswad' ); ?></button>
			</form>
		<?php endif; ?>
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'post-card' ); ?> data-reveal>
						<a class="post-card__media" href="<?php the_permalink(); ?>" data-cursor="<?php esc_attr_e( 'Read', 'guruswad' ); ?>">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'gor-wide' );
							} else {
								echo '<span class="art-panel art-panel--cream" style="aspect-ratio:auto;height:100%">';
								gor_the_icon( 'grain' );
								echo '</span>';
							}
							?>
						</a>
						<div class="post-card__meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 220 ) ) ); ?> <?php esc_html_e( 'min read', 'guruswad' ); ?></div>
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p><?php echo esc_html( get_the_excerpt() ); ?></p>
					</article>
				<?php endwhile; ?>
			</div>
			<nav class="pagination"><?php echo wp_kses_post( paginate_links( array( 'prev_text' => '←', 'next_text' => '→' ) ) ); ?></nav>
		<?php else : ?>
			<p class="lead"><?php esc_html_e( 'Nothing here yet. Please check back soon.', 'guruswad' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
