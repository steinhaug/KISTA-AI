<?php
define('KISTA_ENGINE', true);

define('AJAX_URI', '/admin/ajax2.php');
define('AJAX_FOLDER', 'ajax2');

if (!defined('APPDATA_PATH')) {
    define('APPDATA_PATH', dirname(__FILE__) . '/htdocs/inc_appdata');
}
if (!defined('UPLOAD_PATH')) {
    define('UPLOAD_PATH', dirname(__FILE__) . '/htdocs/uploaded_files');
}
$open_ai_key = 'OPEN_AI_KEY';

$mysql_user     = "";
$mysql_password = "";
$mysql_host     = "";
$mysql_port     = "3306";
$mysql_database = "";

$kista_dp = 'kistaai_';

$passwordOpt = ['cost' => 11];
$passwordAlgo  = PASSWORD_BCRYPT;
$salt = '';


// Localmode
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = Mysqli2::getInstance($mysql_host, $mysql_port, $mysql_user, $mysql_password, $mysql_database);
mysqli_set_charset($mysqli, "utf8");
if ($mysqli->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error;
    exit();
}
if( $mysqli->character_set_name() != 'utf8' ){
    if (!$mysqli->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
        exit();
    }
}

$mysqli->set_logfile_path('I:/python-htdocs/KISTA-AI/logs');
$mysqli->set_log_what_queries('all');
$mysqli->setDieOnError(false);
