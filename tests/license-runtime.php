<?php
/** Exercise the updater without network requests or real license credentials. */
if(!defined('ABSPATH')||!defined('WP_CLI')){exit;}
require_once dirname(__DIR__).'/distribution/class-kumo-hybrid-license.php';
$old=get_option(Kumo_Hybrid_License::OPTION,null);$calls=[];$scenario='activate';
// Keep the mocked release newer than the installed version across future releases.
$parts=explode('.',wp_get_theme('kumo-hybrid')->get('Version'));$parts[2]=(int)$parts[2]+1;$future=implode('.',$parts);
$mock=static function($pre,$args,$url)use(&$calls,&$scenario,&$future){
 if(!str_starts_with($url,'https://gauravtiwari.org/?fluent-cart='))return $pre;
 $calls[]=wp_parse_url($url,PHP_URL_QUERY);$body=[];
 if($scenario==='offline')return new WP_Error('offline','Fixture outage');
 if($scenario==='activate')$body=['success'=>true,'status'=>'valid','activation_hash'=>'fixture-activation','expiration_date'=>'lifetime'];
 if($scenario==='deactivate')$body=['success'=>true];
 if($scenario==='update')$body=['license_status'=>'valid','new_version'=>$future,'package'=>'https://packages.example.test/kumo-hybrid.zip','requires'=>'6.7','requires_php'=>'8.3'];
 if($scenario==='unsafe')$body=['license_status'=>'valid','new_version'=>$future,'package'=>'http://packages.example.test/kumo-hybrid.zip'];
 return ['response'=>['code'=>200],'body'=>wp_json_encode($body),'headers'=>[]];
};
add_filter('pre_http_request',$mock,10,3);$checks=[];$assert=static function($ok,$label)use(&$checks){if(!$ok)throw new RuntimeException($label);$checks[]=$label;};
try{
 delete_option(Kumo_Hybrid_License::OPTION);delete_transient(Kumo_Hybrid_License::UPDATE_CACHE);
 $assert(is_wp_error(Kumo_Hybrid_License::activate('')),'An empty key is rejected before a network request');
 $t=(object)['checked'=>['kumo-hybrid'=>wp_get_theme('kumo-hybrid')->get('Version')],'response'=>[]];
 $assert(empty(Kumo_Hybrid_License::updates(clone $t)->response),'No key means no update offer, with no theme feature gating');
 $assert(true===Kumo_Hybrid_License::activate('fixture-key-not-a-real-license'),'A valid activation saves its state');
 $scenario='offline';$assert(is_wp_error(Kumo_Hybrid_License::check())&&Kumo_Hybrid_License::state()['status']==='valid','A connection failure preserves the saved activation');
 $scenario='update';delete_transient(Kumo_Hybrid_License::UPDATE_CACHE);$offer=Kumo_Hybrid_License::updates(clone $t);
 $assert(($offer->response['kumo-hybrid']['new_version']??'')===$future,'A newer licensed release becomes a WordPress theme update offer');
 $scenario='unsafe';delete_transient(Kumo_Hybrid_License::UPDATE_CACHE);$assert(empty(Kumo_Hybrid_License::updates(clone $t)->response),'Non-HTTPS package URLs are rejected');
 $scenario='deactivate';$assert(true===Kumo_Hybrid_License::deactivate()&&Kumo_Hybrid_License::state()['status']==='unregistered','Deactivation removes this site’s saved key');
 echo wp_json_encode(['passed'=>true,'checks'=>$checks],JSON_PRETTY_PRINT).PHP_EOL;
}finally{
 remove_filter('pre_http_request',$mock,10);delete_transient(Kumo_Hybrid_License::UPDATE_CACHE);
 if(null===$old)delete_option(Kumo_Hybrid_License::OPTION);else update_option(Kumo_Hybrid_License::OPTION,$old,false);
}
