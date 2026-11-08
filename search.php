<?php
/**
 * Search results.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-shell" tabindex="-1">
	<header class="kh-page-header"><h1><?php /* translators: %s: search query. */ printf( esc_html__( 'Search results for: %s', 'kumo-hybrid' ), esc_html( get_search_query() ) ); ?></h1><?php get_search_form(); ?></header>
	<?php get_template_part( 'template-parts/collection' ); ?>
</main>
<?php get_footer(); ?>
