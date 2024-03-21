<?php

    $jsondata = ['status'=>100];


try {

    $CMD = $_POST['data']['CMD'] ?? '';
    $CSSID = $_POST['data']['CSSID'] ?? '';
    $image_id = $_POST['data']['IMID'] ?? 0;
    $post_user_id = $_POST['data']['UserID'] ?? 0;

    if( $post_user_id != $USER_ID )
        throw new Exception('Authentication error');

    $jsondata['message'] = print_r($_POST['data'], true);

    if (($item = $mysqli->prepared_query1("SELECT `reim`.reid FROM `" . $kista_dp . "replicate__images` `reim` INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.reid = `reup`.reid AND `reim`.image_id = ? AND `reup`.user_id = ?", 'ii', [$image_id, $USER_ID], true)) === null)
        throw new Exception('Image not found');



    $jsondata['CSSID'] = $CSSID;
    $jsondata['message'] = print_r($item, true);


} catch (Exception $e) {
    $error = $e->getMessage();
    $jsondata['errorcode'] = 1;
    $jsondata['errormsg'] = $e->getMessage();
}
