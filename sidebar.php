<?php
/**
 * Optional widget sidebar.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
if ( ! kumo_hybrid_has_sidebar() ) {
	return; }
?>
<aside class="kh-sidebar" aria-label="<?php esc_attr_e( 'Sidebar', 'kumo-hybrid' ); ?>"><?php dynamic_sidebar( 'sidebar' ); ?></aside>
