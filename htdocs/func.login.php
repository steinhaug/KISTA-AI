<?php

$APP_NAME = 'KISTA';
$login_cookie_name = '_login_cookie';
$USER_ID = 1;
$USER_NAME = 'anonymous';

if (!isset($_COOKIE[$login_cookie_name])) {
    $_SESSION['app'] = $APP_NAME;
    $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
    $validto =  date("Y-m-d H:i:s", time() +2592000);
    $mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $validto ."')");
    setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
    $USER_ID = $mysqli->insert_id;
} else {
    $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "users__sessions` WHERE `sessionid`='" . cleanString($_COOKIE[$login_cookie_name]) . "'");
    if ($res->num_rows) {
        $item = $res->fetch_assoc();
        $USER_ID = $item['id'];
    } else {
        $_SESSION['app'] = $APP_NAME;
        $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
        $validto =  date("Y-m-d H:i:s", time() +2592000);
        $mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $validto ."')");
        setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
        $USER_ID = $mysqli->insert_id;
    }
}
