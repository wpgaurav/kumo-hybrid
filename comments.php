<?php
/**
 * WordPress comments and reply form.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
if ( post_password_required() ) {
	return; }
?>
<section id="comments" class="kh-comments">
	<?php if ( have_comments() ) : ?>
	<h2><?php /* translators: %s: comment count. */ printf( esc_html( _n( '%s comment', '%s comments', get_comments_number(), 'kumo-hybrid' ) ), esc_html( number_format_i18n( get_comments_number() ) ) ); ?></h2>
	<ol class="comment-list">
		<?php
		wp_list_comments(
			array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 40,
			)
		);
		?>
								</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>
	<?php
	if ( ! comments_open() && get_comments_number() ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'kumo-hybrid' ); ?></p><?php endif; ?>
	<?php comment_form(); ?>
</section>
