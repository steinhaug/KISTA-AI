<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<title>KISTA AI</title>
<link rel="stylesheet" type="text/css" href="styles/bootstrap.css">
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
<link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
<div class="menu-hider"></div>
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="index.html" class="header-title">Processing</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(3)?>

    <div class="page-content header-clear-medium">

            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">Analyzing...</p>
                    <h1>Processing your image</h1>

                    <div class="d-flex justify-content-center mb-3">
                        <div class="spinner-border color-blue-dark" style="border-width: 7px;" role="status"></div>
                    </div>

                    <div class="progress rounded-l" style="height:28px">
                        <div id="kista-ai-progress" class="progress-bar bg-highlight text-start ps-3 color-white" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
                            10% Complete
                        </div>
                    </div>
               </div>
            </div>


        <div data-menu-load="menu-footer.html"></div>
    </div>
    <!-- Page content ends here-->
    
    <!-- Main Menu--> 
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-media"></div>
    
    <!-- Share Menu-->
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    
    <!-- Colors Menu-->
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 
     
    
</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>

<?php
if(isset($_SESSION['error_msg'])){
    echo '
    <div id="notification-1" data-dismiss="notification-1" data-bs-delay="3000" data-bs-autohide="true" class="notification notification-ios bg-dark-dark ms-2 me-2 mt-2 rounded-s">
        <span class="notification-icon color-white rounded-s">
            <i class="fa fa-bell"></i>
            <em>Error</em>
            <i data-dismiss="notification-1" class="fa fa-times-circle"></i>
        </span>
        <h1 class="font-18 color-white mb-n3">All Good</h1>
        <p class="pt-1">
            ' . $_SESSION['error_msg'] . '
        </p>
    </div>  
    <script>
    var toastID = document.getElementById("notification-1");
    toastID = new bootstrap.Toast(toastID);
    toastID.show();
    </script>
    ';
    unset($_SESSION['error_msg']);
}
?>

</body><?php
ob_end_flush();
?>