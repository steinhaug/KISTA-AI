<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->files()->list();

$response->object; // 'list'

foreach ($response->data as $result) {
    $result->id; // 'file-qFrNA8CUu7Npmqghx9O4l2cD'
    $result->object; // 'file'
    // ...

    krumo($result);
}


$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );
