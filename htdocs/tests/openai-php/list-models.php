<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->models()->list();

$response->object; // 'list'

foreach ($response->data as $result) {
    $result->id; // 'gpt-3.5-turbo-instruct'
    $result->object; // 'model'
    // ...
    echo $result->id . '<br>';
}

$response->toArray(); // ['object' => 'list', 'data' => [...]]