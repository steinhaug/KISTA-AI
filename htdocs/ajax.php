<?php
ob_start();
session_cache_expire(720);
if (isset($_SERVER['HTTP_HOST']) and ('forhandler-network.local' == $_SERVER['HTTP_HOST'])) {
    session_start(['read_and_close' => true]);
} else {
    session_start();
}

define('AJAX_URI', '/admin/ajax.php');
define('AJAX_FOLDER', 'ajax');
define('AJAX_FOLDER_PATH', dirname(__FILE__) . '/' . AJAX_FOLDER);
define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';




require_once APPDATA_PATH . '/openai_helpers.php';
require_once AJAX_FOLDER_PATH . '/openai/run-tasks.php';
