<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$base64Image = imageToBase64('../../../assets/fridge/003.jpg');

$promptQue = [];

$promptQue[] = [
    'type' => 'image_url',
    'image_url' => ['url' => $base64Image]
];

$promptQue[] = [
    'type' => 'text',
    'text' => 'Analyse this photograph and provide a detailed description of its contents. Focus on identifying and listing all visible items, with particular attention to food items present. '
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
    $response = $client->chat()->create($settings);

    // Now, let's ask if the image is in color or black and white without re-uploading the image
    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => 'Your job is to detect as many different groceries / seperate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as an item list with quantities.']]
    ];

    // Add the new question to the messages and maintain the context
    $settings['messages'][] = $additionalQuestion;

    // Make another request with the additional question
    $response2 = $client->chat()->create($settings);
    //echo $response2;


} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} finally {
    echo 'OpenAI API done.<br>' . "\n";
}

if( isset($response) ){

    krumo($response);
    //echo $response;
    //echo "<br>\n";
    //var_dump( $response );
 
    foreach ($response->choices as $result) {
        $result->index; // 0
        $result->message->role; // 'assistant'
        $result->message->content; // '\n\nHello there! How can I assist you today?'
        $result->finishReason; // 'stop'

        echo '** content ';
    }
    echo "<br>\n";

    $json_all = $response->toArray();

    $meta = $response->meta();
    $json_meta = $meta->toArray();

    echo htmlentities( json_encode($json_meta) );
    echo '<br>' . "\n";
    echo htmlentities( json_encode($json_all) );

}

echo '<hr>';


if( isset($response2) ){

    krumo($response2);
    //echo $response;
    //echo "<br>\n";
    //var_dump( $response );
 
    foreach ($response2->choices as $result) {
        $result->index; // 0
        $result->message->role; // 'assistant'
        $result->message->content; // '\n\nHello there! How can I assist you today?'
        $result->finishReason; // 'stop'

        echo '** content ';
    }
    echo "<br>\n";

    $json_all = $response2->toArray();

    $meta = $response2->meta();
    $json_meta = $meta->toArray();

    echo htmlentities( json_encode($json_meta) );
    echo '<br>' . "\n";
    echo htmlentities( json_encode($json_all) );

}


/*

{"openai-model":"gpt-4-1106-vision-preview","openai-organization":"user-mzxmp72hru22dzhng3i7umkv","openai-processing-ms":20939,"openai-version":"2020-10-01","x-ratelimit-limit-requests":500,"x-ratelimit-limit-tokens":10000,"x-ratelimit-remaining-requests":497,"x-ratelimit-remaining-tokens":8754,"x-ratelimit-reset-requests":"6m34.772s","x-ratelimit-reset-tokens":"7.476s","x-request-id":"req_5e50bb9fbb47d226599868494d05e02a"}
{"id":"chatcmpl-8xpiMuW8lFMPXuFOEYCOhi8LySun9","object":"chat.completion","created":1709270834,"model":"gpt-4-1106-vision-preview","choices":[{"index":0,"message":{"role":"assistant","content":"This photograph depicts the interior of a refrigerator that is stocked with various food items and condiments. Here's a detailed list of visible items, from top to bottom and left to right, as much as they can be identified:\n\nTop Shelf:\n- A box of raisins at the far left.\n- A carton of eggs on the top compartment, partially visible.\n- A package of Quaker oats.\n- Orange juice carton (brand not clearly visible).\n- A packet that appears to have olives or similar food items inside.\n\nSecond Shelf:\n- A tub of butter or margarine.\n- A small jar, possibly containing a seasoning or spread.\n- A bottle of ketchup.\n- Two bottles with red caps, likely condiments.\n- A brown bottle, possibly a sauce or vinegar.\n- A tub or block of cheese.\n- A small container or cardboard box, not identifiable.\n\nThird Shelf:\n- A can of Coca-Cola.\n- Bottles of sauces and condiments, including what seems like soy sauce, chili or tomato sauce, and other dressings.\n- A container with a yellow label, possibly mustard or a similar condiment.\n- Containers or jars that include red and gold lids, indicating they could be spices or condiments.\n- A wrapped item in the front right that may be cheese.\n- A stick of butter or margarine named 'Color Life.'\n\nDoor Shelves:\n- A variety of bottles at the bottom section of the door, which may include oil, vinegar, soy sauce, and a bottle with a green and white label that could be a juice or beverage.\n- The middle section of the door includes what appears to be a jar of jam, mayonnaise, and other assorted condiments.\n- The top section of the door has bottles and jars too, one of which appears to be a sauce while the others are undiscernible.\n\nCrisper Drawer:\n- The crisper drawer at the bottom is partially visible and appears to have some vegetables or fruits inside, but the items are not clear enough to be explicitly identified.\n\nThe refrigerator shelves also host assorted items that are difficult to describe due to the lack of clarity in the image or their packaging being turned away from view. It's clear this is a well-stocked fridge with a focus on condiments and basic ingredients for cooking and preparation of food."},"finish_reason":"stop"}],"usage":{"prompt_tokens":803,"completion_tokens":472,"total_tokens":1275}}

{"openai-model":"gpt-4-1106-vision-preview","openai-organization":"user-mzxmp72hru22dzhng3i7umkv","openai-processing-ms":42621,"openai-version":"2020-10-01","x-ratelimit-limit-requests":500,"x-ratelimit-limit-tokens":10000,"x-ratelimit-remaining-requests":496,"x-ratelimit-remaining-tokens":8693,"x-ratelimit-reset-requests":"9m6.069s","x-ratelimit-reset-tokens":"7.842s","x-request-id":"req_67ef89601a2aca1e94fb467f513c7274"}
{"id":"chatcmpl-8xpihLO1pSId2KVvuczRau7QwuVYk","object":"chat.completion","created":1709270855,"model":"gpt-4-1106-vision-preview","choices":[{"index":0,"message":{"role":"assistant","content":"Based on the photograph provided, I'll enumerate the visible items within the refrigerator, organized by the refrigerator shelves:\n\n**Top Shelf:**\n1. A carton with at least four visible eggs (total quantity could be up to a dozen).\n2. An orange box, likely to be a dry food item such as whole grain oatmeal or a similar product.\n3. A small container, partially obscured, likely holding a food spread or condiment.\n4. A tub of margarine or butter (brand starting with \"Oliv...,\" possibly \"Olivio\" or similar).\n5. Two packets or boxes with purple and yellow coloring, specifics unclear.\n\n**Second Shelf:**\n1. Two Coca-Cola cans.\n2. At least two jars of a red-colored condiment or sauce (one larger and one smaller, potential hot sauce or similar).\n3. Two bottles with yellow caps and red labels, potentially a type of oil or dressing.\n4. A bottle with a red label and green cap, likely a sauce or condiment.\n5. A bottle with a green label and white cap, content unclear.\n6. A package or container with an orange label, placed upright, content unclear.\n\n**Third Shelf:**\n1. A transparent container with a white lid, specifics of content unclear.\n2. A smaller transparent container or jar next to it with a gold lid, possibly a spread or condiment.\n3. At least two dark-colored bottles which could be soy sauce or another similar condiment.\n4. A container with a yellow label and red cap, possibly mustard or another condiment.\n5. A bottle with a white label and red cap, specifics unclear.\n6. An item with a green label in the foreground, likely a bottle or jar, contents unclear.\n7. A tall clear bottle with a yellow liquid, possibly a cooking oil.\n8. A red-capped bottle, partly obscured, content unclear.\n\n**Bottom Shelf:**\n1. A green plastic bottle, label not fully visible, potentially a beverage or sauce.\n2. A packet or box with the label \"Gelatine\" on it.\n3. A bottle with a red label and cap, item specifics unclear.\n\n**Door Shelves:**\n1. An obscured item with a red cap, possibly ketchup or another red sauce.\n2. A tall bottle with a label featuring a bird, likely a sauce like Sriracha.\n3. A green-labeled bottle, potentially an Asian cooking ingredient or sauce.\n4. A white plastic bottle with a label, partially obscured, content unclear.\n\nThe quantities listed are based on the visible count; there might be additional items of the same type behind those in view. As for the obscured and unclear items, without additional visual information or context, providing exact details is challenging."},"finish_reason":"stop"}],"usage":{"prompt_tokens":854,"completion_tokens":565,"total_tokens":1419}}

*/