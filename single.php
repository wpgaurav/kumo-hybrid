<?php
/**
 * Single post.
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
	<div class="kh-content-layout"><div>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="kh-entry-header">
		<?php
		the_title( '<h1>', '</h1>' );
		kumo_hybrid_post_meta();
		?>
		</header>
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
		<?php
		if ( has_tag() ) :
			?>
			<footer class="kh-entry-footer"><?php the_tags( '<span class="kh-tags">', ' ', '</span>' ); ?></footer><?php endif; ?>
	</article>
		<?php
		the_post_navigation(
			array(
				'prev_text' => '<span>' . esc_html__( 'Previous article', 'kumo-hybrid' ) . '</span>%title',
				'next_text' => '<span>' . esc_html__( 'Next article', 'kumo-hybrid' ) . '</span>%title',
			)
		);
		?>
		<?php
		if ( comments_open() || get_comments_number() ) {
			comments_template(); }
		?>
	</div><?php get_sidebar(); ?></div>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
