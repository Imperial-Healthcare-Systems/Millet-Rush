<?php
/**
 * Footer.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__media" aria-hidden="true">
		<video class="site-footer__video" data-lazy-video muted loop playsinline disablepictureinpicture disableremoteplayback preload="none"
			poster="<?php echo esc_url( GOR_URI . '/assets/img/photo/footer-field.jpg' ); ?>">
			<source data-src="<?php echo esc_url( GOR_URI . '/assets/video/footer-field.mp4' ); ?>" type="video/mp4">
		</video>
	</div>
	<div class="container">
		<div class="footer-grid">
			<div>
				<a class="brand-mark brand-mark--logo brand-mark--footer" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'MilletRush — Everyday Goodness of Millets', 'guruswad' ); ?>"><img src="<?php echo esc_url( GOR_URI . '/assets/img/logo/milletrush-color.svg' ); ?>" alt="<?php esc_attr_e( 'MilletRush — Everyday Goodness of Millets', 'guruswad' ); ?>" width="988" height="500"></a>
				<p style="margin-top:1.5rem;max-width:26em"><?php esc_html_e( 'A vertically integrated millet company connecting Maharashtra’s farmers to tables across the world — grown with care, processed with science, crafted into brands.', 'guruswad' ); ?></p>
				<form class="newsletter" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="gor_enquiry"><input type="hidden" name="enquiry_type" value="general"><input type="hidden" name="name" value="Newsletter subscriber"><input type="hidden" name="consent" value="1"><input type="hidden" name="message" value="Newsletter signup">
					<?php wp_nonce_field( 'gor_enquiry', 'gor_enquiry_nonce' ); ?>
					<label class="screen-reader-text" for="nl-email"><?php esc_html_e( 'Email', 'guruswad' ); ?></label>
					<input id="nl-email" type="email" name="email" required placeholder="<?php esc_attr_e( 'Your email for the Millet Journal', 'guruswad' ); ?>">
					<button type="submit"><?php esc_html_e( 'Join', 'guruswad' ); ?></button>
				</form>
				<div class="socials">
					<?php
					// Channel => icon. 'gbp' is the Google Business Profile listing, which
					// has no brand glyph in the set, so it borrows the map pin.
					$gor_channels = array(
						'facebook'  => 'facebook',
						'instagram' => 'instagram',
						'linkedin'  => 'linkedin',
						'x'         => 'x',
						'youtube'   => 'youtube',
						'gbp'       => 'pin',
					);
					$gor_labels = array( 'x' => 'X (Twitter)', 'gbp' => __( 'Google Business Profile', 'guruswad' ) );
					foreach ( $gor_channels as $gor_key => $gor_icon ) :
						$u = gor_opt( 'social_' . $gor_key );
						if ( ! $u ) {
							continue;
						}
						$gor_label = isset( $gor_labels[ $gor_key ] ) ? $gor_labels[ $gor_key ] : ucfirst( $gor_key );
						?>
						<a href="<?php echo esc_url( $u ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $gor_label ); ?>"><?php gor_the_icon( $gor_icon ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<div>
				<h4><?php esc_html_e( 'Company', 'guruswad' ); ?></h4>
				<?php gor_menu( 'footer', array(
					__( 'About Us', 'guruswad' )              => gor_page_url( 'about-us' ),
					__( 'Manufacturing', 'guruswad' )         => gor_page_url( 'manufacturing' ),
					__( 'Farmers & FPO Network', 'guruswad' ) => gor_page_url( 'farmers-fpo-network' ),
					__( 'News & Updates', 'guruswad' )        => gor_page_url( 'news' ),
					__( 'Contact Us', 'guruswad' )            => gor_page_url( 'contact-us' ),
				) ); ?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Business', 'guruswad' ); ?></h4>
				<?php gor_menu( 'footer2', array(
					__( 'Shop MilletRush', 'guruswad' )          => gor_shop_url(),
					__( 'Import & Export', 'guruswad' )          => gor_page_url( 'import-export' ),
					__( 'Request a Quote', 'guruswad' )          => gor_enquiry_url( 'export' ),
					__( 'Bulk Orders', 'guruswad' )              => gor_enquiry_url( 'bulk' ),
					__( 'Investment & Partnership', 'guruswad' ) => gor_page_url( 'investment-partnership' ),
				) ); ?>
				<?php if ( gor_opt( 'catalogue_pdf' ) ) : ?><p style="margin-top:1.25rem"><a class="link-arrow" href="<?php echo esc_url( gor_opt( 'catalogue_pdf' ) ); ?>" download><?php gor_the_icon( 'download' ); ?> <?php esc_html_e( 'Product catalogue', 'guruswad' ); ?></a></p><?php endif; ?>
			</div>
			<div>
				<h4><?php esc_html_e( 'Reach us', 'guruswad' ); ?></h4>
				<ul>
					<li><?php echo esc_html( gor_opt( 'address' ) ); ?></li>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', gor_opt( 'phone' ) ) ); ?>"><?php echo esc_html( gor_opt( 'phone' ) ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( gor_opt( 'email' ) ); ?>"><?php echo esc_html( gor_opt( 'email' ) ); ?></a></li>
					<li><a href="<?php echo esc_url( gor_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WhatsApp Business', 'guruswad' ); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="footer-word" aria-hidden="true">Guruswad Ocean</div>
	</div>
	<div class="container footer-bottom">
		<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( gor_opt( 'company_name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'guruswad' ); ?></span>
		<?php gor_menu( 'legal', array( __( 'Privacy Policy', 'guruswad' ) => get_privacy_policy_url() ? get_privacy_policy_url() : gor_page_url( 'privacy-policy' ), __( 'Terms', 'guruswad' ) => gor_page_url( 'terms' ), __( 'Shipping & Returns', 'guruswad' ) => gor_page_url( 'shipping-returns' ) ), 'legal-menu' ); ?>
		<span><?php esc_html_e( 'Crafted by', 'guruswad' ); ?> <a href="https://www.imperialtechinnovations.com" target="_blank" rel="noopener">Imperial Tech Innovations</a></span>
	</div>
</footer>

<a class="whatsapp-fab" href="<?php echo esc_url( gor_whatsapp_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'guruswad' ); ?>"><?php gor_the_icon( 'whatsapp' ); ?></a>

<nav class="mobile-bar" aria-label="<?php esc_attr_e( 'Quick actions', 'guruswad' ); ?>">
	<a href="<?php echo esc_url( gor_shop_url() ); ?>"><?php gor_the_icon( 'shop' ); ?><?php esc_html_e( 'Shop', 'guruswad' ); ?></a>
	<a href="<?php echo esc_url( gor_enquiry_url() ); ?>"><?php gor_the_icon( 'enquire' ); ?><?php esc_html_e( 'Enquire', 'guruswad' ); ?></a>
	<a href="<?php echo esc_url( gor_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php gor_the_icon( 'whatsapp' ); ?>WhatsApp</a>
	<a href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : gor_shop_url() ); ?>"><?php gor_the_icon( 'cart' ); ?><?php esc_html_e( 'Cart', 'guruswad' ); ?></a>
</nav>

<?php if ( function_exists( 'gor_mini_cart' ) ) { gor_mini_cart(); } ?>
<?php wp_footer(); ?>
</body>
</html>
