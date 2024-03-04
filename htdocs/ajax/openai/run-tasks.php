<?php


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


        $sql = new sqlbuddy;
        $sql->que('status', 'complete', 'string');
        $sql->que('log', json_encode($log), 'text');
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
