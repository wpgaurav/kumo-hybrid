<?php
/**
 * Title: Business introduction with layered panel
 * Slug: kumo-hybrid/introduction
 * Categories: kumo-hybrid
 * Description: Business introduction with layered panel built with native WordPress blocks.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"layout":{"type":"default"},"className":"kh-hero","align":"wide"} -->
<div class="wp-block-group alignwide kh-hero">
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php echo esc_html__( 'Good work starts with a clear idea.', 'kumo-hybrid' ); ?></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"kh-hero-copy"} -->
<p class="kh-hero-copy"><?php echo esc_html__( 'We help ambitious people turn complicated challenges into useful, considered digital experiences.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#approach"><?php echo esc_html__( 'Explore our approach', 'kumo-hybrid' ); ?></a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-quiet"} -->
<div class="wp-block-button is-style-quiet"><a class="wp-block-button__link wp-element-button" href="#services"><?php echo esc_html__( 'What we do', 'kumo-hybrid' ); ?></a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:group {"layout":{"type":"constrained"},"className":"is-style-layer kh-product-panel"} -->
<div class="wp-block-group is-style-layer kh-product-panel">
<!-- wp:paragraph {"className":"kh-panel-bar"} -->
<p class="kh-panel-bar"><?php echo esc_html__( 'From first sketch to launch', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html__( 'A clear path forward', 'kumo-hybrid' ); ?></h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><?php echo esc_html__( 'One focused process. Room for the details.', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
<!-- wp:list {"className":"is-style-checklist"} -->
<ul class="wp-block-list is-style-checklist">
<!-- wp:list-item -->
<li><?php echo esc_html__( 'Find the right direction', 'kumo-hybrid' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php echo esc_html__( 'Design around real people', 'kumo-hybrid' ); ?></li>
<!-- /wp:list-item -->
<!-- wp:list-item -->
<li><?php echo esc_html__( 'Build something that lasts', 'kumo-hybrid' ); ?></li>
<!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
<!-- wp:group {"layout":{"type":"constrained"},"className":"is-style-recessed"} -->
<div class="wp-block-group is-style-recessed">
<!-- wp:paragraph {"className":"kh-small"} -->
<p class="kh-small"><?php echo esc_html__( 'Thoughtful by design', 'kumo-hybrid' ); ?></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
