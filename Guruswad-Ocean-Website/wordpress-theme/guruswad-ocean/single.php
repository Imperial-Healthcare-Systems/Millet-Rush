<?php
/**
 * Single post / story.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	gor_page_hero( array( 'eyebrow' => get_the_date(), 'art' => ! has_post_thumbnail() ) );
	?>
	<article class="section">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="container" style="margin-top:calc(-1 * var(--section) / 2);margin-bottom:var(--s-6)"><div class="reveal-img" data-clip style="aspect-ratio:16/8"><?php the_post_thumbnail( 'gor-hero' ); ?></div></div>
		<?php endif; ?>
		<div class="container"><div class="entry-content"><?php the_content(); ?></div></div>
		<div class="container" style="max-width:760px;margin-top:var(--s-6);display:flex;justify-content:space-between;gap:1rem;border-top:1px solid var(--line);padding-top:var(--s-4)">
			<span><?php previous_post_link( '%link', '← %title' ); ?></span>
			<span><?php next_post_link( '%link', '%title →' ); ?></span>
		</div>
	</article>
	<?php
endwhile;
get_footer();
