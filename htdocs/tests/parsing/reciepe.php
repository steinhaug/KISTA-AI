<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';


$url = '/uploaded_files/00008-00002-1.png';
$thumb_url = '/uploaded_files/_thumbs/00008-00002-1.png';

//678
echo 'UserID: ' . $USER_ID . '<br>';
$item = $mysqli->query1("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE upload_id=" . 17);
#var_dump($parts);

#echo '<pre>' . json_encode( getArray__splitAtNeedleLine($item['reciepe'], 'Instructions:'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</pre>';
echo '<hr>';
echo "
<div style='display: flex;'>
    <div style='width:50%;'><pre>" . openai__generateReciepe($item['reciepe'], $item['reciepe_image']) . "</pre></div>
    <div style='width:50%;'><pre>" . $item['reciepe'] . "</pre></div>
</div>
";