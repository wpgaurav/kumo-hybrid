<?php
/**
 * Default page.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main" class="kh-main kh-shell" tabindex="-1">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'kh-page' ); ?>>
		<header class="kh-entry-header"><?php the_title( '<h1>', '</h1>' ); ?></header>
		<?php
		if ( has_post_thumbnail() && ! post_password_required() ) :
			?>
			<div class="kh-featured-image"><?php the_post_thumbnail( 'full' ); ?></div><?php endif; ?>
		<div class="entry-content kh-prose is-layout-constrained">
		<?php
		the_content();
		wp_link_pages();
		?>
		</div>
	</article>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		} endwhile;
	?>
</main>
<?php get_footer(); ?>
