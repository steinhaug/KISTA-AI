<?php
session_start();
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');
require_once 'func.inc.php';
require_once 'func.login.php';

/*
    some_error_system();

    header('HTTP/1.0 200 OK');
    echo json_encode(['status'=>'idle','progress'=>0,'message'=>'Nothing to do.']);
    exit;
*/


/*
$json = file_get_contents('../htdocs-ngrok-tunnel/webhook.log');

$d = json_decode($json,1);
$replicate_id = '';
if(isset($d['id']))
    $replicate_id = $d['id'];
$sql = new sqlbuddy;
$sql->que('processed', 0, 'int');
$sql->que('replicate_id', $replicate_id, 'string');
$sql->que('created', 'NOW()', 'raw');
$sql->que('json', $json, 'string');
$mysqli->query( $sql->build('insert', 'kistaai_replicate__hooks') );
$hook_id = $mysqli->insert_id;

echo $hook_id;
*/

$item = ['replicate_id'=>'b7idjslbe4d6zltxahouw665mi'];

if (($data = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__hooks` `hooks` WHERE `hooks`.`replicate_id`=?", 's', [$item['replicate_id']], true)) !== null) {
    $hook = json_decode($data['json'],true);
    var_dump($hook);

}