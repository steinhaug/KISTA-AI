<?php
require '../vendor/autoload.php';
require '../credentials.php';

use Orhanerday\OpenAi\OpenAi;
$open_ai = new OpenAi($open_ai_key);

/*
* text-embedding-ada-002
* babbage-002
* davinci-002
* gpt-3.5-turbo-instruct-0914
* gpt-3.5-turbo-instruct
* gpt-4-1106-preview
*/

$opts = [
   'model' => 'gpt-3.5-turbo-instruct',
   'prompt' => "Hello",
   'temperature' => 0.9,
   "max_tokens" => 150,
   "frequency_penalty" => 0,
   "presence_penalty" => 0.6,
   "stream" => true,
];

header('Content-type: text/event-stream');
header('Cache-Control: no-cache');

$open_ai->completion($opts, function ($curl_info, $data) {
   echo $data;
   echo PHP_EOL;
   ob_flush();
   flush();
   return strlen($data);
});


