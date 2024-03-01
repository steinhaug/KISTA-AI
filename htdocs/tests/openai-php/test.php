<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';


$client = OpenAI::client($open_ai_key);

$result = $client->chat()->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!'],
    ],
]);

echo $result->choices[0]->message->content;
