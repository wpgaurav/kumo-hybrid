<?php
/** Run with WP-CLI on a test install: wp eval-file tests/icon-runtime.php. */
if(!defined('ABSPATH')||!defined('WP_CLI'))exit;
$assert=static function($ok,$label){if(!$ok)throw new RuntimeException($label);echo 'PASS: '.$label.PHP_EOL;};
$assert(''===kumo_hybrid_icon('../functions.php')&&''===kumo_hybrid_icon([]),'Only named bundled icons can be rendered');
foreach(['menu','mode','sun','arrow','chevron','search','check','rss','comment','reply','close'] as $role){$svg=kumo_hybrid_icon($role);$assert(str_contains($svg,'icon-tabler')&&str_contains($svg,'aria-hidden="true"')&&simplexml_load_string($svg)!==false,'Valid decorative Tabler asset: '.$role);}
foreach(['button-outside','button-inside','button-only'] as $position){$html=render_block(['blockName'=>'core/search','attrs'=>['label'=>'Find content','showLabel'=>true,'buttonText'=>'Search','buttonUseIcon'=>true,'buttonPosition'=>$position],'innerBlocks'=>[],'innerHTML'=>'','innerContent'=>[]]);$assert(str_contains($html,'icon-tabler-search')&&str_contains($html,'class="search-icon')&&str_contains($html,'name="s"'),'Native Search semantics retained: '.$position);if($position==='button-only')$assert(str_contains($html,'data-wp-on--click="actions.openSearchInput"'),'Button-only search keeps its native Interactivity API binding');}
