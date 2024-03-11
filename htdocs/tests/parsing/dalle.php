<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';

$string = '{"completion1":"Recipe Name: Raspberry Glazed Salmon\n\nOrigin: Fusion (combining elements of French cuisine with a modern twist)\n\nCalories: Approximately 400 calories\n\nPreparation Time: 30 minutes\n\nIngredients:\n- 1 salmon fillet\n- 2 tablespoons raspberry preserve\n- 1 tablespoon lemon juice\n- Salt and pepper to taste\n- 1 teaspoon Maille Mustard\n- 1 tablespoon butter\n- Pickled gherkins, for garnish\n\nInstructions:\n1. Preheat the oven to 180\u00b0C.\n2. In a blender, combine the raspberry preserve, lemon juice, Maille Mustard, and a pinch of salt and pepper. Blend until smooth.\n3. Season the salmon fillet with salt and pepper on both sides.\n4. In a skillet, melt the butter over medium heat. Sear the salmon fillet skin-side down for 3-4 minutes until crispy.\n5. Flip the salmon fillet and brush the raspberry glaze generously on top.\n6. Transfer the skillet to the preheated oven and bake for about 8-10 minutes, or until the salmon is cooked to your liking.\n7. Remove the salmon from the oven and let it rest for a few minutes.\n8. Serve the raspberry glazed salmon on a plate, garnished with pickled gherkins for an extra tangy kick.\n\nOptional: Pair the dish with a glass of Ros\u00e9 Wine to complement the flavors of the raspberry glaze and salmon. Enjoy your gourmet meal for one!","completion2":"1. \"A plate of Elegant Raspberry Glazed Salmon\": Showcase a perfectly seared salmon fillet glazed with a luxurious raspberry preserve sauce. Place the dish on a white ceramic plate, garnished with a drizzle of lemon dressing and a sprinkle of fresh herbs. Utilize natural light to highlight the glossy texture of the glaze and the vibrant colors of the dish.\n\n2. \"A bowl of Gourmet Sweetcorn and Tomato Bisque\": Present a creamy and velvety sweetcorn and tomato bisque in a rustic bowl. Garnish the soup with a dollop of sour cream, a sprinkle of finely chopped pickled gherkins, and a swirl of Old El Paso Salsa for a touch of heat. Compose the shot with a crusty bread roll and a small glass of ros\u00e9 wine in the background to evoke a cozy and comforting atmosphere.\n\n3. \"A plate of Mustard Glazed Chicken Breast\": Display a succulent and tender chicken breast coated in a golden Maille mustard glaze on a sleek black plate. Add a side of buttery mashed potatoes and a medley of roasted vegetables to elevate the dish. Incorporate props like a sprig of fresh thyme and a drizzle of balsamic reduction for a touch of elegance and sophistication.\n\n4. \"A bowl of Decadent Cheese Platter\": Curate an exquisite cheese platter featuring an assortment of artisanal cheeses displayed on a rustic wooden board. Arrange the cheese blocks with varying textures and flavors, complemented by a small jar of green spread and a selection of gourmet crackers. Enhance the visual appeal with a drizzle of honey, a cluster of grapes, and a scattering of mixed nuts to create a luxurious and irresistible spread."}';
$string2 = 'Sure, here are four DALL-E prompts based on the guidelines you\'ve provided:

1. "A plate of Norwegian Open-Faced Sandwiches (Smørbrød), elegantly arranged on a rustic wooden board, showcasing the vibrant colors of the thinly sliced GILDE meats and fresh cucumber on a backdrop of dark rye bread, garnished with a delicate dollop of TINE Lettrømme and a sprinkle of green dill, with a side of pickles in a small ceramic dish, all under soft, natural lighting to evoke a homely Scandinavian afternoon."

2. "A plate of gourmet Smørbrød, artistically composed on a sleek, matte-black plate to contrast the creamy textures of TINE Lettrømme and Kavli cheese spread, the sandwich is topped with perfectly arranged slices of GILDE meat and accented with fresh herbs, positioned at the rule of thirds intersection, with intentional negative space to highlight the dish’s simplicity and elegance."

3. "A plate of authentic Norwegian Smørbrød, captured in a high-end culinary setting, where the golden hue of the butter shines against the rich, dark bread, the meat is layered with precision, the sour cream is peaked to perfection, and each sandwich is paired with a crystal glass of chilled Solo, with the focus on the glistening texture of the fresh ingredients and the story of a luxury Nordic meal."

4. "A plate of traditional Smørbrød, prepared for a fine dining experience, with the camera angle set to capture the smooth, creamy spread of the TINE sour cream against the rye bread, accented by a serpentine drizzle of cheese spread, the composition includes silver cutlery gently resting beside the plate and a soft linen napkin, the background blurred to draw attention to the detailed textures and colors of the dish."';

$json = json_decode($string, 1);
#var_dump($json);
$dalle_prompts = openai__extract_the_prompts($json['completion2']);

#var_dump($dalle_prompts);

$json_pretty_encoded_prompts = json_encode($dalle_prompts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$prompt__get_dalle_short_prompts = <<<EOF
QUESTION

Rewrite the JSON prompts using fewer words. Make sure your answer is a numerated list.

PROMPTS

{ {$json_pretty_encoded_prompts} }
EOF;
echo '<pre>' . htmlentities($prompt__get_dalle_short_prompts) . '</pre>';
$completion_short_prompts = promptChatGPT3($prompt__get_dalle_short_prompts);
echo '<pre>' . htmlentities($completion_short_prompts) . '</pre>';
$dalle_short_prompts = openai__extract_the_prompts($completion_short_prompts);
echo '<pre>'; var_dump($dalle_short_prompts); echo '</pre>';

/*
$json_short_prompts = getDelimitedStrings_string($completion_short_prompts, '[', ']', false);
$dalle_short_prompts = json_decode($json_short_prompts, 1);
echo '<pre>' . var_dump($dalle_short_prompts) . '</pre>';
$dalle_short_prompts = json_decode($completion_short_prompts, 1);
echo '<pre>' . var_dump($dalle_short_prompts) . '</pre>';
*/