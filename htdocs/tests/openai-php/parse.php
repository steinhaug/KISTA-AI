<?php
/*
$string = '["Thumbnail, created.","Thumbnail, DB updated.","ChatGPT Vision:"," - first question:","{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":6729,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":499,\"x-ratelimit-remaining-tokens\":8754,\"x-ratelimit-reset-requests\":\"2m52.8s\",\"x-ratelimit-reset-tokens\":\"7.476s\",\"x-request-id\":\"req_bbfee77becde1060204c1557210a267c\"}","{\"id\":\"chatcmpl-8yzpCALogUK1LxaXKu2Qx4NOqAdGo\",\"object\":\"chat.completion\",\"created\":1709548026,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"The image you have provided is not a photograph but appears to be a digital rendering or a stylized artwork. It depicts a figure that seems to be a human form in a semi-abstract or impressionistic style, characterized by blurred lines and a monochrome palette. The figure is leaning forward and has one finger extended, but due to the blurred nature of the image, it\'s difficult to determine the exact action or the context. \\n\\nThere are no clear or discernible items in this image, especially no food items visible. All we see is the figure which appears to be rendered with a smooth surface, and there is a notable lack of detail due to the artistic style of the image. The background is similarly blurred and does not provide any additional context or items to describe. Due to the abstract nature of the artwork, it\'s not possible to give a more specific description regarding objects or food.\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":463,\"completion_tokens\":179,\"total_tokens\":642}}"," - second question:","{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":2952,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":498,\"x-ratelimit-remaining-tokens\":8677,\"x-ratelimit-reset-requests\":\"5m38.371s\",\"x-ratelimit-reset-tokens\":\"7.933s\",\"x-request-id\":\"req_938e6c72b49833bcbe885eff765170c3\"}","{\"id\":\"chatcmpl-8yzpLGX1UJsdJYIIRbfQk3TdgCatU\",\"object\":\"chat.completion\",\"created\":1709548035,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"NO\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":500,\"completion_tokens\":1,\"total_tokens\":501}}"]';

$json = json_decode($string, true);

foreach($json as $key=>$val){

    echo $key . ':' . $val;
    echo '<hr>';

}
*/

$str = 'Sure, based on the visible items in the refrigerator, here\'s a list with estimated quantities:

1. Eggs - 1 carton (appears to have at least 6 eggs visible)
2. Raisins - 1 box
3. Whole Grain Oatmeal - 1 container
4. Soda cans (Coca-Cola) - at least 2 visible
5. Butter or margarine - 1 container
6. Assorted small condiment jars (e.g., jams, sauces) - approximately 5 jars
7. Carton of milk or juice - 1
8. Assorted sauces and condiments in bottles (e.g., ketchup, mustard, soy sauce) - approximately 12 bottles
9. Cooking oil (large bottle) - 1
10. Gelatine (bottle) - 1
11. Mayonnaise or similar spread - 1 bottle
12. Salad dressing or similar sauce - 1 bottle

Please note that the quantities are estimates based on what\'s visible, and there may be additional items behind those shown that cannot be enumerated.';

function openai__parse_vision_answer($string_before) {
    // Split the string into an array of lines
    $lines = explode("\n", $string_before);
    $result = "";

    foreach ($lines as $line) {
        // Check if the line starts with a number followed by a period
        if (preg_match('/^\d+\.\s*(.*)$/', $line, $matches)) {
            // Remove the markdown bold syntax
            $cleanLine = str_replace('**', '', $matches[1]);
            // Append the formatted line to the result string
            $result .= "- " . $cleanLine . "\n";
        }
    }

    return trim($result);
}

echo '<pre>';
echo $str;
echo '</pre>';
echo '<pre>';
echo openai__parse_vision_answer($str);
echo '</pre>';
