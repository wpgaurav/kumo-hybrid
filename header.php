<?php
/**
 * Site header.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'kumo-hybrid' ); ?></a>
<div class="kh-site">
<header class="kh-site-header">
	<div class="kh-shell kh-header-inner">
		<div class="kh-identity">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo(); } else {
				?>
				<a class="kh-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="kh-mark" aria-hidden="true"></span><?php bloginfo( 'name' ); ?></a><?php } ?>
			<?php
			if ( get_bloginfo( 'description' ) ) :
				?>
				<p class="kh-tagline"><?php bloginfo( 'description' ); ?></p><?php endif; ?>
		</div>
		<button class="kh-menu-toggle kh-control" aria-controls="site-navigation" aria-expanded="false" hidden><?php echo kumo_hybrid_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php esc_html_e( 'Menu', 'kumo-hybrid' ); ?></span></button>
		<nav id="site-navigation" class="kh-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'kumo-hybrid' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'kh-menu',
					'fallback_cb'    => 'kumo_hybrid_menu_fallback',
					'depth'          => 3,
				)
			);
			?>
		</nav>
		<div class="kh-header-actions">
			<?php
			if ( kumo_hybrid_option( 'header_action_label' ) && kumo_hybrid_option( 'header_action_url' ) ) :
				?>
				<a class="kh-button kh-header-action" href="<?php echo esc_url( kumo_hybrid_option( 'header_action_url' ) ); ?>"><?php echo esc_html( kumo_hybrid_option( 'header_action_label' ) ); ?></a><?php endif; ?>
			<?php
			if ( kumo_hybrid_option( 'show_mode_toggle' ) ) :
				?>
				<button class="kh-mode-toggle kh-control" aria-label="<?php esc_attr_e( 'Dark mode', 'kumo-hybrid' ); ?>" aria-pressed="false" hidden><?php echo kumo_hybrid_icon( 'mode' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button><?php endif; ?>
		</div>
	</div>
</header>
