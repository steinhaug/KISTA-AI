<?php
use BenBjurstrom\Replicate\Replicate;

if(!defined('WEBROOT')) define('WEBROOT', dirname(dirname(__FILE__)) . '/htdocs');
if(!defined('APPDATA_PATH')) define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
if(!defined('UPLOAD_PATH')) define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once WEBROOT . '/func.inc.php';

// require_once 'func.login.php';
define('REPLICATE_INFERENCE_FOLDER', UPLOAD_PATH . '/ri');

/**
 * Ending the page.
 *
 */
function save_webhook_and_exit($end_message, $reason=''){

    logfile('HOOK ENDED: ' . $end_message);
    echo htmlentities($end_message);
    echo "\n\n" . REPLICATE_INFERENCE_FOLDER . "\n";

    $page = ob_get_contents();
    ob_end_clean();
    file_put_contents('webhook.log', $page . "\n", FILE_APPEND);
    //file_put_contents(dirname(WEBROOT) . '/logs/webhook.log', $page . "\n", FILE_APPEND);

    if(strlen($reason))
        file_put_contents('webhook.log', $reason . "\n", FILE_APPEND);

    exit;
}

$verbose = false;
$rawData = file_get_contents("php://input");
file_put_contents('webhook.log', 'Size: ' . strlen($rawData) . "\n" . $rawData . "\n", FILE_APPEND);



if( isset($_GET['go_replicate_id']) and !empty($_GET['go_replicate_id']) ){

    $verbose = true;
    $jsonData = [true];
    $replicate_id = (string) trim($_GET['go_replicate_id']);

} else {

    // Triggering the page will just stop it
    if( !strlen($rawData) ){
        save_webhook_and_exit('<rawStop/>');
    }

    $jsonData = json_decode($rawData, true);
    $replicate_id = $jsonData['id'];

    file_put_contents('webhook.log', '$replicate_id: ' . $replicate_id . "\n", FILE_APPEND);
}


    if( ($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`replicate_id`=?", 's', [$replicate_id], true)) !== null ){

        $api = new Replicate(
            apiToken: $replicate_api_token,
        );
        $data = $api->predictions()->get($replicate_id);

        #$json_packed_string = json_encode($data->error, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        #krumo($data); $page = ob_get_contents(); ob_end_clean(); echo $page; exit;

        /*
        if( !empty($rawData) ){
            file_put_contents('webhook.log', 'Saving $rawData.' . "\n", FILE_APPEND);
            $sql = new sqlbuddy;
            $sql->que('log', $rawData, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . (int) $item['reid']));
        }
        */

        file_put_contents('webhook.log', "\n" . 'Status: ' . $data->status . "\n", FILE_APPEND);
        if($data->status == 'succeeded'){

            if(is_array($data->output) or is_object($data->output)){
                foreach($data->output as $url){

                        file_put_contents('webhook.log', 'Output passed...' . "\n", FILE_APPEND);

                        $image_filename = basename(dirname($url)) . '.' . get_extension($url);
                        if($verbose) echo htmlentities('<url>' . $image_filename . '</url>');

                        $download_savePath = REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $image_filename;

                        if (file_exists($download_savePath)) {
                            unlink($download_savePath);
                        }

                        file_put_contents('webhook.log', 'next openai__guzzleDownloader()' . "\n", FILE_APPEND);

                        $data = openai__guzzleDownloader($url);
                        if ($data[0]=='200' and $data[2]=='png') {

                                file_put_contents($download_savePath, $data[1]);
                                $size = filesize($download_savePath);
                                if($verbose) echo htmlentities('<db/>');

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
                                if($verbose) echo htmlentities('<img id="' . $image_id . '"/>');

                                createThumbnail(
                                    $download_savePath,
                                    REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.png',
                                    ['resize' => [512, 768]]
                                );

                                createThumbnail(
                                    REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.png',
                                    REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_s.jpg',
                                    ['resize' => [150, 224]]
                                );

                                convertImage(REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.png',
                                            REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . pathinfo($url, PATHINFO_FILENAME) . '_m.jpg');

                                $sql = new sqlbuddy;
                                $sql->que('thumbnail', 'm, s','string');
                                $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__images", 'image_id=' . $image_id));
                                //echo '<img src="/downloads/' . $filename . '">';

                        }

                        // Release processing
                        $sql = new sqlbuddy;
                        $sql->que('status', 'complete','string');
                        //$sql->que('data', json_encode($img_arrays, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 'string');
                        $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . (int) $item['reid']));

                }

            } else {

                file_put_contents('webhook.log', 'We crashed...' . "\n", FILE_APPEND);

                if( is_null($data->output) ){
                    $sql = new sqlbuddy;
                    $sql->que('status', 'error','string');
                    $sql->que('error', 'AI-Server didnt reply, broken output. Try again.','string');
                    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . (int) $item['reid']));
                    save_webhook_and_exit('<broken/>', 'is_null($data->output)');
                } else {
                    save_webhook_and_exit('<Expired/>');
                }
            }

        }

    } else {
        #echo "Data received:\n";
        #print_r($jsonData);
        save_webhook_and_exit('<OK/>', 'DB Row not found, replicate_id: ' . $replicate_id);
    }




save_webhook_and_exit('<end/>');
