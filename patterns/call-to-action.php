<?php
/**
 * Title: Conversation call to action
 * Slug: kumo-hybrid/call-to-action
 * Categories: kumo-hybrid
 * Description: Conversation call to action built with native WordPress blocks.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"layout":{"type":"default"},"className":"kh-pattern-section kh-contact-panel","align":"wide","anchor":"approach"} -->
<div id="approach" class="wp-block-group alignwide kh-pattern-section kh-contact-panel">
<!-- wp:columns {"className":"kh-split"} -->
<div class="wp-block-columns kh-split">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html__( 'Start with a conversation', 'kumo-hybrid' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'Tell us what you\'re building, who it\'s for, and what needs to work better.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:buttons {"className":"kh-contact-action"} -->
<div class="wp-block-buttons kh-contact-action">
<!-- wp:button {"className":"is-style-quiet"} -->
<div class="wp-block-button is-style-quiet"><a class="wp-block-button__link wp-element-button"><?php echo esc_html__( 'Get in touch', 'kumo-hybrid' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
