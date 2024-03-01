<?php
require '../vendor/autoload.php';
require '../credentials.php';

use Orhanerday\OpenAi\OpenAi;
$open_ai = new OpenAi($open_ai_key);

/*
gpt-4-vision-preview
dall-e-3
dall-e-2
- gpt-4-turbo-preview
- gpt-3.5-turbo-0613
whisper-1
tts-1-hd-1106
tts-1-hd
- gpt-3.5-turbo-0125
- gpt-3.5-turbo
- gpt-3.5-turbo-0301
tts-1
tts-1-1106
- gpt-3.5-turbo-1106
- gpt-3.5-turbo-16k
- gpt-4
- gpt-4-0613
-, gpt-3.5-turbo-16k-0613
- gpt-4-0125-preview
- text-embedding-3-small
- text-embedding-3-large

* text-embedding-ada-002
* babbage-002
* davinci-002
* gpt-3.5-turbo-instruct-0914
* gpt-3.5-turbo-instruct
* gpt-4-1106-preview

*/


$complete = $open_ai->completion([
   'model' => 'gpt-3.5-turbo-instruct',
   'prompt' => 'Tell me a funny joke that makes a software developer laugh',
   'temperature' => 0.9,
   'max_tokens' => 150,
   'frequency_penalty' => 0,
   'presence_penalty' => 0.6,
]);

var_dump($complete);
echo "<br><br>";

// decode response
$d = json_decode($complete);
var_dump($d);