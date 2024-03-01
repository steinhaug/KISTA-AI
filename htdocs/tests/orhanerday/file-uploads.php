<form action="file-uploads.php" method="post" enctype="multipart/form-data">
    Select file to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload File" name="submit">
</form>
<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';

use Orhanerday\OpenAi\OpenAi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    ob_clean();
    $open_ai = new OpenAi($open_ai_key);
    $tmp_file = $_FILES['fileToUpload']['tmp_name'];
    $file_name = basename($_FILES['fileToUpload']['name']);
    $c_file = curl_file_create($tmp_file, $_FILES['fileToUpload']['type'], $file_name);

    echo "[";
    echo $open_ai->uploadFile(
        [
            "purpose" => "answers",
            "file" => $c_file,
        ]
    );
    echo ",";
    echo $open_ai->listFiles();
    echo "]";

}


// ref: https://orhanerday.gitbook.io/openai-php-api-1/#answers