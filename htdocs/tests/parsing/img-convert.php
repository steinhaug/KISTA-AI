<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';


/*
$img = dirname(APPDATA_PATH) . '/tests/img/002.webp';
echo get_extension($img) . '<br>';
var_dump( pathinfo($img, PATHINFO_FILENAME) );
convertImage(dirname(APPDATA_PATH) . '/tests/img/002.webp',dirname(APPDATA_PATH) . '/tests/img/002.jpg');
*/

//executeDalle('Generate an artwork of two ethereal mice amidst a clash, their silhouettes filled with explosive psychedelic colors and intricate mandala designs, all set within a shifting, otherworldly dimension that vibrates with energy.', 'xxx.png');


//echo promptChatGPT3('How much is 100 times 2?');

debug_log_error('Mombo jokmbo');