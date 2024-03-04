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

$string = '1. A plate of "Elegant Rosé Braised Beef Short Ribs" - Showcase the dish on a sleek black plate with a minimalist white tablecloth background. - Use soft, natural lighting to highlight the juicy beef short ribs glazed with a luxurious rosé wine reduction. - Garnish with fresh herbs and edible flowers for a pop of color and texture. - Incorporate a fine dining setting with silver cutlery and a glass of rosé wine in the background to elevate the overall presentation. 2. A bowl of "Velvety Rosé Infused Lobster Bisque" - Present the dish in a classy, oversized soup bowl with a delicate swirl of cream on top. - Arrange a few pieces of succulent lobster meat at the center of the bisque as a focal point. - Place a rustic bread roll on the side with a small jar of rosé wine as a beverage pairing. - Capture the steam rising from the bisque to add an element of warmth and comfort to the image. 3. A plate of "Rosé Wine Glazed Duck Breast with Cherry Compote" - Plate the perfectly seared duck breast slices on a bed of vibrant cherry compote for a burst of color. - Add a drizzle of the rosé wine glaze over the duck to enhance the glossy appearance. - Include a scattering of toasted nuts and microgreens for texture and freshness. - Set the plate on a wooden backdrop with a vintage silver fork to create a contrasting yet harmonious composition. 4. A plate of "Rosé Poached Pear Salad with Goat Cheese and Candied Walnuts" - Arrange slices of delicately poached pears on a bed of mixed greens with crumbled goat cheese and candied walnuts. - Drizzle a light rosé vinaigrette over the salad to tie all the flavors together. - Incorporate a sprinkle of pomegranate arils for a pop of color and added sweetness. - Opt for a rustic wooden serving board and vintage silverware to bring a touch of sophistication to the shot.';



$dalle_prompts = openai__extract_the_prompts($string);
if (count($dalle_prompts) != 4) {
    echo 'Error: ' . count($dalle_prompts) . ' != 4';
}


print_r($dalle_prompts);
var_dump($dalle_prompts);



