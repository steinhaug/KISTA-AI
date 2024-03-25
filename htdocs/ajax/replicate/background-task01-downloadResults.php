<?php
use BenBjurstrom\Replicate\Replicate;

logfile('Background task01 started.');

if (($data = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__hooks` `hooks` WHERE `hooks`.`replicate_id`=?", 's', [$item['replicate_id']], true)) !== null) {
    $hook = json_decode($data['json'],true);
    processHook($data['whid']);
    logfile('- hook found and processed.');
} else {
    logfile('- hook not found: ' . $item['replicate_id']);
}

define('REPLICATE_INFERENCE_FOLDER', UPLOAD_PATH . '/ri');

try {

    logfile('Replicate API check');
    $api = new Replicate(
        apiToken: $replicate_api_token,
    );
    $data = $api->predictions()->get($item['replicate_id']);

    logfile('- api returned status: ' . $data->status);

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

                    $sql = new sqlbuddy;
                    $sql->que('thumbnail', 'm, s', 'string');
                    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__images", 'image_id=' . $image_id));
                    logfile('Completed.');

                }
            }
        }
    }

    logfile('- Updating complete');
    $status = updateStatus__replicate($item['reid'], ['status'=>'complete']);

} catch (Exception $e) {
    logfile('- Updating error');
    $status = updateStatus__replicate($item['reid'], ['status'=>'error', 'error'=>$e->getMessage()]);
}

logfile('- EXIT');

// Whatever happened we are completed now.
exit;