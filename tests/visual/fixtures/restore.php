<?php
/** Restore local widget and comment options after running the audit fixtures. */
if(!defined('WP_CLI')||!in_array(wp_parse_url(home_url(),PHP_URL_HOST),['127.0.0.1','localhost'],true))WP_CLI::error('Non-local fixture target');
$m=get_option('_kumo_visual_audit_manifest');if(empty($m['snapshot']))WP_CLI::error('No audit snapshot');
foreach($m['snapshot']['options'] as $key=>$old){if($old['exists'])update_option($key,$old['value']);else delete_option($key);}
update_option('theme_mods_'.get_option('stylesheet'),$m['snapshot']['mods']);WP_Theme_JSON_Resolver::clean_cached_data();flush_rewrite_rules(false);echo 'Restored pre-audit local options. Fixture posts/media are retained for replay.'.PHP_EOL;
