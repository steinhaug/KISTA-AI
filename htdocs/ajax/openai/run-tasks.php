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

        require AJAX_FOLDER_PATH . '/openai/task01-createThumbnail.php';
        require AJAX_FOLDER_PATH . '/openai/task02-OpenAIVision.php';

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

