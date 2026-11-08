<?php
/** Native fixtures for a loopback-only Kumo Hybrid visual audit. */
if(!defined('WP_CLI')||!in_array(wp_parse_url(home_url(),PHP_URL_HOST),['127.0.0.1','localhost'],true)||get_stylesheet()!=='kumo-hybrid'){WP_CLI::error('Audit fixture refuses non-local targets');}
require_once ABSPATH.'wp-admin/includes/file.php';require_once ABSPATH.'wp-admin/includes/media.php';require_once ABSPATH.'wp-admin/includes/image.php';
global $kumo_audit;$kumo_audit=get_option('_kumo_visual_audit_manifest',[]);
$keys=['permalink_structure','sidebars_widgets','widget_block','widget_search','widget_recent-posts','widget_recent-comments','widget_archives','widget_categories','widget_calendar','widget_tag_cloud','widget_nav_menu','widget_pages','widget_text','widget_custom_html','widget_rss','widget_meta','widget_media_image','widget_media_gallery','widget_media_audio','widget_media_video','thread_comments','thread_comments_depth','page_comments','comments_per_page','default_comments_page','comment_order','comments_notify','moderation_notify','comment_moderation','comment_previously_approved','show_avatars','require_name_email'];
if(empty($kumo_audit['snapshot'])){foreach($keys as $key)$kumo_audit['snapshot']['options'][$key]=['exists'=>get_option($key,null)!==null,'value'=>get_option($key,null)];$kumo_audit['snapshot']['mods']=get_theme_mods();update_option('_kumo_visual_audit_manifest',$kumo_audit,false);}
function kva_block($name,$attrs=[],$inner=''){
 return serialize_block(['blockName'=>'core/'.$name,'attrs'=>$attrs,'innerBlocks'=>[],'innerHTML'=>$inner,'innerContent'=>$inner===''?[]:[$inner]]);
}
function kva_p($s){return '<!-- wp:paragraph --><p>'.$s.'</p><!-- /wp:paragraph -->';}
function kva_h($s){return '<!-- wp:heading --><h2 class="wp-block-heading">'.$s.'</h2><!-- /wp:heading -->';}
function kva_object($key,$type,$title,$body,$extra=[]){
 global $kumo_audit;
 $id=$kumo_audit['objects'][$key]??0;$slug='kumo-audit-'.$key;
 if(!$id){$old=get_page_by_path($slug,OBJECT,$type);if($old&&get_post_meta($old->ID,'_kumo_audit_key',true)!==$key)WP_CLI::error('Fixture collision');$id=$old?$old->ID:0;}
 $data=array_merge(['post_type'=>$type,'post_title'=>$title,'post_name'=>$slug,'post_content'=>$body,'post_status'=>'publish','post_author'=>1,'comment_status'=>'closed','ping_status'=>'closed'],$extra);
 if($id)$data['ID']=$id;$id=$id?wp_update_post(wp_slash($data),true):wp_insert_post(wp_slash($data),true);if(is_wp_error($id))WP_CLI::error($id->get_error_message());update_post_meta($id,'_kumo_audit_key',$key);$kumo_audit['objects'][$key]=$id;update_option('_kumo_visual_audit_manifest',$kumo_audit,false);return $id;
}
function kva_widget($base,$number,$config){$option='widget_'.$base;$all=get_option($option,[]);$all[$number]=$config;$all['_multiwidget']=1;update_option($option,$all);return $base.'-'.$number;}
$base=trailingslashit(home_url());
$blockIntro=kva_h('Typography and content').kva_p('These are native WordPress blocks. This local audit page deliberately includes narrow layouts, long text, and common form and widget combinations.').kva_p('A paragraph with <strong>strong text</strong>, <em>emphasis</em>, an <a href="#search-variants">ordinary link</a>, and <code>inline_code_example</code> should remain readable in both color modes.');
$gallery=$blockIntro;
foreach([3,4,5,6] as $level)$gallery.='<!-- wp:heading {"level":'.$level.'} --><h'.$level.' class="wp-block-heading">Heading level '.$level.'</h'.$level.'><!-- /wp:heading -->'.kva_p('Supporting text gives this heading a realistic context rather than leaving it isolated.');
$gallery.='<!-- wp:group {"anchor":"search-variants","layout":{"type":"default"}} --><div id="search-variants" class="wp-block-group">'.kva_h('Native search variants');
foreach(['outside'=>['buttonPosition'=>'button-outside','buttonText'=>'Search'],'inside'=>['buttonPosition'=>'button-inside','buttonText'=>'Search'],'icon'=>['buttonPosition'=>'button-outside','buttonUseIcon'=>true],'no-button'=>['buttonPosition'=>'no-button'],'button-only'=>['buttonPosition'=>'button-only','buttonUseIcon'=>true],'long-label'=>['buttonPosition'=>'button-outside','buttonText'=>'Search this collection']] as $name=>$config){$gallery.=kva_h(ucfirst(str_replace('-',' ',$name))).kva_block('search',array_merge(['label'=>'Search this sample','showLabel'=>true],$config));}
$gallery.='</div><!-- /wp:group -->';
$gallery.=kva_h('Buttons and disclosure').'<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Primary action</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-quiet"} --><div class="wp-block-button is-style-quiet"><a class="wp-block-button__link wp-element-button" href="#">Quiet action</a></div><!-- /wp:button --></div><!-- /wp:buttons -->';
$gallery.='<!-- wp:details {"className":"is-style-panel"} --><details class="wp-block-details is-style-panel"><summary>Open a native disclosure</summary>'.kva_p('This should open with the mouse, touch, or keyboard while keeping a clear focus state.').'</details><!-- /wp:details -->';
$gallery.=kva_h('Tables, quotes, code, and long content').'<!-- wp:table --><figure class="wp-block-table"><table class="has-fixed-layout"><thead><tr><th>Control</th><th>Expected behavior</th></tr></thead><tbody><tr><td>Search</td><td>A readable label and a useful text field</td></tr><tr><td>Comments</td><td>Clear hierarchy, labels, and reply states</td></tr></tbody></table></figure><!-- /wp:table -->';
$gallery.='<!-- wp:quote --><blockquote class="wp-block-quote">'.kva_p('The same design should work for ordinary WordPress content, not only for the homepage patterns.').'<cite>Audit fixture</cite></blockquote><!-- /wp:quote -->';
$gallery.='<!-- wp:code --><pre class="wp-block-code"><code>example_of_a_deliberately_long_unbroken_code_token_to_check_overflow_inside_a_reading_column_12345678901234567890</code></pre><!-- /wp:code -->';
$gallery.=kva_p('A long link: <a href="https://example.test/reference">https://example.test/a-deliberately-long-unbroken-reference-abcdefghijklmnopqrstuvwxyz0123456789</a>');
$gallery.=kva_h('Tabler icon roles').'<!-- wp:shortcode -->[kumo_audit_icons]<!-- /wp:shortcode -->';
$lab=kva_object('blocks','page','Native blocks and form samples',$gallery);
$commentsBody=kva_p('This post exercises the real WordPress comment list, threaded replies, pagination, and comment form. All people and messages are synthetic test fixtures.').kva_p('Use Reply and Cancel reply, test the required fields, and inspect the form as a guest and as a logged-in user.');
$commentPost=kva_object('comments','post','WordPress comments: native fixtures',$commentsBody,['comment_status'=>'open']);
$closed=kva_object('comments-closed','post','WordPress comments: closed',$commentsBody,['comment_status'=>'closed']);
$password=kva_object('password','post','WordPress comments: protected',$commentsBody,['comment_status'=>'open','post_password'=>'audit-fixture']);
foreach(['thread_comments'=>1,'thread_comments_depth'=>5,'page_comments'=>1,'comments_per_page'=>5,'default_comments_page'=>'oldest','comment_order'=>'asc','comments_notify'=>0,'moderation_notify'=>0,'comment_moderation'=>0,'comment_previously_approved'=>0,'show_avatars'=>1,'require_name_email'=>1] as $key=>$value)update_option($key,$value);
if(empty($kumo_audit['comments'])){
 $commentIds=[];$parent=0;
 for($i=0;$i<5;$i++){$id=wp_insert_comment(['comment_post_ID'=>$commentPost,'comment_author'=>'Audit reader '.($i+1),'comment_author_email'=>'reader'.($i+1).'@example.test','comment_content'=>$i===4?'<p>A deliberately long reference: <a href="https://example.test/reference">https://example.test/abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz0123456789</a></p>':'<p>A native WordPress comment with a short paragraph and <strong>some emphasis</strong>.</p>','comment_parent'=>$parent,'comment_approved'=>1]);$commentIds[]=$id;$parent=$id;}
 for($i=0;$i<8;$i++)$commentIds[]=wp_insert_comment(['comment_post_ID'=>$commentPost,'comment_author'=>'Sample visitor '.($i+1),'comment_author_email'=>'visitor'.($i+1).'@example.test','comment_content'=>'<p>A separate top-level comment to exercise pagination and repeated spacing.</p>','comment_approved'=>1]);
 wp_insert_comment(['comment_post_ID'=>$closed,'comment_author'=>'Audit reader','comment_author_email'=>'reader@example.test','comment_content'=>'<p>This discussion is now closed.</p>','comment_approved'=>1]);
 $kumo_audit['comments']=$commentIds;update_option('_kumo_visual_audit_manifest',$kumo_audit,false);
}
$category=term_exists('Audit category with a longer descriptive label','category');if(!$category)$category=wp_insert_term('Audit category with a longer descriptive label','category');$cat=(int)($category['term_id']??$category);
wp_set_post_terms($commentPost,[$cat],'category');wp_set_post_terms($closed,[$cat],'category');wp_set_post_terms($commentPost,['Audit','WordPress','Longer tag label for wrapping'],'post_tag');wp_set_post_terms($closed,['Audit','WordPress'],'post_tag');
$menu=$kumo_audit['menu']??0;if(!$menu){$menu=wp_create_nav_menu('Audit widget menu');foreach(['Native block samples'=>$lab,'Comments with replies'=>$commentPost,'A longer navigation item for wrapping'=>$closed] as $label=>$post)wp_update_nav_menu_item($menu,0,['menu-item-title'=>$label,'menu-item-object-id'=>$post,'menu-item-object'=>get_post_type($post),'menu-item-type'=>'post_type','menu-item-status'=>'publish']);$kumo_audit['menu']=$menu;}
$widgets=[];
$widgets[]=kva_widget('block',70,['content'=>'<!-- wp:heading --><h2 class="wp-block-heading">Studio notes</h2><!-- /wp:heading -->'.kva_p('Sample writing on useful, considered digital work.').kva_block('search',['label'=>'Search the journal','showLabel'=>true,'buttonText'=>'Search'])]);
$widgets[]=kva_widget('search',70,['title'=>'Classic search']);
$widgets[]=kva_widget('recent-posts',70,['title'=>'Recent posts','number'=>4,'show_date'=>true]);
$widgets[]=kva_widget('recent-comments',70,['title'=>'Recent comments','number'=>4]);
$widgets[]=kva_widget('archives',70,['title'=>'Archives list','count'=>1,'dropdown'=>0]);
$widgets[]=kva_widget('archives',71,['title'=>'Archives dropdown','count'=>1,'dropdown'=>1]);
$widgets[]=kva_widget('categories',70,['title'=>'Categories list','count'=>1,'hierarchical'=>1,'dropdown'=>0]);
$widgets[]=kva_widget('categories',71,['title'=>'Categories dropdown','count'=>1,'hierarchical'=>1,'dropdown'=>1]);
$widgets[]=kva_widget('calendar',70,['title'=>'Classic calendar']);
$widgets[]=kva_widget('tag_cloud',70,['title'=>'Classic tag cloud','taxonomy'=>'post_tag','count'=>1]);
$widgets[]=kva_widget('nav_menu',70,['title'=>'Navigation menu','nav_menu'=>$menu]);
$widgets[]=kva_widget('pages',70,['title'=>'Pages','sortby'=>'post_title','exclude'=>'']);
$widgets[]=kva_widget('text',70,['title'=>'Text widget','text'=>'<p>A native text widget with <strong>emphasis</strong>, <a href="#">a link</a>, and a paragraph that wraps naturally.</p>','filter'=>true,'visual'=>true]);
$widgets[]=kva_widget('custom_html',70,['title'=>'Custom HTML','content'=>'<p>Form controls in a native Custom HTML widget.</p><form><label for="audit-email">Email</label><input id="audit-email" name="email" type="email" placeholder="reader@example.test"><label for="audit-select">Choose an option</label><select id="audit-select" name="choice"><option>A longer option label for a narrow widget</option><option>Short option</option></select><p><label><input type="checkbox" name="consent"> A checkbox with a longer label</label></p><button type="button">Secondary action</button></form>']);
$widgets[]=kva_widget('rss',70,['title'=>'RSS fixture','url'=>'https://fixtures.kumo.test/feed/','items'=>2,'show_summary'=>1,'show_author'=>0,'show_date'=>1]);
$widgets[]=kva_widget('meta',70,['title'=>'Meta links']);
$widgets[]=kva_widget('block',71,['content'=>kva_h('Block calendar').kva_block('calendar').kva_h('Block archives').kva_block('archives',['displayAsDropdown'=>true,'showPostCounts'=>true]).kva_h('Block categories').kva_block('categories',['displayAsDropdown'=>true,'showPostCounts'=>true])]);
$widgets[]=kva_widget('block',72,['content'=>kva_h('Block latest posts').kva_block('latest-posts',['postsToShow'=>3,'displayPostDate'=>true]).kva_h('Block latest comments').kva_block('latest-comments',['commentsToShow'=>3,'displayAvatar'=>true,'displayDate'=>true,'displayExcerpt'=>true]).kva_h('Block tag cloud').kva_block('tag-cloud')]);
wp_set_sidebars_widgets(['sidebar'=>$widgets,'footer'=>array_slice($widgets,0,3),'wp_inactive_widgets'=>[]]);set_theme_mod('kumo_hybrid_sidebar',true);
$kumo_audit['sidebar_widgets']=$widgets;update_option('_kumo_visual_audit_manifest',$kumo_audit,false);
update_option('permalink_structure','/%postname%/');flush_rewrite_rules(false);
echo wp_json_encode(['objects'=>$kumo_audit['objects'],'comments'=>$kumo_audit['comments'],'widgets'=>$widgets,'menu'=>$menu]);
