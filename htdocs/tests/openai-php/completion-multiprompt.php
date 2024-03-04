<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);



$settings = [
    'model' => 'gpt-4-1106-preview', // gpt-3.5-turbo
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!'],
    ],
];

try {
    $response1 = $client->chat()->create($settings);

    $additionalQuestion = [
        'role' => 'user',
        'content' => [['type' => 'text', 'text' => 'What day is it today?']]
    ];
    $settings['messages'][] = $additionalQuestion;
    $response2 = $client->chat()->create($settings);



    $completion1 = '';
    foreach ($response1->choices as $result) {
        $completion1 = $result->message->content;
    }
    $completion2 = '';
    foreach ($response2->choices as $result) {
        $completion2 = $result->message->content;
    }

    // debugging 

    krumo($response1);
    krumo($response2);

    $json_all = $response1->toArray();
    $meta = $response1->meta();
    $json_meta = $meta->toArray();
    echo '<fieldset><legend>Completion1</legend>';
    echo '<p style="color:blue;">' . $completion1 . '</p>';
    echo htmlentities( json_encode($json_meta) );
    echo '<br>' . "\n";
    echo htmlentities( json_encode($json_all) );
    echo '</fieldset>' . "\n";

    $json_all = $response2->toArray();
    $meta = $response2->meta();
    $json_meta = $meta->toArray();
    echo '<fieldset><legend>Completion2</legend>';
    echo '<p style="color:blue;">' . $completion2 . '</p>';
    echo htmlentities( json_encode($json_meta) );
    echo '<br>' . "\n";
    echo htmlentities( json_encode($json_all) );
    echo '</fieldset>' . "\n";

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
} finally {
    echo '<hr>OpenAI API done.<br>' . "\n";
}