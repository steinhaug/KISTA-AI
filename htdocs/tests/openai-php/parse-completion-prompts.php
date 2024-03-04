<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
require_once APPDATA_PATH . '/openai_helpers.php';



// Example usage
$string = "1. \"A plate of Elegant Oatmeal Soufflé\": Showcase a beautifully plated oatmeal soufflé, garnished with a sprinkle of raisins and a drizzle of condensed milk. Place the plate on a rustic wooden table to create a sophisticated and luxurious feel. Utilize the rule of thirds by positioning the dish on one of the intersecting points for a visually appealing composition.\n
2. \"A bowl of Gourmet Egg Custard\": Present a delicately baked egg custard in a bowl, topped with a dusting of gelatin powder for an added touch of elegance. Include a side of boxed raisins and a decorative spoon to enhance the composition. Utilize leading lines with the handle of the spoon to draw the viewer's eye to the creamy custard.\n
3. \"A plate of Refined Cheese Omelette\": Capture a perfectly cooked cheese omelette on a stylish plate, with melted cheese oozing out enticingly. Pair the omelette with a side of yogurt or pudding cups for a contrast in textures. Pay attention to the details, such as the cheese slices elegantly layered on the omelette and the steam rising from the piping hot dish.\n
4. \"A bowl of Luxurious Savory Oatmeal\": Present a savory oatmeal dish in a bowl, garnished with a swirl of cooking oil for a touch of sophistication. Include a side of beverage cans and a sprinkle of boxed raisins for a pop of color. Experiment with different angles to capture the steam rising from the hearty oatmeal, enhancing the overall visual appeal.";

$string = '1. "A plate of Elegant Oatmeal Soufflé": Showcase a beautifully plated oatmeal soufflé, garnished with a sprinkle of raisins and a drizzle of condensed milk. Place the plate on a rustic wooden table to create a sophisticated and luxurious feel. Utilize the rule of thirds by positioning the dish on one of the intersecting points for a visually appealing composition.

2. "A bowl of Gourmet Egg Custard": Present a delicately baked egg custard in a bowl, topped with a dusting of gelatin powder for an added touch of elegance. Include a side of boxed raisins and a decorative spoon to enhance the composition. Utilize leading lines with the handle of the spoon to draw the viewer\'s eye to the creamy custard.

3. "A plate of Refined Cheese Omelette": Capture a perfectly cooked cheese omelette on a stylish plate, with melted cheese oozing out enticingly. Pair the omelette with a side of yogurt or pudding cups for a contrast in textures. Pay attention to the details, such as the cheese slices elegantly layered on the omelette and the steam rising from the piping hot dish.

4. "A bowl of Luxurious Savory Oatmeal": Present a savory oatmeal dish in a bowl, garnished with a swirl of cooking oil for a touch of sophistication. Include a side of beverage cans and a sprinkle of boxed raisins for a pop of color. Experiment with different angles to capture the steam rising from the hearty oatmeal, enhancing the overall visual appeal.';

$array = openai__extract_prompts($string);

print_r($array);
var_dump($array);