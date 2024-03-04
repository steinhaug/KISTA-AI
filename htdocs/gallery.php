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
        <a href="index.html" class="header-title">Galleri</a>
        <a href="#" data-back-button class="header-icon header-icon-1"><i class="fa fa-chevron-left"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-3 show-on-theme-dark"><i class="fas fa-sun"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-3 show-on-theme-light"><i class="fas fa-moon"></i></a>
        <a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
    </div>

    <div id="footer-bar" class="footer-bar-6">
        <a href="index-components.html"><i class="fa fa-layer-group"></i><span>Features</span></a>
        <a href="index-pages.html"><i class="fa fa-file"></i><span>Pages</span></a>
        <a href="upload.php" class="circle-nav"><i class="fa fa-home"></i><span>Last opp</span></a>
        <a href="index-projects.html" class="active-nav"><i class="fa fa-camera"></i><span>Projects</span></a>
        <a href="#" data-menu="menu-main"><i class="fa fa-bars"></i><span>Menu</span></a>
    </div>

    <div class="page-content header-clear-medium">

            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">Classic Thumbs</p>
                    <h1>Squared</h1>
                    <p class="mb-3">
                        The classic squared thumbnail gallery. A must have for any basic gallery.
                    </p>
                    <div class="row row-cols-3 px-1 mb-0">
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
                        <a class="col p-2" href="images/pictures/150x150.jpg" data-gallery="gallery-a">
                            <img src="images/pictures/150x150.jpg" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
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