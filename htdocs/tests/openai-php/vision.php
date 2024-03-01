<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

function imageToBase64($image_path, $add_pre_markup=true) {

    // Check if the file exists
    if (!file_exists($image_path)) {
        return false;
    }
    
    // Get the file extension
    $extension = pathinfo($image_path, PATHINFO_EXTENSION);
    
    // Read the image file contents
    $image_data = file_get_contents($image_path);
    
    // Convert the image data to Base64 encoding
    if( $add_pre_markup )
        $base64_image = 'data:image/' . $extension . ';base64,' . base64_encode($image_data);
        else
        $base64_image = base64_encode($image_data);

    // Return the Base64 encoded image
    return $base64_image;
}

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


/*

{"openai-model":"gpt-4-1106-vision-preview","openai-organization":"user-mzxmp72hru22dzhng3i7umkv","openai-processing-ms":35659,"openai-version":"2020-10-01","x-ratelimit-limit-requests":500,"x-ratelimit-limit-tokens":10000,"x-ratelimit-remaining-requests":499,"x-ratelimit-remaining-tokens":8693,"x-ratelimit-reset-requests":"2m52.8s","x-ratelimit-reset-tokens":"7.842s","x-request-id":"req_2212b29080a3134a735131fb6c8e28ff"}
{"id":"chatcmpl-8xpccYAy1CDD5MKkR9HuIsIjsb3lZ","object":"chat.completion","created":1709270478,"model":"gpt-4-1106-vision-preview","choices":[{"index":0,"message":{"role":"assistant","content":"Certainly, here's a detailed list of the visible items in the refrigerator with estimated quantities:\n\n1. Dairy and Eggs:\n - A carton of eggs (appears to be a dozen, but only 5 eggs are visible)\n - Butter or margarine (1 small container in the door)\n - A bottle of milk or liquid dairy creamer (1, in the door)\n\n2. Condiments, Sauces, and Oils:\n - Soy sauce (at least 2 bottles, of different sizes and brands)\n - Ketchup (1 bottle)\n - Mayonnaise or a similar white condiment (1 bottle)\n - Mustard (1 bottle)\n - Oyster sauce (1 bottle)\n - Chili sauce or sriracha (1 bottle)\n - Salad dressing or vinegar (1 clear bottle with a green cap)\n - Cooking oil (1 large bottle with a green label)\n - Barbecue sauce or a similar dark-colored sauce (1 bottle)\n\n3. Beverages:\n - Soft drinks (at least 2 cans, one appears to be Coca-Cola)\n - Bottled water or possibly a sports drink (1 bottle, partly obscured)\n\n4. Grains and Baking Supplies:\n - Whole grain oatmeal (1 box)\n - Gelatin powder or similar (1 packet)\n\n5. Fresh Produce:\n - A glimpse of what could be fruit or vegetables in the crisper drawer (specifics not visible)\n\n6. Miscellaneous:\n - A couple of small bottles\/jars with yellow lids (contents unknown)\n - Various small jars that could contain spices, preserves, or condiments (at least 3, specifics not visible)\n - Small rectangular plastic containers which might hold leftovers or specialized food items (at least 2)\n - Purple and white box of an unknown item (1 box)\n\nIt's difficult to be certain of some items, as their labels are not fully visible, and some might not be food items at all, such as medication or food supplements. Some containers may contain homemade or bulk items without recognizable branding or packaging."},"finish_reason":"stop"}],"usage":{"prompt_tokens":854,"completion_tokens":428,"total_tokens":1282}}

*/