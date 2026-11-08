<?php
/**
 * Template helpers.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;

/**
 * Check whether the current template has an active sidebar.
 *
 * @return bool
 */
function kumo_hybrid_has_sidebar() {
	return kumo_hybrid_option( 'sidebar' ) && is_active_sidebar( 'sidebar' ) && ( is_single() || is_home() || is_archive() || is_search() );
}

/**
 * Return an SVG from a fixed, theme-owned set.
 *
 * @param string $name Icon name.
 * @return string
 */
function kumo_hybrid_icon( $name ) {
	$paths = array(
		'menu'    => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'mode'    => '<path d="M21 12.8A9 9 0 0 1 11.2 3 9 9 0 1 0 21 12.8Z"/>',
		'arrow'   => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
		'chevron' => '<path d="m6 9 6 6 6-6"/>',
		'search'  => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
	);
	// All SVG paths are theme-owned, never user-supplied.
	return isset( $paths[ $name ] ) ? '<svg class="kh-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>' : '';
}

/**
 * Render optional publication date and author metadata.
 */
function kumo_hybrid_post_meta() {
	if ( ! kumo_hybrid_option( 'show_meta' ) || 'post' !== get_post_type() ) {
		return;
	}
	?>
	<div class="kh-post-meta"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><span aria-hidden="true">/</span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></div>
	<?php
}

/**
 * Render the native page menu when no menu is assigned.
 */
function kumo_hybrid_menu_fallback() {
	printf( '<ul class="kh-menu"><li><a href="%s">%s</a></li>', esc_url( home_url( '/' ) ), esc_html__( 'Home', 'kumo-hybrid' ) );
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 2,
		)
	);
	echo '</ul>';
}
