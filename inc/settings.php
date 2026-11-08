<?php
/**
 * Shared Customizer and block editor settings.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;

/**
 * Define the shared settings schema and defaults.
 *
 * @return array
 */
function kumo_hybrid_settings() {
	return array(
		'color_mode'          => array(
			'default' => 'light',
			'label'   => __( 'Default color mode', 'kumo-hybrid' ),
			'type'    => 'select',
			'choices' => array(
				'light'  => __( 'Light', 'kumo-hybrid' ),
				'dark'   => __( 'Dark', 'kumo-hybrid' ),
				'system' => __( 'Follow device', 'kumo-hybrid' ),
			),
		),
		'accent'              => array(
			'default' => '#2563eb',
			'label'   => __( 'Accent color', 'kumo-hybrid' ),
			'type'    => 'color',
		),
		'content_width'       => array(
			'default' => 720,
			'label'   => __( 'Reading width (px)', 'kumo-hybrid' ),
			'type'    => 'number',
			'min'     => 560,
			'max'     => 900,
		),
		'wide_width'          => array(
			'default' => 1200,
			'label'   => __( 'Site width (px)', 'kumo-hybrid' ),
			'type'    => 'number',
			'min'     => 960,
			'max'     => 1600,
		),
		'font'                => array(
			'default' => 'inter',
			'label'   => __( 'Reading font', 'kumo-hybrid' ),
			'type'    => 'select',
			'choices' => array(
				'inter' => __( 'Inter (local)', 'kumo-hybrid' ),
				'sans'  => __( 'System sans serif', 'kumo-hybrid' ),
				'serif' => __( 'System serif', 'kumo-hybrid' ),
			),
		),
		'archive_layout'      => array(
			'default' => 'grid',
			'label'   => __( 'Post collection layout', 'kumo-hybrid' ),
			'type'    => 'select',
			'choices' => array(
				'grid' => __( 'Cards', 'kumo-hybrid' ),
				'list' => __( 'List', 'kumo-hybrid' ),
			),
		),
		'sidebar'             => array(
			'default' => false,
			'label'   => __( 'Show sidebar on posts and archives', 'kumo-hybrid' ),
			'type'    => 'checkbox',
		),
		'show_meta'           => array(
			'default' => true,
			'label'   => __( 'Show post date and author', 'kumo-hybrid' ),
			'type'    => 'checkbox',
		),
		'show_mode_toggle'    => array(
			'default' => true,
			'label'   => __( 'Show visitor color mode switch', 'kumo-hybrid' ),
			'type'    => 'checkbox',
		),
		'header_action_label' => array(
			'default' => '',
			'label'   => __( 'Header button label', 'kumo-hybrid' ),
			'type'    => 'text',
		),
		'header_action_url'   => array(
			'default' => '',
			'label'   => __( 'Header button URL', 'kumo-hybrid' ),
			'type'    => 'url',
		),
		'footer_text'         => array(
			'default' => '',
			'label'   => __( 'Footer text', 'kumo-hybrid' ),
			'type'    => 'text',
		),
	);
}

/**
 * Validate saved values at the persistence and consumption boundaries.
 *
 * @param mixed  $value Proposed value.
 * @param string $key   Schema key.
 * @return mixed
 */
function kumo_hybrid_sanitize_setting( $value, $key ) {
	$settings = kumo_hybrid_settings();
	if ( ! isset( $settings[ $key ] ) ) {
		return null;
	}
	$setting = $settings[ $key ];
	if ( ! is_scalar( $value ) ) {
		return $setting['default'];
	}
	switch ( $setting['type'] ) {
		case 'select':
			return isset( $setting['choices'][ $value ] ) ? $value : $setting['default'];
		case 'color':
			$color = sanitize_hex_color( $value );
			return $color ? $color : $setting['default'];
		case 'number':
			return max( $setting['min'], min( $setting['max'], absint( $value ) ) );
		case 'checkbox':
			return in_array( $value, array( true, 1, '1' ), true );
		case 'url':
			return esc_url_raw( $value, array( 'http', 'https' ) );
		default:
			return sanitize_text_field( $value );
	}
}

/**
 * Read a validated theme mod or its default.
 *
 * @param string $key Schema key.
 * @return mixed
 */
function kumo_hybrid_option( $key ) {
	$settings = kumo_hybrid_settings();
	return isset( $settings[ $key ] ) ? kumo_hybrid_sanitize_setting( get_theme_mod( 'kumo_hybrid_' . $key, $settings[ $key ]['default'] ), $key ) : null;
}

/**
 * Build Customizer controls from the shared schema.
 *
 * @param WP_Customize_Manager $manager Customizer manager.
 */
function kumo_hybrid_customize_register( $manager ) {
	$manager->add_section(
		'kumo_hybrid_design',
		array(
			'title'       => __( 'Kumo design', 'kumo-hybrid' ),
			'priority'    => 30,
			'description' => __( 'Site defaults also apply to the block editor. Individual blocks can use their own styles. Visitor color choices override the default mode.', 'kumo-hybrid' ),
		)
	);
	foreach ( kumo_hybrid_settings() as $key => $setting ) {
		$id = 'kumo_hybrid_' . $key;
		$manager->add_setting(
			$id,
			array(
				'default'           => $setting['default'],
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'transport'         => 'refresh',
				'sanitize_callback' => static function ( $value ) use ( $key ) {
					return kumo_hybrid_sanitize_setting( $value, $key );
				},
			)
		);
		$args = array(
			'label'   => $setting['label'],
			'section' => 'kumo_hybrid_design',
			'type'    => $setting['type'],
		);
		if ( isset( $setting['choices'] ) ) {
			$args['choices'] = $setting['choices'];
		}
		if ( 'number' === $setting['type'] ) {
			$args['input_attrs'] = array(
				'min'  => $setting['min'],
				'max'  => $setting['max'],
				'step' => 1,
			);
		}
		if ( 'color' === $setting['type'] ) {
			$manager->add_control( new WP_Customize_Color_Control( $manager, $id, $args ) );
		} else {
			$manager->add_control( $id, $args );
		}
	}
}
add_action( 'customize_register', 'kumo_hybrid_customize_register' );

/**
 * Pick the greater WCAG contrast against the chosen accent.
 *
 * @param string $hex Validated hexadecimal accent color.
 * @return string
 */
function kumo_hybrid_accent_ink( $hex ) {
	$hex = ltrim( $hex, '#' );
	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	$channels  = array_map(
		static function ( $channel ) {
			$channel = hexdec( $channel ) / 255;
			return $channel <= 0.04045 ? $channel / 12.92 : pow( ( $channel + 0.055 ) / 1.055, 2.4 );
		},
		str_split( $hex, 2 )
	);
	$luminance = 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
	return ( $luminance + 0.05 ) / 0.05 > 1.05 / ( $luminance + 0.05 ) ? '#000000' : '#ffffff';
}

/**
 * Apply shared site defaults to the editor and frontend.
 *
 * @param WP_Theme_JSON_Data $data Theme JSON data.
 * @return WP_Theme_JSON_Data
 */
function kumo_hybrid_theme_json( $data ) {
	$accent = kumo_hybrid_option( 'accent' );
	$font   = array(
		'inter' => '"InterVariable", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
		'sans'  => '-apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif',
		'serif' => 'Georgia, Cambria, "Times New Roman", serif',
	)[ kumo_hybrid_option( 'font' ) ];
	$data->update_with(
		array(
			'version'  => 3,
			'settings' => array(
				'layout' => array(
					'contentSize' => kumo_hybrid_option( 'content_width' ) . 'px',
					'wideSize'    => kumo_hybrid_option( 'wide_width' ) . 'px',
				),
				'color'  => array(
					'palette' => array(
						array(
							'slug'  => 'canvas',
							'name'  => __( 'Canvas', 'kumo-hybrid' ),
							'color' => 'var(--kh-canvas)',
						),
						array(
							'slug'  => 'base',
							'name'  => __( 'Surface', 'kumo-hybrid' ),
							'color' => 'var(--kh-base)',
						),
						array(
							'slug'  => 'recessed',
							'name'  => __( 'Recessed', 'kumo-hybrid' ),
							'color' => 'var(--kh-recessed)',
						),
						array(
							'slug'  => 'foreground',
							'name'  => __( 'Text', 'kumo-hybrid' ),
							'color' => 'var(--kh-text)',
						),
						array(
							'slug'  => 'subtle',
							'name'  => __( 'Secondary text', 'kumo-hybrid' ),
							'color' => 'var(--kh-subtle)',
						),
						array(
							'slug'  => 'brand',
							'name'  => __( 'Accent', 'kumo-hybrid' ),
							'color' => $accent,
						),
						array(
							'slug'  => 'brand-ink',
							'name'  => __( 'Text on accent', 'kumo-hybrid' ),
							'color' => kumo_hybrid_accent_ink( $accent ),
						),
					),
				),
			),
			'styles'   => array( 'typography' => array( 'fontFamily' => $font ) ),
		)
	);
	return $data;
}
add_filter( 'wp_theme_json_data_theme', 'kumo_hybrid_theme_json' );

/** Editor surfaces follow the site default, independent of a visitor preference. */
function kumo_hybrid_editor_assets() {
	// Include tokens in editor controls as well as the content iframe.
	wp_enqueue_style( 'kumo-hybrid-tokens', get_theme_file_uri( '/assets/css/tokens.css' ), array(), KUMO_HYBRID_VERSION );
	$mode   = kumo_hybrid_option( 'color_mode' );
	$scheme = 'system' === $mode ? 'light dark' : $mode;
	wp_add_inline_style( 'kumo-hybrid-tokens', 'body.editor-styles-wrapper{color-scheme:' . $scheme . ';}' );
}
add_action( 'enqueue_block_assets', 'kumo_hybrid_editor_assets' );
