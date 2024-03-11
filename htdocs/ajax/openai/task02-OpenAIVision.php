<?php
logfile('Task2: init');

$vision_prompt = "Your job is to detect as many different groceries / seperate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as a numerated item list with the item's quantities.";
$client = OpenAI::client($open_ai_key);
$base64Image = imageToBase64($imgIn);
$promptQue = [];
$promptQue[] = [
    'type' => 'image_url',
    'image_url' => ['url' => $base64Image]
];
$promptQue[] = [
    'type' => 'text',
    'text' => $vision_prompt
];
$messagesArray = [];
$messagesArray[] = [
    'role' => 'user',
    'content' => $promptQue
];
$settings['model'] = 'gpt-4-vision-preview';
$settings['messages'] = $messagesArray;
$settings['max_tokens'] = 1200;
try {
    logfile('Task2: response');
    $response = $client->chat()->create($settings);
    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => 'If there is a refridgerator visible in the image answer answer one word only YES, if no refridgerator is visible answer one word only NO.']]
    ];
    $settings['messages'][] = $additionalQuestion;
    logfile('Task2: response2');
    $response2 = $client->chat()->create($settings);
} catch (Exception $e) {
    throw new OpenAIException($e->getMessage());
}

$chatgpt_result1 = '';
$chatgpt_result2 = '';

foreach ($response->choices as $result) {
    $chatgpt_result1 = $result->message->content;
}
$json_all = $response->toArray();
$meta = $response->meta();
$json_meta = $meta->toArray();
$log[] = 'ChatGPT Vision:';
$log['vision_m1'] = json_encode($json_meta);
$log['vision_q1'] = json_encode($json_all);

foreach ($response2->choices as $result) {
    $chatgpt_result2 = $result->message->content;
}
$json_all = $response2->toArray();
$meta = $response2->meta();
$json_meta = $meta->toArray();
$log['vision_m2'] = json_encode($json_meta);
$log['vision_q2'] = json_encode($json_all);

if( str_contains($chatgpt_result2,'NO') ){
    logfile('Task2: No fridge');
    $chatgpt_result3 = '';
    $chatgpt_result4 = '';
    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => 'I want you to be extremely creative and analyze the image in detail. Come up with an artistic expression and describe the image in vividly and in a psychedelic fashion. Start the description by pointing out that this is a piece of art, use your imagination and write around 150 words.']]
    ];
    $settings['messages'][] = $additionalQuestion;
    $response3 = $client->chat()->create($settings);
    foreach ($response3->choices as $result) {
        $chatgpt_result3 = $result->message->content;
    }
    logfile('Task2: promptChatGPT3()');
    $chatgpt_result4 = promptChatGPT3('Come up with 4 different Dall-E prompts, make sure they all are made with psychedelic and vivid colors. Use the following text as your source:' . "\n\TEXT - - -\n" . $chatgpt_result3);

    /*
    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => 'Use the last completion and come up with 4 different Dall-E prompts, make sure they all are made with psychedelic and vivid colors.']]
    ];
    $settings['messages'][] = $additionalQuestion;
    $response4 = $client->chat()->create($settings);
    foreach ($response4->choices as $result) {
        $chatgpt_result4 = $result->message->content;
    }
    */
    setUploadStatus($upload_id, 'task2', [
        'chatgpt_result1'=>$chatgpt_result1,
        'chatgpt_result2'=>$chatgpt_result2,
        'chatgpt_result3'=>$chatgpt_result3,
        'chatgpt_result4'=>$chatgpt_result4
    ]);
    $dalle_prompts = openai__extract_the_prompts($chatgpt_result4);
    logfile('Task2: Make no-fridge image');
    promptDalle(array_shift($dalle_prompts));
    throw new SegWayImage('Aight!');
}


$get_curated_list = <<<EOF
QUESTION:
Please create a numerated list of the ingredients mentioned in the following text, one ingredients pr line. The text provided has the amount of the ingredient mentioned at the end of the sentence, remember to keep this if found.

TEXT:
{$chatgpt_result1}
EOF;

logfile('Task2: get_curated_list');
$chatgpt_curated_list = promptChatGPT3($get_curated_list);
$list_of_ingredients = openai__parse_vision_completion($chatgpt_curated_list);
$log['list'] = $list_of_ingredients;

if( empty($list_of_ingredients) ){
    $sql = new sqlbuddy;
    $sql->que('log', json_encode($log, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), 'text');
    $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
    throw new OpenAIException('Missing ingredients');
}
