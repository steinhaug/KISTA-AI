<?php
class OpenAIException extends Exception {};

$error = null;
$upload_id = (int) $_SESSION['task']['aiid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
if ($res->num_rows) {
 
    $log = [];
    $item = $res->fetch_assoc();

    if( $item['status'] == 'start' ){

        try {

            if (!($item['extension'] == 'png' or $item['extension'] == 'jpg')) {
                throw new Exception('Image format not supported');
            }

            require AJAX_FOLDER_PATH . '/openai/task01-createThumbnail.php';
            $mysqli->query("UPDATE `" . $kista_dp . "uploaded_files` SET `status`='task1' WHERE `upload_id`=" . $upload_id);
            require AJAX_FOLDER_PATH . '/openai/task02-OpenAIVision.php';
            $mysqli->query("UPDATE `" . $kista_dp . "uploaded_files` SET `status`='task2' WHERE `upload_id`=" . $upload_id);
            require AJAX_FOLDER_PATH . '/openai/task03-OpenAIChatYouAreChef.php';
            $mysqli->query("UPDATE `" . $kista_dp . "uploaded_files` SET `status`='task3' WHERE `upload_id`=" . $upload_id);
            require AJAX_FOLDER_PATH . '/openai/task04-OpenAIDallE.php';
            $mysqli->query("UPDATE `" . $kista_dp . "uploaded_files` SET `status`='task4' WHERE `upload_id`=" . $upload_id);

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
            var_dump($success);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "uploaded_files", 'upload_id=' . $upload_id));
            var_dump($success);
        }

    } else {
        echo $item['status'];
        exit;
    }

} else {
    $error = 'An APP error has occured. Task ' . (int) $upload_id . ' does not exist.';
    $_SESSION['error_msg'] = $error;
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