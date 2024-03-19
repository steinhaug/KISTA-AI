<?php
session_start();
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');
require_once 'func.inc.php';
require_once 'func.login.php';






#$replicate_id = 'x7gtbj3b4ogwwc5jgfb54l6x6m'; // $_GET['reid];
$reid = _GET('reid',0,'int');
if (($data = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`reid`=? AND `user_id`=?", 'si', [$reid, $_SESSION['USER_ID']], true)) !== null) {
    echo '<h1>found</h1>';
    $json = json_decode($data['log'],1);
    echo '<pre>';
    var_dump( $json['logs'] );
    echo '</pre>';
}

echo '<a href="index.php">index.php</a>';
