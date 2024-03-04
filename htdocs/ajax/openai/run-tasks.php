<?php
class OpenAIException extends Exception {};

$upload_id = (int) $_SESSION['task']['aiid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
if ($res->num_rows) {
    try {

        $log = [];

        $upload_id = (int) $_SESSION['task']['aiid'];
        $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
        if (!$res->num_rows) {
            throw new Exception('Division by zero.');
        }
        $item = $res->fetch_assoc();

        if (!($item['extension'] == 'png' or $item['extension'] == 'jpg')) {
            throw new Exception('Image format not supported');
        }

        $error  = false;
        $imgIn  = UPLOAD_PATH . DIRECTORY_SEPARATOR . $item['filename'];
        $imgOut = UPLOAD_PATH . DIRECTORY_SEPARATOR . '_thumbs' . DIRECTORY_SEPARATOR . basename($item['filename'], "." . $item['extension']) . '.jpg';

        createThumbnail(
            $imgIn,
            $imgOut,
            ['resize' => [150, 150]]
        );
        $log[] = 'Thumbnail, created.';

        $sql = new sqlbuddy;
        $sql->que('thumbnail', '_thumbs' . DIRECTORY_SEPARATOR . basename($item['filename'], "." . $item['extension']) . '.jpg', 'string');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
        if (!$success) {
            throw new Exception('Thumbnail creation error');
        }
        $log[] = 'Thumbnail, DB updated.';




        $client = OpenAI::client($open_ai_key);
        $base64Image = imageToBase64($imgIn);
        $promptQue = [];
        $promptQue[] = [
            'type' => 'image_url',
            'image_url' => ['url' => $base64Image]
        ];
        $promptQue[] = [
            'type' => 'text',
            'text' => 'Your job is to detect as many different groceries / seperate items currently inside of the refrigerator as possible. If you find more items of the same type try to estimate pcs / quanta and return your findings as an item list with quantities.'
        ];
        $messagesArray = [];
        $messagesArray[] = [
            'role' => 'user',
            'content' => $promptQue
        ];
        $settings['model'] = 'gpt-4-vision-preview';
        $settings['messages'] = $messagesArray;
        $settings['max_tokens'] = 1200;
        try {
            $response = $client->chat()->create($settings);
            $additionalQuestion = [
                'role' => 'user',
                'content' => [['type' => 'text', 'text' => 'If there is a refridgerator visible in the image answer answer one word only YES, if no refridgerator is visible answer one word only NO.']]
            ];
            $settings['messages'][] = $additionalQuestion;
            $response2 = $client->chat()->create($settings);
        } catch (Exception $e) {
            throw new OpenAIException($e->getMessage());
        }

        $chatgpt_result1 = '';
        $chatgpt_result2 = '';

        foreach ($response->choices as $result) {
            $chatgpt_result1 = $result->message->content;
        }
        $json_all = $response->toArray();
        $meta = $response->meta();
        $json_meta = $meta->toArray();
        $log[] = 'ChatGPT Vision:';
        $log['vision_m1'] = json_encode($json_meta);
        $log['vision_q1'] = json_encode($json_all);

        foreach ($response2->choices as $result) {
            $chatgpt_result2 = $result->message->content;
        }
        $json_all = $response2->toArray();
        $meta = $response2->meta();
        $json_meta = $meta->toArray();
        $log['vision_m2'] = json_encode($json_meta);
        $log['vision_q2'] = json_encode($json_all);

        if( $chatgpt_result2 == 'NO' ){
            throw new OpenAIException('Missing refridgerator');
        }

        $sql = new sqlbuddy;
        $sql->que('status', 'complete', 'string');
        $sql->que('log', json_encode($log), 'text');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));

    } catch (OpenAIException $e) {
        $error = $e->getMessage();
        $sql = new sqlbuddy;
        $sql->que('status', 'error', 'string');
        $sql->que('error', 'OpenAI error: ' . $error, 'text');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
    } catch (Exception $e) {
        $error = $e->getMessage();
        $sql = new sqlbuddy;
        $sql->que('status', 'error', 'string');
        $sql->que('error', $error, 'string');
        $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
    }

} else {
    $_SESSION['error_msg'] = 'An APP error has occured. Task ' . (int) $upload_id . ' does not exist.';
}

echo 'Done!';
echo htmlentities($chatgpt_result1);
echo '<hr>';
echo htmlentities($chatgpt_result2);
krumo($response);
krumo($response2);

