<?php
logfile('Task4: init');

$client = OpenAI::client($open_ai_key);

$prompt = array_shift($dalle_prompts);

$dalle_image_url = null;

try {
    $response = $client->images()->create([
        'model' => 'dall-e-3',
        'prompt' => $prompt,
        'n' => 1,
        'size' => '1024x1024',
        'response_format' => 'url',
    ]);

} catch (Exception $e) {
    throw new OpenAIException('Dall-E create, ' . $e->getMessage());
}

try {
    foreach ($response->data as $data) {
        $dalle_image_url = $data->url;
    }
    $log[] = 'Dall-E image, completed.';

    $json_all = $response->toArray();
    $log['dalle-img1'] = json_encode($json_all);

} catch (Exception $e) {
    throw new OpenAIException('Dall-E extract, ' . $e->getMessage());
}

logfile('Task4: openai__guzzleDownloader');
try {

    $data = openai__guzzleDownloader($dalle_image_url);
    if($data[0]=='200' and $data[2]=='png'){
        $filename = str_pad($upload_id, 5, '0', STR_PAD_LEFT) . '-' . str_pad($USER_ID, 5, '0', STR_PAD_LEFT) . '-1.png';
        file_put_contents(UPLOAD_PATH . '/' . $filename, $data[1]);
        //echo 'File saved: ' . UPLOAD_PATH . '/' . $filename . '<br>';
        $log[] = 'Dall-E image, downloaded.';
        createThumbnail(
            UPLOAD_PATH . '/' . $filename,
            UPLOAD_PATH . '/_thumbs/' . $filename,
            ['resize' => [150, 150]]
        );
        $log[] = 'Dall-E thumbnail, created.';

        $sql = new sqlbuddy;
        $sql->que('reciepe_image', $filename, 'string');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));

        $dalle_img1 = [
            'image' => $filename,
            'thumbnail' => '/_thumbs/' . $filename,
            'path' => UPLOAD_PATH . '/' . $filename,
            'thumb_path' => UPLOAD_PATH . '/_thumbs/' . $filename,
            'src' => UPLOAD_URI . '/' . $filename,
            'thumb_src' => UPLOAD_URI . '/_thumbs/' . $filename,
        ];

    } else {
        throw new OpenAIException('Dall-E download error, http_status: ' . $data[0] . ', ' . $e->getMessage());
    }

} catch (Exception $e) {
    throw new OpenAIException('Dall-E download error, ' . $e->getMessage());
}
