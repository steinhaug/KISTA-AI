<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

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

if (!isset($_COOKIE[$login_cookie_name])) {
    $_SESSION['app'] = $APP_NAME;
    $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
    $validto =  date("Y-m-d H:i:s", time() +2592000);
    $mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $validto ."')");
    setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
    $USER_ID = $mysqli->insert_id;
    define('USER_ID', $USER_ID);
    $_SESSION['USER_ID'] = $USER_ID;
    $_SESSION['USER_SESSION'] = cleanString($_SESSION['sessid']);
    $_SESSION['createCookie'] = true;
} else {
    $res = $mysqli->query("SELECT * FROM `" . $kista_dp . "users__sessions` WHERE `sessionid`='" . cleanString($_COOKIE[$login_cookie_name]) . "'");
    if ($res->num_rows) {
        $item = $res->fetch_assoc();
        $USER_ID = $item['id'];
        $_SESSION['USER_ID'] = $item['id'];
        $_SESSION['USER_SESSION'] = $item['sessionid'];
        $_SESSION['getCookie'] = true;
        define('USER_ID', $USER_ID);
        loadSessionData($item['id']);
    } else {
        $_SESSION['app'] = $APP_NAME;
        $_SESSION['sessid'] = $APP_NAME . rand(0, 100000).md5(1 . $USER_NAME.rand(0, 100000).time());
        $validto =  date("Y-m-d H:i:s", time() +2592000);
        $mysqli->query("INSERT INTO `" . $kista_dp . "users__sessions` VALUES (NULL, '". cleanString($_SESSION['sessid']) ."','". 1 ."','". $validto ."')");
        setcookie($login_cookie_name, $_SESSION['sessid'], time() + (86400 * 30), "/");
        $USER_ID = $mysqli->insert_id;
        define('USER_ID', $USER_ID);
        $_SESSION['USER_ID'] = $USER_ID;
        $_SESSION['USER_SESSION'] = cleanString($_SESSION['sessid']);
        $_SESSION['setCookie'] = true;
        setSessionKey('seed', ['power'=>50,'vol'=>0]);

    }
}

if($uri!==false)
    header('Location: ' . $uri);
    else
    header('Location: /upload.php');

exit;
