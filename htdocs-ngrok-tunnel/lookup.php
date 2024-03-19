<?php
require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';



$replicate_id = 'gvb5sbdby7rdwmecf6giwfhjjy'; // basename($jsonData['urls']['get'])
if (strlen($replicate_id)==26) {
    if( ($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`replicate_id`=?", 's', [$replicate_id], true)) !== null ){
        echo "<h1>found</h1>\n";

        updateStatus__replicate($item['reid'], ['status'=>'failed', 'error'=>'Yolo!']);

        safePrintArray($item);

    }
}

$a = true;
echo json_encode($a);