<?php
/**
 * Thin-line icon set (1.5px, currentColor) — Guruswad Ocean.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return an inline SVG icon.
 *
 * @param string $name  Icon key.
 * @param string $class Optional class.
 * @return string
 */
function gor_icon( $name, $class = '' ) {
	$p = array(
		'arrow'     => '<path d="M4 12h16M14 6l6 6-6 6"/>',
		'arrow-up'  => '<path d="M7 17 17 7M8 7h9v9"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'cart'      => '<path d="M5 7h14l-1.2 11.2a2 2 0 0 1-2 1.8H8.2a2 2 0 0 1-2-1.8L5 7Z"/><path d="M9 10V6a3 3 0 0 1 6 0v4"/>',
		'search'    => '<circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21c1.5-4 4.5-6 8-6s6.5 2 8 6"/>',
		'grain'     => '<path d="M12 22V9"/><ellipse cx="12" cy="5" rx="2" ry="3"/><ellipse cx="8.2" cy="9.5" rx="1.8" ry="2.7" transform="rotate(-35 8.2 9.5)"/><ellipse cx="15.8" cy="9.5" rx="1.8" ry="2.7" transform="rotate(35 15.8 9.5)"/><ellipse cx="8.4" cy="14.5" rx="1.8" ry="2.7" transform="rotate(-35 8.4 14.5)"/><ellipse cx="15.6" cy="14.5" rx="1.8" ry="2.7" transform="rotate(35 15.6 14.5)"/>',
		'leaf'      => '<path d="M5 19C5 10 10 4 20 4c0 10-6 15-15 15Z"/><path d="M5 19 13 11"/>',
		'farmer'    => '<path d="M5 9h14"/><path d="M7 9c0-3 2.2-5 5-5s5 2 5 5"/><path d="M8.5 11a3.5 3.5 0 0 0 7 0"/><path d="M4 21c1.2-3.6 4.2-5.5 8-5.5s6.8 1.9 8 5.5"/>',
		'factory'   => '<path d="M3 21V10l5 3V10l5 3V10l5 3V4h3v17H3Z"/><path d="M7 17h2M12 17h2M17 17h1"/>',
		'flask'     => '<path d="M9 3h6M10 3v6L4.5 19a1.5 1.5 0 0 0 1.3 2h12.4a1.5 1.5 0 0 0 1.3-2L14 9V3"/><path d="M7 15h10"/>',
		'ship'      => '<path d="M3 17l2 4h14l2-4H3Z"/><path d="M5 17V11h14v6"/><path d="M9 11V6h6v5"/><path d="M12 3v3"/>',
		'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.8 3 2.8 15 0 18M12 3c-2.8 3-2.8 15 0 18"/>',
		'handshake' => '<path d="m2 11 4-4 4 2 3-2 4 1 5 3"/><path d="m6 7 0 6 5 5c.8.8 2 .8 2.8 0l4-4c.8-.8.8-2 0-2.8L13 6"/><path d="m10 15 2 2M12 13l2 2"/>',
		'chart'     => '<path d="M4 20V4M4 20h16"/><path d="m7 15 4-4 3 3 5-6"/>',
		'shield'    => '<path d="M12 3 4 6v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V6l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
		'truck'     => '<path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="17.5" r="1.8"/><circle cx="17" cy="17.5" r="1.8"/>',
		'receipt'   => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3Z"/><path d="M9 8h6M9 12h6"/>',
		'seal'      => '<circle cx="12" cy="10" r="6"/><path d="m9 15-1.5 6L12 19l4.5 2L15 15"/><path d="m9.5 10 1.8 1.8L15 8.3"/>',
		'women'     => '<circle cx="8" cy="7" r="3"/><circle cx="16" cy="7" r="3"/><path d="M2.5 20c.8-4 3-6 5.5-6s4.7 2 5.5 6M10.5 20c.8-4 3-6 5.5-6s4.7 2 5.5 6"/>',
		'spark'     => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M5.6 5.6l2.8 2.8M15.6 15.6l2.8 2.8M5.6 18.4l2.8-2.8M15.6 8.4l2.8-2.8"/>',
		'doc'       => '<path d="M6 3h8l4 4v14H6V3Z"/><path d="M14 3v4h4M9 12h6M9 16h6"/>',
		'download'  => '<path d="M12 4v11M7 10l5 5 5-5M5 20h14"/>',
		'phone'     => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1Z"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="m3 7 9 6 9-6"/>',
		'pin'       => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12Z"/><circle cx="12" cy="9" r="2.5"/>',
		'enquire'   => '<path d="M4 5h16v11H8l-4 4V5Z"/><path d="M8 9h8M8 12h5"/>',
		'shop'      => '<path d="M4 9h16l-1-5H5L4 9Z"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>',
		'whatsapp'  => '<path fill="currentColor" stroke="none" d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8s-.4-.1-.6.1-.6.8-.8 1-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.3-.4.3-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 2.9 2.9 0 0 0-.9 2.2 5.1 5.1 0 0 0 1 2.7 11.6 11.6 0 0 0 4.5 4c1.7.7 2.3.8 3.2.7a2.7 2.7 0 0 0 1.8-1.3 2.2 2.2 0 0 0 .2-1.3c-.1-.1-.3-.2-.5-.3Z"/>',
		'instagram' => '<path fill="currentColor" stroke="none" d="M12 4.62c2.4 0 2.69.01 3.64.05.88.04 1.35.19 1.67.31.42.16.72.36 1.03.67.31.31.51.61.67 1.03.12.32.27.79.31 1.67.04.95.05 1.24.05 3.65s-.01 2.7-.05 3.65c-.4.88-.19 1.35-.31 1.67-.16.42-.36.72-.67 1.03-.31.31-.61.51-1.03.67-.32.12-.79.27-1.67.31-.95.04-1.23.05-3.64.05s-2.69-.01-3.64-.05c-.88-.04-1.35-.19-1.67-.31a2.77 2.77 0 0 1-1.03-.67 2.77 2.77 0 0 1-.67-1.03c-.12-.32-.27-.79-.31-1.67-.04-.95-.05-1.24-.05-3.65s.01-2.7.05-3.65c.04-.88.19-1.35.31-1.67.16-.42.36-.72.67-1.03.31-.31.61-.51 1.03-.67.32-.12.79-.27 1.67-.31.95-.04 1.23-.05 3.64-.05M12 3c-2.44 0-2.75.01-3.71.05-.96.05-1.61.2-2.19.42-.6.23-1.1.54-1.61 1.05-.5.5-.82 1.01-1.05 1.6-.22.58-.37 1.24-.42 2.2C3 9.28 3 9.58 3 12.02s.01 2.75.05 3.71c.5.96.2 1.61.42 2.19.23.6.54 1.1 1.05 1.61.5.5 1.01.82 1.6 1.05.58.22 1.24.37 2.2.42.96.04 1.27.05 3.71.05s2.75-.01 3.71-.05c.96-.05 1.61-.2 2.19-.42.6-.23 1.1-.54 1.61-1.05.5-.5.82-1.01 1.05-1.6.22-.58.37-1.24.42-2.2.04-.96.05-1.27.05-3.71s-.01-2.75-.05-3.71c-.05-.96-.2-1.61-.42-2.19a4.4 4.4 0 0 0-1.05-1.61 4.4 4.4 0 0 0-1.6-1.05c-.58-.22-1.24-.37-2.2-.42C14.78 3.01 14.47 3 12.03 3Zm0 4.38a4.63 4.63 0 1 0 0 9.26 4.63 4.63 0 0 0 0-9.26Zm0 7.63a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm5.89-7.81a1.08 1.08 0 1 1-2.16 0 1.08 1.08 0 0 1 2.16 0Z"/>';
		'facebook'  => '<path fill="currentColor" stroke="none" d="M13.5 21.5V13h2.86l.43-3.32H13.5V7.56c0-.96.27-1.62 1.65-1.62h1.76V2.97a23.6 23.6 0 0 0-2.57-.13c-2.54 0-4.28 1.55-4.28 4.4V9.68H7.19V13h2.87v8.5h3.44Z"/>',
		'linkedin'  => '<path fill="currentColor" stroke="none" d="M6.94 8.6H3.56V21h3.38V8.6ZM5.25 3a1.96 1.96 0 1 0 0 3.92 1.96 1.96 0 0 0 0-3.92ZM21 13.68c0-3.2-1.71-4.69-3.99-4.69-1.84 0-2.66 1.01-3.12 1.72V8.6H10.5c.05.95 0 12.4 0 12.4h3.39v-6.93c0-.3.02-.61.11-.83.25-.61.8-1.24 1.74-1.24 1.23 0 1.72.94 1.72 2.31V21H21v-7.32Z"/>',
		'x'         => '<path fill="currentColor" stroke="none" d="M17.53 3h3.2l-6.99 7.99L21.96 21h-6.44l-5.04-6.59L4.7 21H1.5l7.48-8.55L1.04 3h6.6l4.56 6.03L17.53 3Zm-1.12 16.08h1.77L7.68 4.83H5.78l10.63 14.25Z"/>',
		'youtube'   => '<path fill="currentColor" stroke="none" d="M21.6 7.2a2.5 2.5 0 0 0-1.76-1.77C18.25 5 12 5 12 5s-6.25 0-7.84.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.76 1.77C5.75 19 12 19 12 19s6.25 0 7.84-.43a2.5 2.5 0 0 0 1.76-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8ZM10 15.5v-7l6 3.5-6 3.5Z"/>',
	);

	if ( ! isset( $p[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="icon icon-%1$s %2$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		esc_attr( $class ),
		$p[ $name ]
	);
}

/**
 * Echo an inline SVG icon.
 *
 * @param string $name  Icon key.
 * @param string $class Optional class.
 * @return void
 */
function gor_the_icon( $name, $class = '' ) {
	echo gor_icon( $name, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built above from a fixed set.
}

/**
 * The hero's millet illustration.
 *
 * Read from assets/img/hero-millet.svg rather than pasted in here: it is 42 KB
 * of path data, and keeping it as a file means a designer can replace the
 * artwork without touching PHP. Cached per request because front-page.php and
 * the page-hero helper can both ask for it on one page.
 *
 * @return void
 */
function gor_millet_art() {
	static $svg = null;
	if ( null === $svg ) {
		$file = GOR_DIR . '/assets/img/hero-millet.svg';
		$svg  = is_readable( $file ) ? file_get_contents( $file ) : ''; // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme asset, not a remote request.
	}
	echo $svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- a fixed SVG shipped with the theme.
}

/**
 * The small grain mark used in the marquee and on the page-transition curtain.
 *
 * @return void
 */
function gor_grain_mark() {
	echo '<svg viewBox="0 0 40 40" fill="currentColor" aria-hidden="true" focusable="false">'
		. '<ellipse cx="20" cy="7" rx="3.4" ry="5"/>'
		. '<ellipse cx="13.5" cy="14" rx="3" ry="4.5" transform="rotate(-38 13.5 14)"/>'
		. '<ellipse cx="26.5" cy="14" rx="3" ry="4.5" transform="rotate(38 26.5 14)"/>'
		. '<ellipse cx="13.8" cy="22.5" rx="3" ry="4.5" transform="rotate(-38 13.8 22.5)"/>'
		. '<ellipse cx="26.2" cy="22.5" rx="3" ry="4.5" transform="rotate(38 26.2 22.5)"/>'
		. '<path d="M20 13v25" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'
		. '</svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup.
}
