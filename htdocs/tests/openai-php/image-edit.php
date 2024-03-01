<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$response = $client->images()->edit([
    'image' => fopen('image_edit_original.png', 'r'),
    'mask' => fopen('image_edit_mask.png', 'r'),
    'prompt' => 'A sunlit indoor lounge area with a pool containing a flamingo',
    'n' => 1,
    'size' => '256x256',
    'response_format' => 'url',
]);

$response->created; // 1589478378

foreach ($response->data as $data) {
    $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
    $data->b64_json; // null
}

$response->toArray(); // ['created' => 1589478378, data => ['url' => 'https://oaidalleapiprodscus...', ...]]