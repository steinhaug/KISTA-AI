<?php


ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';

$APP_NAME = 'KISTA';
$USER_NAME = 'anonymous';
$login_cookie_name = '_login_cookie';

$uri = false;
if( !empty($_GET['return']) ){
    $uri = rawurldecode($_GET['return']);
    #if( strpos($uri,'?')!==false ){
    #}
}

#echo htmlentities($uri); exit;

/*
$_SESSION['app']
$_SESSION['sessid']
$_SESSION['USER_ID']
$_SESSION['USER_SESSION']
$_SESSION['USER_GOOGLE_LOGIN']
$_SESSION['createCookie']
*/



if (!isset($_COOKIE[$login_cookie_name])) {
    $_SESSION['app'] = $APP_NAME;
    $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
    $valid_to =  date("Y-m-d H:i:s", time() +2592000);

    $sql = new sqlbuddy;
    $sql->que('session_id', $_SESSION['sessid'], 'str');
    $sql->que('google_id', 1, 'int');
    $sql->que('valid_to', $valid_to, 'str');
    $sql->que('user_agent', $_SERVER['HTTP_USER_AGENT'], 'str:255');
    $mysqli->query( $sql->build('insert', $kista_dp . "users__sessions") );
    //$mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $valid_to ."')");

    setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
    $USER_ID = $mysqli->insert_id;
    define('USER_ID', $USER_ID);
    $_SESSION['USER_ID'] = $USER_ID;
    $_SESSION['USER_SESSION'] = cleanString($_SESSION['sessid']);
    $_SESSION['createCookie'] = true;
    logfile('auto-login: create new session + cookie');
} else {
    $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "users__sessions` WHERE `session_id`='" . cleanString($_COOKIE[$login_cookie_name]) . "'");
    if ($res->num_rows) {
        $item = $res->fetch_assoc();
        $USER_ID = $item['user_id'];
        $_SESSION['USER_ID'] = $item['user_id'];
        $_SESSION['USER_SESSION'] = $item['session_id'];
        $_SESSION['getCookie'] = true;
        define('USER_ID', $USER_ID);
        loadSessionData($item['user_id']);
        logfile('auto-login: found cookie + load session');
    } else {
        $_SESSION['app'] = $APP_NAME;
        $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
        $valid_to =  date("Y-m-d H:i:s", time() +2592000);

        $sql = new sqlbuddy;
        $sql->que('session_id', $_SESSION['sessid'], 'str');
        $sql->que('google_id', 1, 'int');
        $sql->que('valid_to', $valid_to, 'str');
        $sql->que('user_agent', $_SERVER['HTTP_USER_AGENT'], 'str:255');
        $mysqli->query( $sql->build('insert', $kista_dp . "users__sessions") );
        //$mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $valid_to ."')");

        setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
        $USER_ID = $mysqli->insert_id;
        define('USER_ID', $USER_ID);
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_SESSION'] = cleanString($_SESSION['sessid']);
        $_SESSION['setCookie'] = true;
        setSessionKey('seed', ['power'=>50,'vol'=>0]);
        logfile('auto-login: cookie not found. Created new session + cookie');
    }
}

if($uri!==false)
    header('Location: ' . $uri);
    else
    header('Location: /upload.php');

exit;
