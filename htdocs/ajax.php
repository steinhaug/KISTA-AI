<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

#session_start(['read_and_close' => true]);
session_start();

define('AJAX_URI', '/admin/ajax.php');
define('AJAX_FOLDER', 'ajax');
define('AJAX_FOLDER_PATH', dirname(__FILE__) . '/' . AJAX_FOLDER);
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

ignore_user_abort(true);
set_time_limit(0);

if (($curentTaskID = getSessionTaskKey(['aiid'=> _GET('aiid', 0, 'int')])) !== null) {
    require_once AJAX_FOLDER_PATH . '/openai/run-tasks.php';
} else if( ($curentTaskID = getSessionTaskKey(['reid'=> _GET('reid', 0, 'int')])) !== null ){
    require_once AJAX_FOLDER_PATH . '/replicate/run-tasks.php';
} else {

    /*
    if(isset($_GET['test'])){
        $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `reid`=28");
        if ($res->num_rows) {
        logfile('4');
            $item = $res->fetch_assoc();
            require AJAX_FOLDER_PATH . '/replicate/background-task01-downloadResults.php';
        }

        logfile('done');
        exit;
    }
    */



    if( !empty($_GET['aiid'])){
        $upload_id = (int) $_GET['aiid'];
        $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID);
        if ($res->num_rows) {
            $item = $res->fetch_assoc();
            task_validation__open_ai_tasks($item);

            if($item['status']=='error')
                $_SESSION['error_msg'] = $item['error'];
            echo json_encode(['status'=>$item['status'], 'progress'=>200]);
            exit;
        }
    }

    if( !empty($_GET['reid'])){
        $reid = (int) $_GET['reid'];
        $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `reid`=" . $reid . " AND `user_id`=" . $USER_ID);
        if ($res->num_rows) {
            $item = $res->fetch_assoc();
            task_validation__replicate_tasks($item);
            if($item['status']=='error')
                $_SESSION['error_msg'] = $item['error'];

            if($item['status'] == 'waiting')
                echo json_encode(['status'=>$item['status'], 'progress'=>60]);
                else
                echo json_encode(['status'=>$item['status'], 'progress'=>200]);
            exit;
        }
    }


}

//http_response_code(102);
header('HTTP/1.0 200 OK');
echo json_encode(['status'=>'idle','progress'=>0,'message'=>'Nothing to do.']);
exit;
