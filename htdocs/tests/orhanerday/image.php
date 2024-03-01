<h1>Generate Image</h1>
<form action="image.php" method="post" enctype="multipart/form-data">
    Generate Image With Given Input: <br>
    <label for="prompt">Prompt</label>
    <input type="text" name="prompt" id="prompt">
    <br>
    <input type="submit" value="Generate Image" name="submit">
</form>
<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';

use Orhanerday\OpenAi\OpenAi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (ob_get_contents()) ob_end_clean();
    $open_ai = new OpenAi($open_ai_key);

    $result = $open_ai->image([
        "prompt" => $_POST["prompt"],
        "n" => 1,
        "size" => "256x256",
    ]);
    echo $result;

    // Output the image
    $data = json_decode($result, true);

    if( isset($data['data'][0]['url']) ){
        $url = $data['data'][0]['url'];
        echo "\n" . '<br><img src="' . $url . '" alt="" width="256" height="256">';
    }

}
