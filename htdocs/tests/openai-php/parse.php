<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
require_once APPDATA_PATH . '/openai_helpers.php';

//$string = '["Thumbnail, created.","Thumbnail, DB updated.","ChatGPT Vision:"," - first question:","{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":6729,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":499,\"x-ratelimit-remaining-tokens\":8754,\"x-ratelimit-reset-requests\":\"2m52.8s\",\"x-ratelimit-reset-tokens\":\"7.476s\",\"x-request-id\":\"req_bbfee77becde1060204c1557210a267c\"}","{\"id\":\"chatcmpl-8yzpCALogUK1LxaXKu2Qx4NOqAdGo\",\"object\":\"chat.completion\",\"created\":1709548026,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"The image you have provided is not a photograph but appears to be a digital rendering or a stylized artwork. It depicts a figure that seems to be a human form in a semi-abstract or impressionistic style, characterized by blurred lines and a monochrome palette. The figure is leaning forward and has one finger extended, but due to the blurred nature of the image, it\'s difficult to determine the exact action or the context. \\n\\nThere are no clear or discernible items in this image, especially no food items visible. All we see is the figure which appears to be rendered with a smooth surface, and there is a notable lack of detail due to the artistic style of the image. The background is similarly blurred and does not provide any additional context or items to describe. Due to the abstract nature of the artwork, it\'s not possible to give a more specific description regarding objects or food.\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":463,\"completion_tokens\":179,\"total_tokens\":642}}"," - second question:","{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":2952,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":498,\"x-ratelimit-remaining-tokens\":8677,\"x-ratelimit-reset-requests\":\"5m38.371s\",\"x-ratelimit-reset-tokens\":\"7.933s\",\"x-request-id\":\"req_938e6c72b49833bcbe885eff765170c3\"}","{\"id\":\"chatcmpl-8yzpLGX1UJsdJYIIRbfQk3TdgCatU\",\"object\":\"chat.completion\",\"created\":1709548035,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"NO\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":500,\"completion_tokens\":1,\"total_tokens\":501}}"]';
$string = '{"0":"Thumbnail, created.","1":"Thumbnail, DB updated.","2":"ChatGPT Vision:","vision_m1":"{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":13427,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":499,\"x-ratelimit-remaining-tokens\":8738,\"x-ratelimit-reset-requests\":\"2m52.8s\",\"x-ratelimit-reset-tokens\":\"7.572s\",\"x-request-id\":\"req_37b6abb1c0ef50549cba2dc0057525d8\"}","vision_q1":"{\"id\":\"chatcmpl-8z2tTf0uSfAUEuaFGWGLsVORokWbo\",\"object\":\"chat.completion\",\"created\":1709559823,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"Certainly, I will list the different groceries and estimate quantities based on what I can see from the image:\\n\\n1. Eggs \\u2013 A carton visible, assuming it\'s a standard size, approximately 6 eggs can be seen, but it could hold up to 12.\\n2. Raisins \\u2013 1 packet.\\n3. Whole oatmeal \\u2013 1 box.\\n4. Canned food \\u2013 1 can visible.\\n5. Bottles of condiments\\\/sauces \\u2013 At least 10 different bottles with various labels, difficult to determine duplicates without clearer visibility of labels.\\n6. Packets of sauce or seasoning \\u2013 At least 3 different packets\\\/containers.\\n7. Block of cheese (in clear plastic container) \\u2013 1.\\n8. Butter or margarine (yellow tub) \\u2013 1.\\n9. Carton of milk or juice \\u2013 1 visible.\\n10. Soft drink cans \\u2013 At least 2 cans (Coca-Cola).\\n11. More condiments\\\/sauces \\u2013 Minimum of 6, diverse types.\\n12. Cooking oil (large bottle) \\u2013 1.\\n13. Container (possibly of spread or dairy product) \\u2013 1.\\n14. Bottle with red cap (possibly ketchup or sauce) \\u2013 1.\\n15. Gelatin powder \\u2013 1 packet\\\/container.\\n16. Asiago cheese (in plastic container based on the label) \\u2013 1.\\n\\nNot all items can be clearly identified due to the angle and the items being partially obscured. Further, quantities may vary especially for items that are not fully visible.\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":819,\"completion_tokens\":314,\"total_tokens\":1133}}","vision_m2":"{\"openai-model\":\"gpt-4-1106-vision-preview\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":2222,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":500,\"x-ratelimit-limit-tokens\":10000,\"x-ratelimit-remaining-requests\":498,\"x-ratelimit-remaining-tokens\":8703,\"x-ratelimit-reset-requests\":\"5m27.708s\",\"x-ratelimit-reset-tokens\":\"7.782s\",\"x-request-id\":\"req_14ee26d34a5d8166c745e12eb0dc0e11\"}","vision_q2":"{\"id\":\"chatcmpl-8z2tdeJTTuRfJNoghmnLkQ3mZJd2K\",\"object\":\"chat.completion\",\"created\":1709559833,\"model\":\"gpt-4-1106-vision-preview\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"YES\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":856,\"completion_tokens\":1,\"total_tokens\":857}}","list":"- Eggs \u2013 A carton visible, assuming it\'s a standard size, approximately 6 eggs can be seen, but it could hold up to 12.\n- Raisins \u2013 1 packet.\n- Whole oatmeal \u2013 1 box.\n- Canned food \u2013 1 can visible.\n- Bottles of condiments\/sauces \u2013 At least 10 different bottles with various labels, difficult to determine duplicates without clearer visibility of labels.\n- Packets of sauce or seasoning \u2013 At least 3 different packets\/containers.\n- Block of cheese (in clear plastic container) \u2013 1.\n- Butter or margarine (yellow tub) \u2013 1.\n- Carton of milk or juice \u2013 1 visible.\n- Soft drink cans \u2013 At least 2 cans (Coca-Cola).\n- More condiments\/sauces \u2013 Minimum of 6, diverse types.\n- Cooking oil (large bottle) \u2013 1.\n- Container (possibly of spread or dairy product) \u2013 1.\n- Bottle with red cap (possibly ketchup or sauce) \u2013 1.\n- Gelatin powder \u2013 1 packet\/container.\n- Asiago cheese (in plastic container based on the label) \u2013 1.","3":"ChatGPT Chat:","chat_m1":"{\"openai-model\":\"gpt-3.5-turbo-0125\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":8619,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":10000,\"x-ratelimit-limit-tokens\":60000,\"x-ratelimit-remaining-requests\":9999,\"x-ratelimit-remaining-tokens\":59464,\"x-ratelimit-reset-requests\":\"8.64s\",\"x-ratelimit-reset-tokens\":\"536ms\",\"x-request-id\":\"req_49389730ae06e9d985d79d5afd4d7b39\"}","chat_q1":"{\"id\":\"chatcmpl-8z2te0Yny1rVl1yFTtNNRByMSsZPW\",\"object\":\"chat.completion\",\"created\":1709559834,\"model\":\"gpt-3.5-turbo-0125\",\"system_fingerprint\":\"fp_2b778c6b35\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"Recipe: Baked Oatmeal Raisin Egg Custard\\n\\nCalories: Approximately 550 calories\\nCountry\\\/Region of origin: Western cuisine\\nPreparation duration: 45 minutes\\n\\nIngredients:\\n- 2 eggs\\n- 1\\\/4 cup whole oatmeal\\n- 1\\\/4 cup raisins\\n- 1\\\/4 cup milk\\n- 1 tbsp butter\\n- 1 tsp vanilla extract\\n- Pinch of salt\\n- Pinch of cinnamon\\n- Pinch of nutmeg\\n- 1\\\/4 cup grated Asiago cheese\\n- 1 tbsp honey (optional)\\n\\nInstructions:\\n1. Preheat the oven to 180\\u00b0C.\\n2. In a blender, combine eggs, oatmeal, raisins, milk, butter, vanilla extract, salt, cinnamon, and nutmeg. Blend until smooth.\\n3. Pour the mixture into a greased oven-safe dish.\\n4. Sprinkle grated Asiago cheese on top of the mixture.\\n5. Bake in the preheated oven for 25-30 minutes, or until the custard is set and the top is golden brown.\\n6. Drizzle honey on top before serving, if desired.\\n\\nThis Baked Oatmeal Raisin Egg Custard is a delicious and hearty dish that combines the sweetness of raisins with the rich and creamy texture of the custard. The Asiago cheese adds a nice touch of flavor and richness to the dish. Enjoy this gourmet meal for a satisfying lunch or dinner!\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":502,\"completion_tokens\":304,\"total_tokens\":806}}","chat_m2":"{\"openai-model\":\"gpt-3.5-turbo-0125\",\"openai-organization\":\"user-mzxmp72hru22dzhng3i7umkv\",\"openai-processing-ms\":11490,\"openai-version\":\"2020-10-01\",\"x-ratelimit-limit-requests\":10000,\"x-ratelimit-limit-tokens\":60000,\"x-ratelimit-remaining-requests\":9999,\"x-ratelimit-remaining-tokens\":58942,\"x-ratelimit-reset-requests\":\"8.64s\",\"x-ratelimit-reset-tokens\":\"1.057s\",\"x-request-id\":\"req_e24a71c05dd541b2628624d0da452c2e\"}","chat_q2":"{\"id\":\"chatcmpl-8z2tnJ9Mt5GXCt1GahanfkE87Txy5\",\"object\":\"chat.completion\",\"created\":1709559843,\"model\":\"gpt-3.5-turbo-0125\",\"system_fingerprint\":\"fp_2b778c6b35\",\"choices\":[{\"index\":0,\"message\":{\"role\":\"assistant\",\"content\":\"1. A plate of \\\"Savory Asiago Oatmeal Souffl\\u00e9\\\"\\n   - Styling the Shot: Showcase the decadent texture of the souffl\\u00e9 by capturing the golden brown crust on top against a backdrop of a rustic wooden table.\\n   - Composition: Place the souffl\\u00e9 on a white plate positioned at the intersection of the rule of thirds, with a sprinkle of grated Asiago cheese on top to enhance visual appeal.\\n   - Details: Highlight the steam rising delicately from the souffl\\u00e9 to convey its freshly baked warmth and savory aroma.\\n\\n2. A plate of \\\"Raisin and Cheese Stuffed Omelette with Ketchup Drizzle\\\"\\n   - Styling the Shot: Create a luxurious ambiance by pairing the omelette with a side of colorful mixed greens on fine china tableware.\\n   - Composition: Utilize leading lines by strategically placing a silver fork and knife beside the omelette to guide the viewer\'s eye towards the cheesy and raisin-filled center.\\n   - Details: Capture the glossy sheen of the ketchup drizzle cascading elegantly over the omelette, emphasizing the savory-sweet flavor combination.\\n\\n3. A bowl of \\\"Gelatin-Infused Milk Panna Cotta with Caramelized Raisins\\\"\\n   - Styling the Shot: Opt for a minimalist approach by presenting the panna cotta in a delicate glass bowl, set against a soft pastel-hued background.\\n   - Composition: Experiment with negative space to draw attention to the creamy texture of the panna cotta, showcasing the caramelized raisins atop as a focal point.\\n   - Details: Highlight the translucency of the gelatin-infused dessert by capturing the light playfully reflecting off its surface, enhancing its luxurious appearance.\\n\\n4. A plate of \\\"Cheese-Stuffed Oatmeal Fritters with Tangy Dipping Sauce\\\"\\n   - Styling the Shot: Contrast the rustic appeal of the fritters with a modern, sleek plating on a dark slate surface, accompanied by a vibrant red dipping sauce in a small dish.\\n   - Composition: Play with geometric shapes by arranging the fritters in a triangular pattern on the plate, with a drizzle of sauce creating dynamic movement.\\n   - Details: Zoom in on the gooey melted cheese oozing out from the crispy fritters, capturing the indulgent richness of the dish in a visually striking manner.\"},\"finish_reason\":\"stop\"}],\"usage\":{\"prompt_tokens\":893,\"completion_tokens\":495,\"total_tokens\":1388}}"}';

##$json = json_decode($string, true, 64, JSON_BIGINT_AS_STRING);

    json_decode($string, true);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            echo ' - No errors';
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        break;
        default:
            echo ' - Unknown error';
        break;
    }

    echo PHP_EOL;


var_dump($json);
foreach($json as $key=>$val){

    echo $key . ':' . $val;
    echo '<hr>';

}
exit;

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