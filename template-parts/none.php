<?php
/**
 * Empty query.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="kh-empty"><h2><?php esc_html_e( 'Nothing here yet', 'kumo-hybrid' ); ?></h2><p><?php esc_html_e( 'Try a different search or return to the homepage.', 'kumo-hybrid' ); ?></p><?php get_search_form(); ?><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'kumo-hybrid' ); ?></a></section>
