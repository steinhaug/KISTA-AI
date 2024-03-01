<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

/*
* text-embedding-ada-002
* babbage-002
* davinci-002
* gpt-3.5-turbo-instruct-0914
* gpt-3.5-turbo-instruct
* gpt-4-1106-preview
*/
$response = $client->completions()->create([
    'model' => 'gpt-3.5-turbo-instruct',
    'prompt' => 'Say this is a test',
    'max_tokens' => 6,
    'temperature' => 0
]);

$response->id; // 'cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7'
$response->object; // 'text_completion'
$response->created; // 1589478378
$response->model; // 'gpt-3.5-turbo-instruct'

foreach ($response->choices as $result) {
    $result->text; // '\n\nThis is a test'
    $result->index; // 0
    $result->logprobs; // null
    $result->finishReason; // 'length' or null

    echo $result->text;
}

$response->usage->promptTokens; // 5,
$response->usage->completionTokens; // 6,
$response->usage->totalTokens; // 11

$response->toArray(); // ['id' => 'cmpl-uqkvlQyYK7bGYrRHQ0eXlWi7', ...]