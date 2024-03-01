<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);


$type = 'stock photo'; //'concept art';
$aim = 'sells the reciepe'; // 'makes you go'
$withof = 'make the food look great'; // 'hungry';
$style = 'luxurious'; // 'photo-realistic';
$task = 'article in a chef magazine'; // 'a restaurant menu';

$prompta_dalla = "Create a $type that will $aim $withof in a $style for $task. ";

$prompta_dalla .= 'A delicious great looking dish, beautifully served on a white plate on an white table. Å wine glass is standing behind the plate. The dish is ';
$prompta_dalla .= 'called Salsa Parmigiano Gherkin Rolls, has a golden brown texture on the cheese and a drizzle of red salsa wine reduction. ';
$prompta_dalla .= ' ';

$response = $client->images()->create([
    'model' => 'dall-e-3',
    'prompt' => $prompta_dalla,
    'n' => 1,
    'size' => '1024x1024',
    'response_format' => 'url',
]);

$response->created; // 1589478378

foreach ($response->data as $data) {
    $data->url; // 'https://oaidalleapiprodscus.blob.core.windows.net/private/...'
    $data->b64_json; // null

    echo '<img src="' . $data->url . '">';
}

krumo($response);
$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );
