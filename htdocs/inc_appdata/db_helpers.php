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
            "iisss",
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