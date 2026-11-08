<?php
/**
 * Post card.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'kh-post-card' ); ?>>
	<?php
	if ( has_post_thumbnail() && ! post_password_required() ) :
		?>
		<a class="kh-card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		the_post_thumbnail(
			'large',
			array(
				'alt'     => '',
				'loading' => 'lazy',
			)
		);
		?>
		</a><?php endif; ?>
	<div class="kh-card-body">
		<?php
		$categories = get_the_category(); if ( $categories ) :
			?>
			<a class="kh-badge" href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a><?php endif; ?>
		<h2 class="kh-card-title"><a href="<?php the_permalink(); ?>"><?php echo esc_html( get_the_title() ? get_the_title() : __( 'Untitled', 'kumo-hybrid' ) ); ?></a></h2>
		<div class="kh-card-excerpt"><?php the_excerpt(); ?></div>
		<?php kumo_hybrid_post_meta(); ?>
	</div>
</article>
