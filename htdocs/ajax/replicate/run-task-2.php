<?php
class ReplicateAPIException extends Exception { }
class RepliImage extends Exception { }


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

            try {
                require AJAX_FOLDER_PATH . '/replicate/task02-pushInferences.php';
            } catch (RepliImage $e) {
                throw new RepliImage('Image error: ' . $e->getMessage());
            } catch (ReplicateAPIException $e) {
                throw new ReplicateAPIException('Replicate API error: ' . $e->getMessage());
            } catch (Exception $e) {
                throw new Exception('Replicate API request error, ' . $e->getMessage());
            }

            $sql = new sqlbuddy;
            $sql->que('status', 'waiting', 'string');
            $sql->que('log', json_encode($log), 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));

            #unset($_SESSION['task'][$curentTaskID]);
            echo json_encode(['status'=>'waiting','progress'=>50,'message'=>'All tasks completed.']); exit;


        } catch (RepliImage $e) {
            $error = $e->getMessage();
            $sql = new sqlbuddy;
            $sql->que('status', 'error', 'string');
            $sql->que('error', 'Replicate error: ' . $error, 'text');
            $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));
            echo json_encode(['status'=>'complete','progress'=>100,'error'=>'Image problems']); exit;
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
            case 'waiting':  updateStatus__replicate($item['reid'], ['status'=>'waiting2']); $progress = 30; break;
            case 'waiting2': updateStatus__replicate($item['reid'], ['status'=>'waiting3']); $progress = 33; break;
            case 'waiting3': updateStatus__replicate($item['reid'], ['status'=>'waiting4']); $progress = 45; break;
            case 'waiting4': updateStatus__replicate($item['reid'], ['status'=>'waiting5']); $progress = 65; break;
            case 'waiting5': updateStatus__replicate($item['reid'], ['status'=>'waiting6']); $progress = 73; break;
            case 'waiting6': updateStatus__replicate($item['reid'], ['status'=>'waiting7']); $progress = 76; break;
            case 'waiting7': updateStatus__replicate($item['reid'], ['status'=>'waiting8']); $progress = 79; break;
            case 'waiting8': updateStatus__replicate($item['reid'], ['status'=>'waiting9']); $progress = 82; break;
            case 'waiting9': updateStatus__replicate($item['reid'], ['status'=>'waiting10']); $progress = 86; break;
            case 'waiting10': $progress = 90; break;
            case 'inference-complete':
                // status comes from hook
                $progress = 95;
                updateStatus__replicate($item['reid'], ['status'=>'downloading']); 
                end_connection(json_encode(['status'=>'downloading', 'progress'=>95]));
                require AJAX_FOLDER_PATH . '/replicate/background-task01-downloadResults.php';
                break;
            case 'downloading': $progress = 95; break;
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