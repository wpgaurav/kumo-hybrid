<?php
/**
 * Theme bootstrap.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;

define( 'KUMO_HYBRID_VERSION', '0.3.1' );

require_once get_theme_file_path( '/inc/settings.php' );
require_once get_theme_file_path( '/inc/template-tags.php' );
require_once get_theme_file_path( '/inc/icons.php' );

/**
 * Register native theme features and shared editor styles.
 */
function kumo_hybrid_setup() {
	load_theme_textdomain( 'kumo-hybrid', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 64,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_editor_style( array( 'assets/css/tokens.css', 'assets/css/blocks.css', 'assets/css/editor.css' ) );
	register_nav_menus(
		array(
			'primary' => __( 'Primary menu', 'kumo-hybrid' ),
			'footer'  => __( 'Footer menu', 'kumo-hybrid' ),
		)
	);
}
add_action( 'after_setup_theme', 'kumo_hybrid_setup' );

/**
 * Load small, local assets with child theme overrides last.
 */
function kumo_hybrid_assets() {
	wp_enqueue_style( 'kumo-hybrid-tokens', get_theme_file_uri( '/assets/css/tokens.css' ), array(), KUMO_HYBRID_VERSION );
	wp_enqueue_style( 'kumo-hybrid-blocks', get_theme_file_uri( '/assets/css/blocks.css' ), array( 'kumo-hybrid-tokens' ), KUMO_HYBRID_VERSION );
	wp_enqueue_style( 'kumo-hybrid', get_theme_file_uri( '/assets/css/site.css' ), array( 'kumo-hybrid-blocks' ), KUMO_HYBRID_VERSION );
	// The child stylesheet is last so child themes can override the parent.
	wp_enqueue_style( 'kumo-hybrid-style', get_stylesheet_uri(), array( 'kumo-hybrid' ), wp_get_theme()->get( 'Version' ) );
	wp_enqueue_script( 'kumo-hybrid-mode', get_theme_file_uri( '/assets/js/mode.js' ), array(), KUMO_HYBRID_VERSION, false );
	wp_enqueue_script(
		'kumo-hybrid-navigation',
		get_theme_file_uri( '/assets/js/navigation.js' ),
		array(),
		KUMO_HYBRID_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_add_inline_script( 'kumo-hybrid-navigation', 'window.kumoHybridIcons = ' . wp_json_encode( array( 'chevron' => kumo_hybrid_icon( 'chevron' ) ) ) . ';', 'before' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kumo_hybrid_assets' );

/**
 * Register optional sidebar and footer widget areas.
 */
function kumo_hybrid_widgets() {
	foreach ( array(
		'sidebar' => __( 'Sidebar', 'kumo-hybrid' ),
		'footer'  => __( 'Footer', 'kumo-hybrid' ),
	) as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<section id="%1$s" class="kh-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="kh-widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'kumo_hybrid_widgets' );

/**
 * Register styles and the category for native core-block patterns.
 */
function kumo_hybrid_block_styles() {
	register_block_pattern_category( 'kumo-hybrid', array( 'label' => __( 'Kumo Hybrid', 'kumo-hybrid' ) ) );
	foreach ( array(
		'core/group'   => array(
			'layer'    => __( 'Layered panel', 'kumo-hybrid' ),
			'recessed' => __( 'Recessed panel', 'kumo-hybrid' ),
		),
		'core/button'  => array( 'quiet' => __( 'Quiet', 'kumo-hybrid' ) ),
		'core/details' => array( 'panel' => __( 'Disclosure panel', 'kumo-hybrid' ) ),
		'core/list'    => array( 'checklist' => __( 'Checklist', 'kumo-hybrid' ) ),
	) as $block => $styles ) {
		foreach ( $styles as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'kumo_hybrid_block_styles' );

/**
 * Expose the site color default before the first paint.
 *
 * @param string $attributes Existing HTML language attributes.
 * @return string
 */
function kumo_hybrid_language_attributes( $attributes ) {
	$preview = is_customize_preview() ? ' data-kumo-preview="true"' : '';
	return $attributes . ' data-site-mode="' . esc_attr( kumo_hybrid_option( 'color_mode' ) ) . '"' . $preview;
}
add_filter( 'language_attributes', 'kumo_hybrid_language_attributes' );

/**
 * Expose the selected layout to theme CSS.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function kumo_hybrid_body_classes( $classes ) {
	$classes[] = 'kh-archive-' . kumo_hybrid_option( 'archive_layout' );
	if ( kumo_hybrid_has_sidebar() ) {
		$classes[] = 'kh-has-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'kumo_hybrid_body_classes' );
