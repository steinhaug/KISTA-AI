<?php
logfile('Task3: init');

$prompt1 = <<<EOF
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

$prompt2 = <<<EOF
Based on the reciepe above, come up with 4 different prompt ideas that I can give DALL-E to imagine the reciepe. This is food photography, and your goal is to come up with a prompt that will
showcase the dish in a luxurious way, making the viewer want to eat it. Here are some guidelines to have in mind when crafting the prompts:

STYLING THE SHOT
In food photography, the styling of the shot is as important as the actual food. Before you start shooting, think about the story you want to tell with your photo. What mood or feeling do you want to convey? Is your dish meant to be elegant and sophisticated or casual and approachable?
When styling the shot, consider the colors and textures of the food. Also, take into account the props you want to include. A simple background, like a wooden table or a white plate, can help make the food stand out.

COMPOSITION
Composition is the arrangement of the elements in the photo. In food photography, you can create an eye-catching shot using a few composition techniques. The rule of thirds is a common technique where you divide the photo into thirds horizontally and vertically, creating a grid of nine equal parts. Then you place the food on one of the intersecting points.
Another composition technique is leading lines. It is where you use lines like utensils or the edge of a plate to draw the viewer’s eye to the food.

PAY ATTENTION TO THE DETAILS
In food photography, even the small details make a huge difference. Therefore, pay attention to all the tiny things, like how the sauce drizzles over the dish or the steam rising from a hot cup of coffee. All these small details can help bring the image to life.

YOU ARE READY

Start each prompt with wither the words "A plate of " or "A bowl of " depending on the reciepe.
EOF;


$client = OpenAI::client($open_ai_key);
$settings = [
    'model' => 'gpt-4-1106-preview', // 'gpt-4-1106-preview', // 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => $prompt1],
    ],
];

try {
    $response1 = $client->chat()->create($settings);
} catch (Exception $e) {
    throw new OpenAIException('cp1, ' . $e->getMessage());
}

try {
    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => $prompt2]]
    ];
    $settings['messages'][] = $additionalQuestion;
    $response2 = $client->chat()->create($settings);
} catch (Exception $e) {
    throw new OpenAIException('cp2, ' . $e->getMessage());
}

try {
    $completion1 = '';
    foreach ($response1->choices as $result) {
        $completion1 = $result->message->content;
    }
    $completion2 = '';
    foreach ($response2->choices as $result) {
        $completion2 = $result->message->content;
    }
} catch (Exception $e) {
    throw new OpenAIException('cp1-2 extract, ' . $e->getMessage());
}

$sql = new sqlbuddy;
$sql->que('reciepe', $completion1, 'string');
$success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));

$dalle_prompts = openai__extract_the_prompts($completion2);
if( count($dalle_prompts) != 4 ){
    $log['completion1'] = $completion1;
    $log['completion2'] = $completion2;
    $sql = new sqlbuddy;
    $sql->que('log', json_encode($log, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE), 'text');
    $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
    throw new OpenAIException('dalle_prompts extraction error, ' . count($dalle_prompts) . ' != 4');
}
$log['dalle_prompts'] = json_encode($dalle_prompts);

try {
    $json_all = $response1->toArray();
    $meta = $response1->meta();
    $json_meta = $meta->toArray();
    $log[] = 'ChatGPT Chat:';
    $log['chat_m1'] = json_encode($json_meta);
    $log['chat_q1'] = json_encode($json_all);

    $json_all = $response2->toArray();
    $meta = $response2->meta();
    $json_meta = $meta->toArray();
    $log['chat_m2'] = json_encode($json_meta);
    $log['chat_q2'] = json_encode($json_all);

} catch (Exception $e) {
    throw new OpenAIException('cp1-2 log, ' . $e->getMessage());
}

