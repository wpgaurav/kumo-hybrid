<?php
/**
 * Main query collection.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="kh-content-layout">
	<div class="kh-collection-main">
		<?php if ( have_posts() ) : ?>
			<div class="kh-post-grid">
			<?php
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/card' ); }
			?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => __( 'Previous', 'kumo-hybrid' ),
					'next_text' => __( 'Next', 'kumo-hybrid' ),
				)
			);
			?>
			<?php
		else :
			get_template_part( 'template-parts/none' );
endif;
		?>
	</div>
	<?php get_sidebar(); ?>
</div>
