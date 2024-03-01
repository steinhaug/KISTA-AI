<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->files()->delete($file);

$response->id; // 'file-XjGxS3KTG0uNmNOK362iJua3'
$response->object; // 'file'
$response->deleted; // true

$response->toArray(); // ['id' => 'file-XjGxS3KTG0uNmNOK362iJua3', ...]