<?php
/**
 * Template Name: Full width (no title)
 * Template Post Type: page
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-main-landing kh-shell" tabindex="-1">
<?php
while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> aria-label="<?php echo esc_attr( get_the_title() ); ?>"><div class="entry-content is-layout-constrained">
	<?php
	the_content();
	wp_link_pages();
	?>
</div></article><?php endwhile; ?></main>
<?php get_footer(); ?>
