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

$prompta_dalla = '"A bowl of Gourmet Egg Custard": Present a delicately baked egg custard in a bowl, topped with a dusting of gelatin powder for an added touch of elegance. Include a side of boxed raisins and a decorative spoon to enhance the composition. Utilize leading lines with the handle of the spoon to draw the viewer\'s eye to the creamy custard.';

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

    echo htmlentities($data->url) . '<hr>';
    echo '<img src="' . $data->url . '">';
}

krumo($response);
$json_all = $response->toArray();
echo htmlentities( json_encode($json_all) );
