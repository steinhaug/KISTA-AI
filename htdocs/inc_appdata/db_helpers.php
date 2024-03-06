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
    global $mysqli;

    $mysqli->query('UPDATE `uploaded_files__openai` SET `valid_to`=NOW() WHERE `valid_to` IS NULL AND `upload_id`=' . (int) $upload_id);

    $sql = [
        "INSERT INTO `uploaded_files__openai` (`upload_id`,`user_id`,`valid_from`,`valid_to`,`status`,`comment`) VALUES (?,?,NOW(),null,?,?)",
        "iiss",
        [$upload_id, _UserID, $status, $comment]
    ];
    $id = $mysqli->prepared_insert($sql);

    return $id;
}
//
// Fetch the current status of the order
//
function getUploadStatus($upload_id){
    global $mysqli;

    $upload_id = (int) $upload_id;
    $entry = $mysqli->query1("SELECT * FROM `order_status` WHERE `upload_id`={$upload_id} AND valid_to IS NULL");

    if( $entry === null ){
        // TODO: Should report error
    }

    return $entry['status'];
}