<?php

/**
 * Update the status for a WorkOrder
 *
 * @param int $WorkOrderID
 * @param string $status
 * @param string $comment
 *
 * @return int The ID for the new status
 */
function setUploadStatus($upload_id, $status, $comment = ''){
    global $mysqli, $kista_dp, $USER_ID;

    $mysqli->query('UPDATE `' . $kista_dp . 'uploaded_files__openai` SET `valid_to`=NOW() WHERE `valid_to` IS NULL AND `upload_id`=' . (int) $upload_id);

    if( !is_string($comment) ){
        $comment = json_encode($comment, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    $sql = [
        "INSERT INTO `" . $kista_dp . "uploaded_files__openai` (`upload_id`,`user_id`,`valid_from`,`valid_to`,`status`,`comment`) VALUES (?,?,NOW(),null,?,?)",
        "iiss",
        [$upload_id, $USER_ID, $status, $comment]
    ];
    $id = $mysqli->prepared_insert($sql);

    return $id;
}
//
// Fetch the current status of the order
//
function getUploadStatus($upload_id){
    global $mysqli, $kista_dp, $USER_ID;

    $upload_id = (int) $upload_id;
    $entry = $mysqli->query1("SELECT * FROM `" . $kista_dp . "uploaded_files__openai` WHERE `upload_id`={$upload_id} AND valid_to IS NULL");

    if( $entry === null ){
        // TODO: Should report error
        return 'ERROR:NOT FOUND';
    }

    return $entry['status'];
}

function saveReciepe($reciepe, $image=null, $thumbnail=null){
    global $mysqli, $upload_id, $kista_dp, $USER_ID;

    if( !is_string($reciepe) ){
        $reciepe = json_encode($reciepe, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    if( is_null($image) and is_null($thumbnail) ){
        $sql = [
            "INSERT INTO `" . $kista_dp . "uploaded_files__reciepes` (`upload_id`,`user_id`,`created`,`updated`,`image`,`thumbnail`,`reciepe`) VALUES (?,?,NOW(),null,'','',?)",
            "iis",
            [$upload_id, $USER_ID, $reciepe]
        ];
    } else {
        $sql = [
            "INSERT INTO `" . $kista_dp . "uploaded_files__reciepes` (`upload_id`,`user_id`,`created`,`updated`,`image`,`thumbnail`,`reciepe`) VALUES (?,?,NOW(),null,?,?,?)",
            "iisss",
            [$upload_id, $USER_ID, $image, $thumbnail, $reciepe]
        ];
    }
    $id = $mysqli->prepared_insert($sql);
    return $id;
}

function updateReciepe($reciepe_id, $data){
    global $mysqli, $upload_id, $kista_dp, $USER_ID;

    if( isset($data['image']) and isset($data['thumbnail']) and isset($data['reciepe']) ){
        if( !is_string($data['reciepe']) ) $data['reciepe'] = json_encode($data['reciepe'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $sql = [
            "UPDATE `" . $kista_dp . "uploaded_files__reciepes` SET `updated`=NOW(), `image`=?, `thumbnail`=?, `reciepe`=? WHERE `reciepe_id`=?",
            "sssi",
            [$data['image'], $data['thumbnail'], $data['reciepe'], $reciepe_id]
        ];
    } else if( isset($data['image']) and isset($data['thumbnail']) and !isset($data['reciepe']) ){
        $sql = [
            "UPDATE `" . $kista_dp . "uploaded_files__reciepes` SET `updated`=NOW(), `image`=?, `thumbnail`=? WHERE `reciepe_id`=?",
            "ssi",
            [$data['image'], $data['thumbnail'], $reciepe_id]
        ];
    } else if( !isset($data['image']) and !isset($data['thumbnail']) and isset($data['reciepe']) ){
        if( !is_string($data['reciepe']) ) $data['reciepe'] = json_encode($data['reciepe'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $sql = [
            "UPDATE `" . $kista_dp . "uploaded_files__reciepes` SET `updated`=NOW(), `reciepe`=? WHERE `reciepe_id`=?",
            "si",
            [$data['reciepe'], $reciepe_id]
        ];
    }

    $success = $mysqli->prepared_insert($sql);
    return $success;

}





/**
 * Removes completed and failed tasks from session
 *
 * @param array $item The DB row from uploads table
 * 
 * @return mixed Successfull removal of task will return integer, namely the removed key. All other return null.
 */
function task_validation__open_ai_tasks($item){
 
    // 100% states are complete and error, both ultimately means nothing more will happen so we remove the task.
    if(!($item['status']=='complete' or $item['status']=='error'))
        return false;

    return removeSessionTask(['aiid'=>$item['upload_id']]);
}


/**
 * Removes completed and failed tasks from session
 *
 * @param array $item The DB row from uploads table
 * 
 * @return mixed Successfull removal of task will return integer, namely the removed key. All other return null.
 */
function task_validation__replicate_tasks($item){
 
    // 100% states are complete and error, both ultimately means nothing more will happen so we remove the task.
    if(!($item['status']=='complete' or $item['status']=='error'))
        return false;

    return removeSessionTask(['reid'=>$item['reid']]);
}



/**
 * json_encode string if array or object
 *
 * @param mixed $rawData String, array or object.
 * @return string Will return a string for insertion
 */
function json_encode_if_arrobj($rawData){
    if( is_array($rawData) or is_object($rawData) ){
        try {
            $jsonData = json_encode((string) $rawData);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new RuntimeException('json_encode_if_array(): ' . json_last_error());
            }
            return $jsonData;
        } catch(RuntimeException $e) {
            $error_id = debug_log_error($e->getMessage());
            return 'Json encode error, ErrorID: ' . $error_id;
        } catch(Exception $e) {
            $error_id = debug_log_error($e->getMessage());
            return 'Json encode exception, ErrorID: ' . $error_id;
        }
    } else {
        return (string) $rawData;
    }
}



/**
 * Update status for replicate task
 *
 * @param int $reid
 * @param string $status
 * @param mixed $log String or array to save in the log column
 * @return void
 */
function updateStatus__replicate($reid, $props){
    global $mysqli, $kista_dp;

    if( empty($props) or !is_array($props))
        throw new Exception('updateStatus__replicate($props must be array!)');

    logfile('reid ' . $reid . ', new status: ' . $props['status']);

    $sql = new sqlbuddy;

    if(isset($props['replicate_id']))
        $sql->que('replicate_id', $props['replicate_id'], 'string:26');

    $sql->que('updated', 'NOW()', 'raw');
    $sql->que('status', $props['status'], 'string');

    if(isset($props['data']))
        $sql->que('data', json_encode_if_arrobj($props['data']), 'text');
    if(isset($props['log']))
        $sql->que('log', json_encode_if_arrobj($props['log']), 'text');
    if(isset($props['error']))
        $sql->que('error', json_encode_if_arrobj($props['error']), 'text');

    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));

    return $success;
}



/**
 * Hook processed with errors
 *
 * @param int $whid The key for the hook
 * @param string $replicate_id The string identifyier from replicate linked with the uploaded image
 * @param string $error_msg Error message, what went wrong
 * @return void
 */
function hookUpd_processError($whid, $replicate_id, $error_msg){
    global $mysqli, $kista_dp;

    hookUpd_process($whid);

    if( ($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `replicate_id`=?", 's', [$replicate_id], true)) !== null ){

        updateStatus__replicate($item['reid'], [
            'status' => 'error', 
            'error' => (string) $error_msg
        ]);

    }

}


/**
 * Hook processed
 *
 * @param int $whid The key for the hook 
 * @return void
 */
function hookUpd_process($whid){
    global $mysqli, $kista_dp;

    return $mysqli->query("UPDATE `" . $kista_dp . "replicate__hooks` SET `processed`=1 WHERE `whid`=" . (int) $whid);
}
