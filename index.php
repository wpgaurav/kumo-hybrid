<?php
/**
 * Blog and fallback template.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-shell" tabindex="-1">
	<header class="kh-page-header"><h1><?php echo esc_html( is_home() && get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : __( 'Latest stories', 'kumo-hybrid' ) ); ?></h1><p class="kh-lede"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p></header>
	<?php get_template_part( 'template-parts/collection' ); ?>
</main>
<?php get_footer(); ?>
