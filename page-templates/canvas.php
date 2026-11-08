<?php
/**
 * Template Name: Canvas (content only)
 * Template Post Type: page
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html><html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo( 'charset' ); ?>"><meta name="viewport" content="width=device-width, initial-scale=1"><?php wp_head(); ?></head><body <?php body_class( 'kh-canvas-page' ); ?>><?php wp_body_open(); ?><a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'kumo-hybrid' ); ?></a><main id="main" tabindex="-1">
<?php
while ( have_posts() ) :
	the_post();
	?>
	<div class="entry-content is-layout-constrained">
	<?php
	the_content();
	wp_link_pages();
	?>
</div><?php endwhile; ?></main><?php wp_footer(); ?></body></html>
