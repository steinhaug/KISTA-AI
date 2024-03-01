<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$file_id = 'file-qFrNA8CUu7Npmqghx9O4l2cD';

$result = $client->files()->download($file_id); // '{"prompt": "<prompt text>", ...'

krumo($result);
echo htmlentities( $result );
