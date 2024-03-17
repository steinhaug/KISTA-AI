<?php
class ReplicateAPIException extends Exception { }
class RepliImage extends Exception { }

function updateUploadFile($reid, $status, $log){
    global $mysqli, $kista_dp;
    $sql = new sqlbuddy;
    $sql->que('status', $status, 'string');
    $sql->que('log', json_encode($log), 'text');
    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));
}

$error = null;
$reid = (int) $_SESSION['task'][$curentTaskID]['reid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `reid`=" . $reid . " AND `user_id`=" . $USER_ID);
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

            require AJAX_FOLDER_PATH . '/replicate/task01-pushInferences.php';
            updateUploadFile($reid, 'task1', $log);

            $sql = new sqlbuddy;
            $sql->que('status', 'complete', 'string');
            $sql->que('log', json_encode($log), 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));

            #unset($_SESSION['task'][$curentTaskID]);
            echo json_encode(['status'=>'complete','progress'=>100,'message'=>'All tasks completed.']); exit;


        } catch (RepliImage $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'complete', 'string');
            $sql->que('error', 'Replicate error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));
            echo json_encode(['status'=>'complete','progress'=>100,'error'=>'No fridge']); exit;
        } catch (ReplicateAPIException $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', 'Replicate error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));
            #unset($_SESSION['task'][$curentTaskID]);
            echo json_encode(['status'=>'failed','progress'=>100,'error'=>'Task returned an error and was aborted.']); exit;
            var_dump($success);
        } catch (Exception $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));
            #unset($_SESSION['task'][$curentTaskID]);
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
            case 'complete': $progress = 100; unset($_SESSION['task'][$curentTaskID]); break;
            case 'error': $progress = 100; unset($_SESSION['task'][$curentTaskID]); break;
            case 'failed': $progress = 100; unset($_SESSION['task'][$curentTaskID]); break;
        }
        echo json_encode(['status'=>$item['status'], 'progress'=>$progress]);
        exit;
    }

} else {
    $error = 'An APP error has occured. Task ' . (int) $reid . ' does not exist.';
    $_SESSION['error_msg'] = $error;
    unset($_SESSION['task'][$curentTaskID]);
    http_response_code(200);
    echo json_encode(['error'=>$error]);
    exit;
}

