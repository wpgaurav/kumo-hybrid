<?php
/** Run in a disposable WordPress install: wp eval-file tests/runtime.php. */
if ( ! defined( 'ABSPATH' ) || ! defined( 'WP_CLI' ) ) { exit; }
wp_set_current_user( 1 );
$kumo_test_results = array();
$kumo_test_check = static function ( $condition, $message ) use ( &$kumo_test_results ) {
 if ( ! $condition ) { throw new RuntimeException( $message ); }
 $kumo_test_results[] = $message;
};
$kumo_test_mods = get_theme_mods();
try {
 $kumo_test_check( ! wp_is_block_theme(), 'Hybrid theme retains classic Customizer architecture' );
 require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
 $manager = new WP_Customize_Manager();
 do_action( 'customize_register', $manager );
 $kumo_test_check( count( kumo_hybrid_settings() ) === 12, 'All 12 design settings are defined' );
 foreach ( kumo_hybrid_settings() as $key => $definition ) {
  $kumo_test_check( null !== $manager->get_control( 'kumo_hybrid_' . $key ), 'Customizer control registered: ' . $key );
 }
 $manager->set_post_value( 'kumo_hybrid_accent', '#15803d' );
 $manager->get_setting( 'kumo_hybrid_accent' )->save();
 $manager->set_post_value( 'kumo_hybrid_content_width', 810 );
 $manager->get_setting( 'kumo_hybrid_content_width' )->save();
 WP_Theme_JSON_Resolver::clean_cached_data();
 $settings = wp_get_global_settings();
 $palette = array_column( $settings['color']['palette']['theme'], 'color', 'slug' );
 $kumo_test_check( '#15803d' === $palette['brand'], 'Customizer accent saves into the shared editor/frontend palette' );
 $kumo_test_check( '810px' === $settings['layout']['contentSize'], 'Customizer reading width reaches global block settings' );
 $kumo_test_check( 560 === kumo_hybrid_sanitize_setting( -5, 'content_width' ), 'Reading width minimum is enforced' );
 $kumo_test_check( 1600 === kumo_hybrid_sanitize_setting( 99999, 'wide_width' ), 'Site width maximum is enforced' );
 $kumo_test_check( 'light' === kumo_hybrid_sanitize_setting( 'bad-value', 'color_mode' ), 'Unknown color modes fall back safely' );
 $kumo_test_check( '#2563eb' === kumo_hybrid_sanitize_setting( 'red;}</style><script>', 'accent' ), 'Invalid CSS color input is rejected' );
 $kumo_test_check( '' === kumo_hybrid_sanitize_setting( 'javascript:alert(1)', 'header_action_url' ), 'Executable header URLs are rejected' );
 $kumo_test_check( false === kumo_hybrid_sanitize_setting( 'false', 'sidebar' ), 'False checkbox strings do not enable features' );
 $kumo_test_check( 720 === kumo_hybrid_sanitize_setting( array(), 'content_width' ), 'Non-scalar saved values cannot reach CSS settings' );
 $kumo_test_check( '#000000' === kumo_hybrid_accent_ink( '#ffffff' ) && '#ffffff' === kumo_hybrid_accent_ink( '#000000' ), 'Accent text chooses contrasting black or white' );
 $count = 0;
 foreach ( WP_Block_Patterns_Registry::get_instance()->get_all_registered() as $pattern ) {
  if ( ! str_starts_with( $pattern['name'], 'kumo-hybrid/' ) ) { continue; }
  ++$count;
  $kumo_test_check( ! empty( parse_blocks( $pattern['content'] )[0]['blockName'] ), 'Pattern parses: ' . $pattern['name'] );
  $kumo_test_check( '' !== do_blocks( $pattern['content'] ), 'Pattern renders: ' . $pattern['name'] );
 }
 $kumo_test_check( 4 === $count, 'All four theme patterns are registered' );
 echo wp_json_encode( array( 'passed' => true, 'wordpress' => get_bloginfo( 'version' ), 'php' => PHP_VERSION, 'checks' => $kumo_test_results ), JSON_PRETTY_PRINT ) . PHP_EOL;
} finally {
 $stylesheet = get_option( 'stylesheet' );
 update_option( 'theme_mods_' . $stylesheet, $kumo_test_mods ?: array() );
 WP_Theme_JSON_Resolver::clean_cached_data();
}
