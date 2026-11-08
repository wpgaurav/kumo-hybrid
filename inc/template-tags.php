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
