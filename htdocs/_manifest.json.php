<?php
header("Content-type: application/json;charset=utf-8");
require '_vars.php';
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
	  "src": "app/icons/icon-72x72.png",
	  "sizes": "72x72",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-96x96.png",
	  "sizes": "96x96",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-128x128.png",
	  "sizes": "128x128",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-144x144.png",
	  "sizes": "144x144",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-152x152.png",
	  "sizes": "152x152",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-192x192.png",
	  "sizes": "192x192",
	  "type": "image/png",
	  "purpose": "any maskable"
	},
	{
	  "src": "app/icons/icon-384x384.png",
	  "sizes": "384x384",
	  "type": "image/png",
	  "purpose": "any maskable"
	}
  ]
}