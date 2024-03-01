<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->files()->retrieve('file-qFrNA8CUu7Npmqghx9O4l2cD');

$response->id; // 'file-qFrNA8CUu7Npmqghx9O4l2cD'
$response->object; // 'file'
$response->bytes; // 140
$response->createdAt; // 1613779657
$response->filename; // 'mydata.jsonl'
$response->purpose; // 'fine-tune'
$response->status; // 'succeeded'
//$response->status_details; // null

krumo($response);
$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );
