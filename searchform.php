<?php
/**
 * Search form.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
$kumo_hybrid_search_id = wp_unique_id( 'kh-search-' );
?>
<form role="search" method="get" class="kh-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $kumo_hybrid_search_id ); ?>"><?php esc_html_e( 'Search this site', 'kumo-hybrid' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $kumo_hybrid_search_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search articles…', 'kumo-hybrid' ); ?>" required>
	<button type="submit" class="kh-button"><?php esc_html_e( 'Search', 'kumo-hybrid' ); ?></button>
</form>
