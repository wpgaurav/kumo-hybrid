<?php
/**
 * Tabler icon rendering from the bundled local subset.
 *
 * @package Kumo_Hybrid
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a decorative Tabler outline icon for a known semantic role.
 *
 * @param string $name Semantic icon name.
 * @return string
 */
function kumo_hybrid_icon( $name ) {
	$icons = array(
		'menu'    => 'menu-2',
		'mode'    => 'moon',
		'sun'     => 'sun',
		'arrow'   => 'arrow-right',
		'chevron' => 'chevron-down',
		'search'  => 'search',
		'check'   => 'check',
		'rss'     => 'rss',
		'comment' => 'message-circle',
		'reply'   => 'corner-down-right',
		'close'   => 'x',
	);
	if ( ! is_string( $name ) || ! isset( $icons[ $name ] ) ) {
		return '';
	}
	static $markup = array();
	$file          = $icons[ $name ];
	if ( ! isset( $markup[ $file ] ) ) {
		$path = get_theme_file_path( '/assets/icons/tabler/' . $file . '.svg' );
		if ( ! is_readable( $path ) ) {
			return '';
		}
		$svg = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Bundled local asset, never a URL.
		if ( ! preg_match( '/<svg\b[^>]*>(.*)<\/svg>/s', $svg, $match ) ) {
			return '';
		}
		// Assets are a fixed, trusted, MIT-licensed subset of Tabler 3.47.0.
		$markup[ $file ] = '<svg class="kh-icon icon icon-tabler icon-tabler-outline icon-tabler-' . esc_attr( $file ) . '" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $match[1] . '</svg>';
	}
	return $markup[ $file ];
}

/**
 * Use the shared search icon while preserving the native block's SVG attributes.
 *
 * @param string $content Rendered core Search block.
 * @param array  $block   Parsed block attributes.
 * @return string
 */
function kumo_hybrid_search_icon( $content, $block ) {
	if ( empty( $block['attrs']['buttonUseIcon'] ) ) {
		return $content;
	}
	$icon = kumo_hybrid_icon( 'search' );
	if ( ! preg_match( '/<svg\b[^>]*>(.*)<\/svg>/s', $icon, $paths ) ) {
		return $content;
	}
	return preg_replace_callback(
		'/<svg\b[^>]*>.*?<\/svg>/s',
		static function ( $svg_match ) use ( $paths ) {
			$tags = new WP_HTML_Tag_Processor( $svg_match[0] );
			if ( ! $tags->next_tag( 'svg' ) ) {
				return $svg_match[0];
			}
			$tags->add_class( 'kh-icon' );
			$tags->add_class( 'icon-tabler-search' );
			foreach ( array(
				'fill'            => 'none',
				'stroke'          => 'currentColor',
				'stroke-width'    => '2',
				'stroke-linecap'  => 'round',
				'stroke-linejoin' => 'round',
				'aria-hidden'     => 'true',
				'focusable'       => 'false',
			) as $key => $value ) {
				$tags->set_attribute( $key, $value );
			}
			$svg = $tags->get_updated_html();
			return substr( $svg, 0, strpos( $svg, '>' ) + 1 ) . $paths[1] . '</svg>';
		},
		$content,
		1
	);
}
add_filter( 'render_block_core/search', 'kumo_hybrid_search_icon', 10, 2 );
