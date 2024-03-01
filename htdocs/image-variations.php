<h1>Variations</h1>
<form action="/image-variations.php" method="post" enctype="multipart/form-data">
    Select PNG file to upload (less than 4mb): <br>
    <label for="image">Image</label>
    <input type="file" name="image" id="image">
    <br>
    <input type="submit" value="Upload File" name="submit">
</form>
<?php
require '../vendor/autoload.php';
require '../credentials.php';

use Orhanerday\OpenAi\OpenAi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (ob_get_contents()) ob_end_clean();
    $open_ai = new OpenAi($open_ai_key);
    $tmp_file = $_FILES['image']['tmp_name'];
    $file_name = basename($_FILES['image']['name']);
    $image = curl_file_create($tmp_file, $_FILES['image']['type'], $file_name);

    $result = $open_ai->createImageVariation([
        "image" => $image,
        "n" => 2,
        "size" => "256x256",
    ]);
    echo $result;

    if (isset($data['error'])) {
        echo $data['error']['code'];
        echo '<br>';
        echo $data['error']['message'];
    } else {
        // Parse and output images
        $data = json_decode($result, true);
        $imgs_html = '';
        foreach( $data['data'] as $item ){
            echo $item['url'] . "<br>\n";
            $imgs_html .= '<img src="' . $item['url'] . '">';
        }
        echo '<br><br><div style="">';
        echo $imgs_html;
        echo '</div>';
    }

}