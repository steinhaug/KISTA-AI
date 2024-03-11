<?php
logfile('Task10: init');

$prompt__get_reciepe = <<<EOF
TASK

Put together a dish based on the available ingredients below.

CONSIDERATIONS

- You do not have to use all of the ingredients. Please provide a recipe for one great dish, preferably for lunch or dinner.
- Please use the metric system, not the American measurement system.
- The name of the dish must be in English, and you must provide an estimation of calories, the country/region of origin of the recipe, and the duration of preparation.
- Optional ingredients are acceptable and can be suggested.

LIST OF AVAILABLE INGREDIENTS

{$list_of_ingredients}
EOF;

$completion_reciepe = promptChatGPT3($prompt__get_reciepe);

$prompt__get_dalle_prompts = <<<EOF
# QUESTION

Based on the reciepe, come up with 4 different prompt ideas that I can give DALL-E to imagine the reciepe. This is food photography, and your goal is to come up with a prompt that will showcase the dish, making the viewer want to eat it.

# GUIDELINES

**STYLING THE SHOT**
- In food photography, the styling of the shot is as important as the actual food. Before you start shooting, think about the story you want to tell with your photo. What mood or feeling do you want to convey? Is your dish meant to be elegant and sophisticated or casual and approachable?
- When styling the shot, consider the colors and textures of the food. Also, take into account the props you want to include. A simple background, like a wooden table or a white plate, can help make the food stand out.

**COMPOSITION**
- Composition is the arrangement of the elements in the photo. In food photography, you can create an eye-catching shot using a few composition techniques. The rule of thirds is a common technique where you divide the photo into thirds horizontally and vertically, creating a grid of nine equal parts. Then you place the food on one of the intersecting points.
- Another composition technique is leading lines. It is where you use lines like utensils or the edge of a plate to draw the viewer’s eye to the food.

**PAY ATTENTION TO THE DETAILS**
- In food photography, even the small details make a huge difference. Therefore, pay attention to all the tiny things, like how the sauce drizzles over the dish or the steam rising from a hot cup of coffee. All these small details can help bring the image to life.

# PROMPT SYNTAX

- Start each prompt with wither the words "A plate of " or "A bowl of " depending on the reciepe.

# RECIEPE

{$completion_reciepe}
EOF;

logfile('Task10: prompt__get_dalle_prompts');
$completion_prompts = promptChatGPT3($prompt__get_dalle_prompts);
$dalle_prompts = openai__extract_the_prompts($completion_prompts);

$json_pretty_encoded_prompts = json_encode($dalle_prompts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$prompt__get_dalle_short_prompts = <<<EOF
QUESTION

Rewrite the JSON prompts using fewer words. Make sure your answer is a numerated list.

PROMPTS

{ {$json_pretty_encoded_prompts} }
EOF;

logfile('Task10: prompt__get_dalle_short_prompts');
try {
    $completion_short_prompts = promptChatGPT3($prompt__get_dalle_short_prompts);
    $dalle_short_prompts = openai__extract_the_prompts($completion_short_prompts);
} catch (Exception $e) {
    $dalle_short_prompts = [];
}

$reciepe_id = saveReciepe([
    'reciepe'=>$completion_reciepe,
    'dalle_prompts'=>$dalle_prompts,
    'dalle_short_prompts'=>$dalle_short_prompts,
    'completion_short_prompts' => $completion_short_prompts
]);

logfile('Task10: promptDalle');
if( !is_null($dalle_short_prompts) and count($dalle_short_prompts) ){
    [$image, $thumbnail] = promptDalle(array_shift($dalle_short_prompts), 1, false);
} else {
    [$image, $thumbnail] = promptDalle(array_shift($dalle_prompts), 1, false);
}

updateReciepe($reciepe_id, [
    'image'     => $image,
    'thumbnail' => $thumbnail
]);
