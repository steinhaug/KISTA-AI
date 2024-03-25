<?php
header("Content-type: application/json;charset=utf-8");

if(!defined('APPDATA_PATH')) define('APPDATA_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'inc_appdata');
if (!function_exists('getHost')) {
    function getHost() {
        $possibleHostSources = array('HTTP_X_FORWARDED_HOST', 'HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');
        $sourceTransformations = array(
            "HTTP_X_FORWARDED_HOST" => function ($value) {
                $elements = explode(',', $value);
                return trim(end($elements));
            }
        );
        $host = '';
        foreach ($possibleHostSources as $source) {
            if (!empty($host)) {
                break;
            }
            if (empty($_SERVER[$source])) {
                continue;
            }
            $host = $_SERVER[$source];
            if (array_key_exists($source, $sourceTransformations)) {
                $host = $sourceTransformations[$source]($host);
            }
        }

        // Remove port number from host
        $host = preg_replace('/:\d+$/', '', $host);

        return trim($host);
    }
}
$vars_file = 'cred.' . getHost() . '_vars.php';
if( file_exists(dirname(dirname(APPDATA_PATH)) . DIRECTORY_SEPARATOR . $vars_file) )
    require dirname(dirname(APPDATA_PATH)) . DIRECTORY_SEPARATOR . $vars_file;
    else
    require dirname(APPDATA_PATH) . DIRECTORY_SEPARATOR . '_vars.php';
?>{
"version": "<?=$PWA_APP_VER?>",
"lang" : "en",
"name" : "<?=$PWA_APP_NAME?>",
"scope" : "<?=$PWA_DOMAIN?>/",
"display" : "fullscreen",
"start_url" : "<?=$PWA_DOMAIN?>/index.php",
"short_name" : "<?=$PWA_APP_NAME_SHORT?>",
"description" : "",
"orientation" : "portrait",
"background_color": "#000000",
"theme_color": "#000000",
"generated" : "true",
  "icons": [
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-72x72.png",
	  "sizes": "72x72",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-96x96.png",
	  "sizes": "96x96",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-128x128.png",
	  "sizes": "128x128",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-144x144.png",
	  "sizes": "144x144",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-152x152.png",
	  "sizes": "152x152",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-192x192.png",
	  "sizes": "192x192",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "<?=$PWA_LANG['ico_folder']?>/icon-384x384.png",
	  "sizes": "384x384",
	  "type": "image/png",
	  "purpose": "any maskable"
	}
  ]
}