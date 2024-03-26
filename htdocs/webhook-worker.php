<?php
use Intervention\Image\ImageManagerStatic as Image;
use BenBjurstrom\Replicate\Replicate;

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
header("Content-Type: text/plain");


if( ($items = $mysqli->prepared_query("SELECT * FROM `" . $kista_dp . "replicate__hooks` WHERE `processed` = ?", 'i', [0])) === [] )
    die('<hooks />');

define('REPLICATE_INFERENCE_FOLDER', UPLOAD_PATH . '/ri');

echo '<hooks>';

foreach( $items as $item ){

    echo '<' . $item['whid'] . '>';

    $data = Json_decode($item['json'],true);
    if(!empty($data['error'])){
        hookUpd_processError($item['whid'], $item['replicate_id'], $data['error'] );
        echo 'error</' . $item['whid'] . '>';
        continue;
    }


    $api = new Replicate(
        apiToken: $replicate_api_token,
    );
    $data = $api->predictions()->get($item['replicate_id']);

    if(!empty($data->error)){
        hookUpd_processError($item['whid'], $item['replicate_id'], $data->error );
        echo 'error</' . $item['whid'] . '>';
        continue;
    }


    if ($data->status == 'succeeded') {
        if (is_array($data->output) or is_object($data->output)) {
            foreach ($data->output as $url) {

                $image_filename = basename(dirname($url)) . '.' . get_extension($url);
                $download_savePath = REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $image_filename;
                logfile('Download filename: ' . $image_filename);

                $data = openai__guzzleDownloader($url);
                if ($data[0]=='200' and $data[2]=='png') {
                    file_put_contents($download_savePath, $data[1]);
                    $size = filesize($download_savePath);

                    $sql = new sqlbuddy;
                    $sql->que('deleted', 0, 'int');
                    $sql->que('uuid', generateUuid4(), 'string');
                    $sql->que('reid', $item['reid'], 'int');
                    $sql->que('created', 'NOW()', 'raw');
                    $sql->que('url', $url, 'string');
                    $sql->que('filename', $image_filename, 'string');
                    $sql->que('extension', get_extension($url), 'string');
                    $sql->que('filesize', $size, 'int');
                    $sql->que('thumbnail', '', 'string');
                    $sql->que('status', 'done', 'string');
                    $mysqli->query($sql->build('insert', $kista_dp . "replicate__images"));
                    $image_id = $mysqli->insert_id;
                    logfile('Created replicate images...');

                    createThumbnail(
                        $download_savePath,
                        REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_m.png',
                        ['resize' => [512, 768]]
                    );
                    logfile('Created replicate thumb 1/3: ' . get_name_only($image_filename) . '_m.png');

                    createThumbnail(
                        REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_m.png',
                        REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_s.jpg',
                        ['resize' => [150, 224]]
                    );
                    logfile('Created replicate thumb 2/3: ' . get_name_only($image_filename) . '_s.jpg');

                    convertImage(
                        REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_m.png',
                        REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_m.jpg'
                    );
                    logfile('Created replicate thumb 3/3: ' . get_name_only($image_filename) . '_m.jpg');
                    unlink( REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . get_name_only($image_filename) . '_m.png' );

                    $sql = new sqlbuddy;
                    $sql->que('thumbnail', 'm, s', 'string');
                    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__images", 'image_id=' . $image_id));
                    logfile('Completed.');

                }
            }
        }
    }

    hookUpd_process($item['whid']);
    echo '</' . $item['whid'] . '>';

}
echo '</hooks>';
