<?php

$jsondata = ['status'=>100];

$CMD = $_POST['data']['CMD'] ?? '';
$CSSID = $_POST['data']['CSSID'] ?? '';
$image_id = $_POST['data']['IMID'] ?? 0;
$post_user_id = $_POST['data']['UserID'] ?? 0;

try {

    if( $post_user_id != $USER_ID )
        throw new Exception('Authentication error');

    $jsondata['message'] = print_r($_POST['data'], true);


    $user_google_id = $_SESSION['USER_GOOGLE_LOGIN'][0] ?? 0;
    if ($user_google_id) {
        $p_sql = [
            "SELECT `reim`.`reid` 
             FROM `" . $kista_dp . "replicate__images` `reim` 
             INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.`reid` = `reup`.`reid` AND `reim`.`image_id` = ? 
             INNER JOIN `" . $kista_dp . "users__sessions` `s` ON `reup`.`user_id` = `s`.`user_id` 
             WHERE (`s`.`user_id` = ? OR `s`.`google_id` = ?)", 
            'iii', 
            [$image_id, $USER_ID, $user_google_id], 
            true
        ];
    } else {
        $p_sql = [
            "SELECT `reim`.`reid` 
             FROM `" . $kista_dp . "replicate__images` `reim` 
             INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.`reid` = `reup`.`reid` AND `reim`.`image_id` = ? AND `reup`.user_id = ?", 
            'ii', 
            [$image_id, $USER_ID], 
            true
        ];
    }

    if (($item = $mysqli->prepared_query1($p_sql)) === null)
        throw new Exception('Image not found');

    $jsondata['CSSID'] = $CSSID;
    $jsondata['message'] = 'Ready for operation';

} catch (Exception $e) {
    $error = $e->getMessage();
    $jsondata['errorcode'] = 1;
    $jsondata['errormsg'] = $e->getMessage();
}
