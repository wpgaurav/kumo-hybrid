<?php
$root=getenv('KUMO_AUDIT_WP_ROOT');if(!$root||!is_file($root.'/wp-load.php')){http_response_code(500);echo 'Set KUMO_AUDIT_WP_ROOT to the disposable WordPress directory.';return;}
$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);if(is_file($root.$path))return false;require $root.'/index.php';
