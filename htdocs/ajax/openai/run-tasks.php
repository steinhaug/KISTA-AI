<?php
class OpenAIException extends Exception { }
class SegWayImage extends Exception { }

function updateUploadFile($upload_id, $status, $log){
    global $mysqli, $kista_dp;
    $sql = new sqlbuddy;
    $sql->que('status', $status, 'string');
    $sql->que('log', json_encode($log), 'text');
    $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
}

$error = null;
$upload_id = (int) $_SESSION['task']['aiid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
if ($res->num_rows) {
 
    $log = [];
    $item = $res->fetch_assoc();

    if( $item['status'] == 'start' ){

        // Release the session file
        session_write_close();

        try {

            if (!($item['extension'] == 'png' or $item['extension'] == 'jpg')) {
                throw new Exception('Image format not supported');
            }

            require AJAX_FOLDER_PATH . '/openai/task01-createThumbnail.php';
            updateUploadFile($upload_id, 'task1', $log);

            require AJAX_FOLDER_PATH . '/openai/task02-OpenAIVision.php';
            updateUploadFile($upload_id, 'task2', $log);
            setUploadStatus($upload_id, 'task2', [
                'chatgpt_result1'=>$chatgpt_result1,
                'chatgpt_result2'=>$chatgpt_result2,
                'chatgpt_curated_list'=>$chatgpt_curated_list,
                'list_of_ingredients'=>$list_of_ingredients
            ]);

            require AJAX_FOLDER_PATH . '/openai/task03-OpenAIChatYouAreChef.php';
            updateUploadFile($upload_id, 'task3', $log);
            setUploadStatus($upload_id, 'task3', ['completion1'=>$completion1,'completion2'=>$completion2]);

            require AJAX_FOLDER_PATH . '/openai/task04-OpenAIDallE.php';
            updateUploadFile($upload_id, 'task4', $log);
            setUploadStatus($upload_id, 'task4', ['dalle_image_url'=>$dalle_image_url]);

            // Create a reciepe
            $reciepe_id = saveReciepe([
                'reciepe'=>$completion1,
                'dalle_prompts'=>$dalle_prompts
            ],$dalle_img1['image'],$dalle_img1['thumbnail']);

            // Create second reciepe
            require AJAX_FOLDER_PATH . '/openai/task10-OpenAICreateReciepe.php';
            setUploadStatus($upload_id, 'task10', [
                'completion_reciepe'=>$completion_reciepe,
                'completion_prompts'=>$completion_prompts,
                'completion_short_prompts'=>$completion_short_prompts
            ]);

            $sql = new sqlbuddy;
            $sql->que('status', 'complete', 'string');
            $sql->que('log', json_encode($log), 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));

            #unset($_SESSION['task']);
            echo json_encode(['status'=>'complete','progress'=>100,'message'=>'All tasks completed.']); exit;

        } catch (SegWayImage $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('reciepe', '<no_fridge />', 'string');
            $sql->que('status', 'complete', 'string');
            $sql->que('error', 'OpenAI error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            echo json_encode(['status'=>'complete','progress'=>100,'error'=>'No fridge']); exit;
        } catch (OpenAIException $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', 'OpenAI error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            #unset($_SESSION['task']);
            echo json_encode(['status'=>'failed','progress'=>100,'error'=>'Task returned an error and was aborted.']); exit;
            var_dump($success);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            #unset($_SESSION['task']);
            echo json_encode(['status'=>'failed','progress'=>100,'error'=>'Task returned an error and was aborted.']); exit;
            var_dump($success);
        }

    } else {
        http_response_code(200);
        switch ($item['status']) {
            case 'start': $progress = 10; break;
            case 'task1': $progress = 20; break;
            case 'task2': $progress = 40; break;
            case 'task3': $progress = 60; break;
            case 'task4': $progress = 80; break;
            case 'task10': $progress = 90; break;
            case 'complete': $progress = 100; unset($_SESSION['task']); break;
            case 'error': $progress = 100; unset($_SESSION['task']); break;
            case 'failed': $progress = 100; unset($_SESSION['task']); break;
        }
        echo json_encode(['status'=>$item['status'], 'progress'=>$progress]);
        exit;
    }

} else {
    $error = 'An APP error has occured. Task ' . (int) $upload_id . ' does not exist.';
    $_SESSION['error_msg'] = $error;
    unset($_SESSION['task']);
    http_response_code(200);
    echo json_encode(['error'=>$error]);
    exit;
}

echo '<hr><div style="text-align:center;">COMPLETE</div><hr>';

if( $error === null ){

    if( isset($dalle_img1) )
        var_dump($dalle_img1);

    if (isset($chatgpt_result1)) {
        echo '<fieldset><legend>chatgpt_result1</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result1 . '</p>';
        echo htmlentities($log['vision_m1']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q1']);
        echo '</fieldset>' . "\n";
        echo '<fieldset><legend>chatgpt_result2</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result2 . '</p>';
        echo htmlentities($log['vision_m2']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q2']);
        echo '</fieldset>' . "\n";
    }
    if (isset($completion1)) {
        echo '<fieldset><legend>completion1</legend>';
        echo '<p style="color:blue;">' . $completion1 . '</p>';
        echo htmlentities($log['chat_m1']);
        echo '<br>' . "\n";
        echo htmlentities($log['chat_q1']);
        echo '</fieldset>' . "\n";
        echo '<fieldset><legend>completion2</legend>';
        echo '<p style="color:blue;">' . $completion2 . '</p>';
        echo htmlentities($log['chat_m2']);
        echo '<br>' . "\n";
        echo htmlentities($log['chat_q2']);
        echo '</fieldset>' . "\n";
    }
    if (isset($chatgpt_result1)) {
        echo '<h4>$list_of_ingredients</h4>';
        echo '<pre>' . $list_of_ingredients . '</pre>';
    }
} else {

    echo '<h2>error</h2>';
    echo $error;

    if (isset($chatgpt_result1)) {
        echo '<h4>$list_of_ingredients</h4>';
        echo '<pre>' . $list_of_ingredients . '</pre>';
    }
    if (isset($chatgpt_result1)) {
        echo '<fieldset><legend>chatgpt_result1</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result1 . '</p>';
        echo htmlentities($log['vision_m1']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q1']);
        echo '</fieldset>' . "\n";
    }
    if (isset($chatgpt_result2)) {
        echo '<fieldset><legend>chatgpt_result2</legend>';
        echo '<p style="color:blue;">' . $chatgpt_result2 . '</p>';
        echo htmlentities($log['vision_m2']);
        echo '<br>' . "\n";
        echo htmlentities($log['vision_q2']);
        echo '</fieldset>' . "\n";
    }
    if (isset($completion1)) {
        echo '<fieldset><legend>completion1</legend>';
        echo '<p style="color:blue;">' . $completion1 . '</p>';
        echo htmlentities($log['chat_m1']);
        echo '<br>' . "\n";
        echo htmlentities($log['chat_q1']);
        echo '</fieldset>' . "\n";
    }
    if (isset($completion2)) {
        echo '<fieldset><legend>completion2</legend>';
        echo '<p style="color:blue;">' . $completion2 . '</p>';
        echo htmlentities($log['chat_m2']);
        echo '<br>' . "\n";
        echo htmlentities($log['chat_q2']);
        echo '</fieldset>' . "\n";
    }
}