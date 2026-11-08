<?php
/**
 * Plugin Name: Kumo Hybrid Demo Examples
 * Description: Native WordPress examples for the Kumo Hybrid showcase only.
 * Version: 0.3.1
 */
if(!defined('ABSPATH'))exit;
if(untrailingslashit(home_url())!=='https://demo.gatilab.com/kumo-hybrid')return;
add_shortcode('kumo_demo_widgets',static function(){
 $m=get_option('_kumo_demo_031',[]);$html='<div class="kumo-demo-widget-grid">';
 foreach($m['widgets']??[] as $id){$split=strrpos($id,'-');$base=substr($id,0,$split);$number=(int)substr($id,$split+1);$class='';foreach($GLOBALS['wp_widget_factory']->widgets as $candidate=>$widget){if($widget->id_base===$base){$class=$candidate;break;}}if(!$class)continue;$config=get_option('widget_'.$base)[$number]??[];ob_start();the_widget($class,$config,['widget_id'=>wp_unique_id('kumo-demo-'),'before_widget'=>'<section class="kh-widget widget_'.$base.'" data-kumo-widget="'.esc_attr($class).'">','after_widget'=>'</section>','before_title'=>'<h2 class="kh-widget-title">','after_title'=>'</h2>']);$html.='<div class="kh-sidebar">'.ob_get_clean().'</div>';}
 return $html.'</div>';
});
add_action('wp_enqueue_scripts',static function(){if(is_page('widgets'))wp_add_inline_style('kumo-hybrid-style','.kumo-demo-widget-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,280px),280px));gap:2rem;align-items:start}.kumo-demo-widget-grid>.kh-sidebar{margin-block-start:0}');},20);
add_filter('pre_comment_approved',static function($approved,$data){$ids=get_option('_kumo_demo_031',[])['comment_examples']??[];if(in_array((int)($data['comment_post_ID']??0),array_map('intval',$ids),true))return new WP_Error('kumo_demo_read_only','This is a sample discussion. Submissions are disabled on the public demo; no comment has been stored.');return $approved;},10,2);
