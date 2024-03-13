<?php

$USER_ID = 0;

/*
if( !isset($_COOKIE['PHPSESSID']) ){
    session_destroy();
    header("Location: reciepe.php?action=reset&return=" . rawurlencode(prepareLocation()));
    exit;
}
*/
if( isset($_SESSION['USER_ID']) and isset($_SESSION['USER_SESSION']) ){
    $sql = "SELECT * 
            FROM `" . $kista_dp . "users__sessions` `us` 
            WHERE `us`.`user_id`=? AND `us`.`session_id`=?
            ";
    $res = $mysqli->prepared_query($sql, 'is', [$_SESSION['USER_ID'],$_SESSION['USER_SESSION']]);
    if (count($res)) {
        // We are logged in
        $_SESSION['logged_in'] = true;
        $USER_ID = $_SESSION['USER_ID'];
    } else {
        header("Location: /auto-login.php?action=login_error&return=" . rawurlencode(prepareLocation()));
        exit;
    }
} else {
    header("Location: /auto-login.php?action=login&return=" . rawurlencode(prepareLocation()));
    exit;
}
