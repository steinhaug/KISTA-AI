<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';

$string = '{"completion1":"Recipe Name: Raspberry Glazed Salmon\n\nOrigin: Fusion (combining elements of French cuisine with a modern twist)\n\nCalories: Approximately 400 calories\n\nPreparation Time: 30 minutes\n\nIngredients:\n- 1 salmon fillet\n- 2 tablespoons raspberry preserve\n- 1 tablespoon lemon juice\n- Salt and pepper to taste\n- 1 teaspoon Maille Mustard\n- 1 tablespoon butter\n- Pickled gherkins, for garnish\n\nInstructions:\n1. Preheat the oven to 180\u00b0C.\n2. In a blender, combine the raspberry preserve, lemon juice, Maille Mustard, and a pinch of salt and pepper. Blend until smooth.\n3. Season the salmon fillet with salt and pepper on both sides.\n4. In a skillet, melt the butter over medium heat. Sear the salmon fillet skin-side down for 3-4 minutes until crispy.\n5. Flip the salmon fillet and brush the raspberry glaze generously on top.\n6. Transfer the skillet to the preheated oven and bake for about 8-10 minutes, or until the salmon is cooked to your liking.\n7. Remove the salmon from the oven and let it rest for a few minutes.\n8. Serve the raspberry glazed salmon on a plate, garnished with pickled gherkins for an extra tangy kick.\n\nOptional: Pair the dish with a glass of Ros\u00e9 Wine to complement the flavors of the raspberry glaze and salmon. Enjoy your gourmet meal for one!","completion2":"1. \"A plate of Elegant Raspberry Glazed Salmon\": Showcase a perfectly seared salmon fillet glazed with a luxurious raspberry preserve sauce. Place the dish on a white ceramic plate, garnished with a drizzle of lemon dressing and a sprinkle of fresh herbs. Utilize natural light to highlight the glossy texture of the glaze and the vibrant colors of the dish.\n\n2. \"A bowl of Gourmet Sweetcorn and Tomato Bisque\": Present a creamy and velvety sweetcorn and tomato bisque in a rustic bowl. Garnish the soup with a dollop of sour cream, a sprinkle of finely chopped pickled gherkins, and a swirl of Old El Paso Salsa for a touch of heat. Compose the shot with a crusty bread roll and a small glass of ros\u00e9 wine in the background to evoke a cozy and comforting atmosphere.\n\n3. \"A plate of Mustard Glazed Chicken Breast\": Display a succulent and tender chicken breast coated in a golden Maille mustard glaze on a sleek black plate. Add a side of buttery mashed potatoes and a medley of roasted vegetables to elevate the dish. Incorporate props like a sprig of fresh thyme and a drizzle of balsamic reduction for a touch of elegance and sophistication.\n\n4. \"A bowl of Decadent Cheese Platter\": Curate an exquisite cheese platter featuring an assortment of artisanal cheeses displayed on a rustic wooden board. Arrange the cheese blocks with varying textures and flavors, complemented by a small jar of green spread and a selection of gourmet crackers. Enhance the visual appeal with a drizzle of honey, a cluster of grapes, and a scattering of mixed nuts to create a luxurious and irresistible spread."}';

$json = json_decode($string, 1);
var_dump($json);

$dalle_prompts = openai__extract_the_prompts($json['completion2']);

var_dump($dalle_prompts);