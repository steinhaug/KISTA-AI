<?php

$speaker = 'shimmer'; // alloy (dame), echo (mann), fable (lys dame), onyx (mann rolig), nova (dame), shimmer (dame)

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

alloy <audio controls><source src='../temp-openai-speech-alloy.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 
echo <audio controls><source src='../temp-openai-speech-echo.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 
fable <audio controls><source src='../temp-openai-speech-fable.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 
onyx <audio controls><source src='../temp-openai-speech-onyx.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 
nova <audio controls><source src='../temp-openai-speech-nova.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 
shimmer <audio controls><source src='../temp-openai-speech-shimmer.mp3' type='audio/mpeg'> Your browser does not support the audio element.</audio> <br> 

</body>
</html>
";

