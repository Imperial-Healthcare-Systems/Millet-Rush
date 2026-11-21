<?php
/**
 * Default page (editor-built pages: privacy, terms, any new page).
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	gor_page_hero( array( 'art' => false ) );
	?>
	<section class="section">
		<div class="container"><div class="entry-content"><?php the_content(); ?></div></div>
	</section>
	<?php
endwhile;
get_footer();
