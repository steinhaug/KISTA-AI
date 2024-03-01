<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';
$client = OpenAI::client($open_ai_key);

$speaker = 'shimmer'; // alloy (dame), echo (mann), fable (lys dame), onyx (mann rolig), nova (dame), shimmer (dame)

$mp3file = $client->audio()->speech([
    'model' => 'tts-1',
    'input' => 'I wonder what my refridgerator lets me make for dinner today!',
    'voice' => $speaker,
]);

function save_mp3_to_file($mp3, $path) {
    // Write the MP3 data to the file
    $result = file_put_contents($path, $mp3);
    
    // Check if writing to file was successful
    if ($result !== false) {
        echo "MP3 file saved successfully at $path";
        return true;
    } else {
        echo "Error saving MP3 file";
        return false;
    }
}


#save_mp3_to_file($mp3file, "../../../assets/mp3/openai-speech-alloy.mp3");
save_mp3_to_file($mp3file, "../temp-openai-speech-" . $speaker . ".mp3");

echo "
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Sound sample</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='../../css/main.css'>
    <script src='../../js/main.js'></script>
</head>
<body>

<audio controls>
  <source src='../temp-openai-speech-" . $speaker . ".mp3' type='audio/mpeg'>
  Your browser does not support the audio element.
</audio>

</body>
</html>
";

