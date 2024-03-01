<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->files()->upload([
    'purpose' => 'fine-tune',
    'file' => fopen('cache/03.jsonl', 'r'),
]);

$response->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
$response->object; // 'file'
$response->bytes; // 140
$response->createdAt; // 1613779657
$response->filename; // 'mydata.jsonl'
$response->purpose; // 'fine-tune'
$response->status; // 'succeeded'
//$response->status_details; // null

$response->toArray(); // ['id' => 'file-XjGxS3KTG0uNmNOK362iJua3', ...]

krumo($response);
$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );

/*
{"id":"file-qFrNA8CUu7Npmqghx9O4l2cD","object":"file","bytes":1194,"created_at":1709272102,"filename":"03.jsonl","purpose":"fine-tune","status":"processed","status_details":null}
*/