<?php
/**
 * Title: Frequently asked questions
 * Slug: kumo-hybrid/questions
 * Categories: kumo-hybrid
 * Description: Frequently asked questions built with native WordPress blocks.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"layout":{"type":"default"},"className":"kh-pattern-section kh-faq-section","align":"wide","anchor":"questions"} -->
<div id="questions" class="wp-block-group alignwide kh-pattern-section kh-faq-section">
<!-- wp:columns {"className":"kh-split"} -->
<div class="wp-block-columns kh-split">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"layout":{"type":"default"},"className":"kh-section-heading"} -->
<div class="wp-block-group kh-section-heading">
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html__( 'A few useful answers', 'kumo-hybrid' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'What to expect when we work together.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"layout":{"type":"default"},"className":"kh-faq-list"} -->
<div class="wp-block-group kh-faq-list">
<!-- wp:details {"className":"is-style-panel"} -->
<details class="wp-block-details is-style-panel"><summary><?php echo esc_html__( 'Where does a project start?', 'kumo-hybrid' ); ?></summary><!-- wp:paragraph -->
<p><?php echo esc_html__( 'With a conversation about your goals, the people you serve, and what is getting in the way.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"className":"is-style-panel"} -->
<details class="wp-block-details is-style-panel"><summary><?php echo esc_html__( 'Can we start with something small?', 'kumo-hybrid' ); ?></summary><!-- wp:paragraph -->
<p><?php echo esc_html__( 'Yes. A focused first step is often the best way to learn what the larger project needs.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
<!-- wp:details {"className":"is-style-panel"} -->
<details class="wp-block-details is-style-panel"><summary><?php echo esc_html__( 'What happens after launch?', 'kumo-hybrid' ); ?></summary><!-- wp:paragraph -->
<p><?php echo esc_html__( 'We\'ll agree on handover and support before the project begins, so you know what comes next.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</details>
<!-- /wp:details -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
