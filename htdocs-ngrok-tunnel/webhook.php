<?php
use BenBjurstrom\Replicate\Replicate;


require './vendor/autoload.php';

define('APPDATA_PATH', dirname(dirname(__FILE__)) . '/htdocs/inc_appdata');
require 'func.inc.php';

define('REPLICATE_INFERENCE_FOLDER', dirname(dirname(__FILE__)) . '/htdocs/uploaded_files/ri/');


ob_start();

# echo "<h1>Environment Debug Script</h1>";

// Function to safely print arrays with htmlspecialchars
function safePrintArray($array) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            echo htmlspecialchars($key) . ' => Array:<br>';
            echo '<ul>';
            safePrintArray($value);
            echo '</ul>';
        } else {
            echo htmlspecialchars($key) . ' => ' . htmlspecialchars($value) . '<br>';
        }
    }
}

/*
echo "<h2>Superglobals</h2>";
echo "<h3>\$_SERVER</h3>";
echo "<pre>";
safePrintArray($_SERVER);
echo "</pre>";
*/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if( isset($_GET['go_replicate_id']) and !empty($_GET['go_replicate_id']) ){

    $jsonData = [true];
    $replicate_id = (string) trim($_GET['go_replicate_id']);

} else {
    $rawData = file_get_contents("php://input");
    $jsonData = json_decode($rawData, true);
    $replicate_id = basename($jsonData['urls']['get']);
}

if ($jsonData) {
    if( ($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`replicate_id`=?", 's', [$replicate_id], true)) !== null ){
        echo '<located/>';

       $api = new Replicate(
            apiToken: $replicate_api_token,
        );
        $data = $api->predictions()->get($replicate_id);
        if($data->status == 'succeeded'){
            if(is_array($data->output) or is_object($data->output)){
                foreach($data->output as $url){

                    $image_filename = basename(dirname($url)) . '.' . get_extension($url);
                    #echo 'Image ' . $image_filename . ' ready for download.<br>' . "\n";

                    if (!file_exists(REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $image_filename)) {
                        $data = openai__guzzleDownloader($url);
                        if ($data[0]=='200' and $data[2]=='png') {

                            $pngfile_path = REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $image_filename;
                            file_put_contents($pngfile_path, $data[1]);
                            $size = filesize($pngfile_path);

                            $sql = new sqlbuddy;
                            $sql->que('uuid', generateUuid4(),'string');
                            $sql->que('reid', $item['reid'],'int');
                            $sql->que('created', 'NOW()','raw');
                            $sql->que('url', $url,'string');
                            $sql->que('filename', $image_filename,'string');
                            $sql->que('extension', get_extension($url),'string');
                            $sql->que('filesize', $size,'int');
                            $sql->que('thumbnail', '','string');
                            $sql->que('status', 'done','string');
                            $mysqli->query( $sql->build('insert', $kista_dp . "replicate__images") );
                            $image_id = $mysqli->insert_id;
                            echo 'Image ' . $image_id . ' created.<br>' . "\n";

                            createThumbnail(
                                $pngfile_path,
                                REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.png',
                                ['resize' => [384, 512]]
                            );
                            createThumbnail(
                                REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.png',
                                REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_s.jpg',
                                ['resize' => [150, 224]]
                            );

                            $sql = new sqlbuddy;
                            $sql->que('thumbnail', 'm, s','string');
                            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__images", 'image_id=' . $image_id));

                            //echo '<img src="/downloads/' . $filename . '">';
                        }
                    } else {
                        //echo '<img src="/downloads/' . $filename . '">';
                    }

                    // Release processing
                    $sql = new sqlbuddy;
                    $sql->que('status', 'complete','string');
                    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . (int) $item['reid']));

                }
            } else {
                echo '<Expired/>';
            }

        }

    }

    #echo "Data received:\n";
    #print_r($jsonData);
    echo '<OK/>';

} else {
    echo '<BUGGER/>';
}

// Debugging HTTP Request
/*
echo "<h2>HTTP Request Details</h2>";
echo "<strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "<strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<strong>Query String:</strong> " . $_SERVER['QUERY_STRING'] . "<br>";
echo "<strong>Remote Address:</strong> " . $_SERVER['REMOTE_ADDR'] . "<br>";
*/
$page = ob_get_contents();
ob_end_clean();

echo $page; // 'OK';
file_put_contents('webhook.log', $page . "\n", FILE_APPEND);