<?php
/**
 * Missing page.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-shell" tabindex="-1"><section class="kh-empty kh-404"><p class="kh-eyebrow">404</p><h1><?php esc_html_e( 'This page took a different path.', 'kumo-hybrid' ); ?></h1><p><?php esc_html_e( 'The address may have changed. Search the site or start again from the homepage.', 'kumo-hybrid' ); ?></p><?php get_search_form(); ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'kumo-hybrid' ); ?></a></section></main>
<?php get_footer(); ?>
