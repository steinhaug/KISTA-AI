<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
require_once APPDATA_PATH . '/openai_helpers.php';

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

$list_of_ingredients = openai__parse_vision_completion($str);

$prompt = <<<EOF
You are a gourmet chef with 50+ years of experience, your task is to come up with a food reciepe. For this task you will have a 
sortement of groceries to work with, so you will have to base your creation from theese.

CONSIDER THE FOLLOWING WHEN COMPLETING THIS TASK

- I have an oven. If the recipe requires it, preheat the oven to the recommended temperature and duration provided in the recipe.
- I only want to make a dish for one person.
- I have a blender.
- You do not have to use all of the ingredients. Please provide a recipe for one great dish, preferably for lunch or dinner.
- I do not care about side dishes. Only one recipe is needed, with no sides required.
- Please use the metric system, not the American measurement system.
- The name of the dish must be in English, and you must provide an estimation of calories, the country/region of origin of the recipe, and the duration of preparation.
- Optional ingredients are acceptable and can be suggested.

LIST OF AVAILABLE INGREDIENTS

{$list_of_ingredients}

Please provide a gourmet recipe, as if you were the best chef in the world with over 50 years of experience.
EOF;

echo '<pre>';
echo $prompt;
echo '</pre>';