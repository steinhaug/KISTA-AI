<?php
/*
define('WEBROOT', dirname(dirname(__FILE__)) . '/htdocs');
require './vendor/autoload.php';

define('SKIP_VENDOR_AUTOLOAD', true);
require WEBROOT . '/webhook.php';
*/
require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';
logfile('Webhook init.');
ob_start();
    echo "<h3>\$_SERVER</h3>";
    echo "<pre>";
    safePrintArray($_SERVER);
    echo "</pre>";
$page = ob_get_contents();
ob_end_clean();

$rawData = file_get_contents("php://input");

file_put_contents('webhook.log', $page . "\n" . $rawData . "\n\n* * * * *\n\n", FILE_APPEND);

if( !strlen($rawData) ){
    debug_log_error('WebHook triggered with empty body, $rawData');
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(['status'=>'knock knock']));
}

try {
    $jsonData = json_decode((string) $rawData, true);
    if (JSON_ERROR_NONE !== json_last_error()) {
        throw new RuntimeException('Webhook response body json decode error: ' . json_last_error());
    }
    $replicate_id = $d['id'];
} catch(RuntimeException $e) {
    debug_log_error($e->getMessage());
    $replicate_id = '';
} catch(Exception $e) {
    echo ("Error something went wrong!");
    header('Content-Type: application/json; charset=utf-8');
    die(json_encode(['status'=>'fail','error'=>$e->getMessage()]));
}

$sql = new sqlbuddy;
$sql->que('processed', 0, 'int');
$sql->que('replicate_id', $replicate_id, 'string:26');
$sql->que('created', 'NOW()', 'raw');
$sql->que('json', $json, 'string');
$mysqli->query( $sql->build('insert', 'kistaai_replicate__hooks') );
$whid = $mysqli->insert_id;

logfile('- Webhook saved id:' . $whid);
$dat = [
    'status' => 'success',
    'whid' => $whid
];

if (strlen($replicate_id)==26) {
    if (($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`replicate_id`=?", 's', [$replicate_id], true)) !== null) {
        $status = updateStatus__replicate($item['reid'], ['status'=>'inference-complete']);
        $dat['update'] = $status;
        logfile('- Webhook updated upload:' . json_encode($status));
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($dat);
