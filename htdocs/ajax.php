<?php
ob_start();
session_cache_expire(720);
if (isset($_SERVER['HTTP_HOST']) and ('forhandler-network.local' == $_SERVER['HTTP_HOST'])) {
    session_start(['read_and_close' => true]);
} else {
    session_start();
}

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

require_once 'ajax/openai/run-tasks.php';

