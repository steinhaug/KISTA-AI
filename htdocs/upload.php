<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

$callbackURL = 'upload.php';
$continueURL = 'processing.php';

if( isset( $_POST ) && is_array( $_POST ) && isset($_SERVER['CONTENT_TYPE']) ) {

    $file1 = $_FILES['file1']['tmp_name'];
    $file1_name = $_FILES['file1']['name'];
    $file1_size = $_FILES['file1']['size'];

    require APPDATA_PATH . '/XUploadFile.inc.php';
    $myU1 = new Xupload("file1",1);
    $myU1->ignore_cls_arr_ext_accepted = true; // Allow all!

    $myU1->setDir(UPLOAD_PATH);
    clearstatcache();
	$_real_filename_from_upload = $myU1->get_filename();
    $filename = prepare_available_filename($_real_filename_from_upload, UPLOAD_PATH);
    $file_extension = get_extension($_real_filename_from_upload);

    $deniedExtensions = [
        'php','php1','php2','php3','php4','shtml','pl','cgi','asp'
    ];
    if(in_array($file_extension, $deniedExtensions)){
        $msg = $filename['type'] . ' er ikke en gyldig filtype, av sikkerhetsmessige årsaker får du ikke adgang å laste opp denne filen.<br>';
        $msg .= 'Om du ønsker å legge opp filen i Easy CMS kan du pakke den ned som ZIP å laste den opp.';
        $_SESSION['error_msg'] = $msg;
        header('Location: ' . $callbackURL . '?error');
        exit;
    } else {
        $myU1->changeFilename($filename['file']);
        $myU1->xCopy($filename['file']);
        if (!$myU1->show_progressStatus()){
            $sql = new sqlbuddy;
            $sql->push('user_id', $USER_ID,'int');
            $sql->push('created', 'NOW()','raw');
            $sql->push('updated', 'NULL','raw');
            $sql->que('realname', $_real_filename_from_upload, 'string');
            $sql->que('filename', $filename['file'], 'string');
            $sql->que('extension', $file_extension, 'string');
            $sql->que('filesize', $file1_size, 'int');
            $sql->que('thumbnail', '', 'string');
            $sql->que('reciepe_image', '', 'string');
            $sql->que('reciepe', '', 'string');
            $sql->que('status', 'start', 'string');
            $sql->que('log', 'NULL', 'raw');
            $sql->que('error', 'NULL', 'raw');
            $mysqli->query( $sql->build('insert', $kista_dp . "uploaded_files") );
            $aiid = $mysqli->insert_id;
            $_SESSION['task'] = ['aiid'=>$aiid, 'status'=>'start', 'progress'=>0];
            header('Location: ' . $continueURL . '?aiid=' . $aiid);
            exit;

        } else {
            $msg = 'Noe gikk galt med opplastning av filen, kode:' . $myU1->show_progressStatus() . '! Opplastning avbrutt, prøv igjen.';
            $_SESSION['error_msg'] = $msg;
            header('Location: ' . $callbackURL . '?error');
            exit;
        }
    }
}

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
</head>

<body class="theme-light">

<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center header-auto-show">
        <a href="index.html" class="header-title">Tables</a>
        <a href="#" data-back-button class="header-icon header-icon-1"><i class="fas fa-chevron-left"></i></a>
        <a href="#" data-menu="menu-main" class="header-icon header-icon-4"><i class="fas fa-bars"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-3 show-on-theme-dark"><i class="fas fa-sun"></i></a>
        <a href="#" data-toggle-theme class="header-icon header-icon-3 show-on-theme-light"><i class="fas fa-moon"></i></a>
    </div>

    <div id="footer-bar" class="footer-bar-6">
        <a href="index-components.html" class="active-nav"><i class="fa fa-layer-group"></i><span>Features</span></a>
        <a href="index-pages.html"><i class="fa fa-file"></i><span>Pages</span></a>
        <a href="upload.php" class="circle-nav"><i class="fa fa-home"></i><span>Last opp</span></a>
        <a href="index-projects.html"><i class="fa fa-camera"></i><span>Projects</span></a>
        <a href="#" data-menu="menu-main"><i class="fa fa-bars"></i><span>Menu</span></a>
    </div>

    <div class="page-title page-title-fixed">
        <h1>Last opp bilde</h1>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-share"><i class="fa fa-share-alt"></i></a>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-light" data-toggle-theme><i class="fa fa-moon"></i></a>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme show-on-theme-dark" data-toggle-theme><i class="fa fa-lightbulb color-yellow-dark"></i></a>
        <a href="#" class="page-title-icon shadow-xl bg-theme color-theme" data-menu="menu-main"><i class="fa fa-bars"></i></a>
    </div>
    <div class="page-title-clear"></div>

    <div class="page-content">


        <div class="card card-style shadow-xl">
            <div class="content">
                <p class="color-highlight font-600 mb-n1">Last opp bilde</p>
                <h1 class="font-24 font-700 mb-2">Last opp bilde <i class="fa fa-star mt-n2 font-30 color-yellow-dark float-end me-2 scale-box"></i></h1>
                <p class="mb-1">
                    Ta bilde av kjøleskapet ditt og last det opp her.
                </p>
                <form action="upload.php" method="post" enctype="multipart/form-data" style="text-align:center">
                    <input type="hidden" namer="action" value="start">
                    <input name="file1" type="file" accept="image/*" class="">
                    <br><br>
                    <button type="submit" class="btn btn-m btn-full mb-3 rounded-0 text-uppercase font-900 shadow-s bg-red-light" style="margin:0 auto;">Last opp bilde</button>
                </form>
            </div>
        </div>


        <div data-menu-load="menu-footer.html"></div>
    </div>
    <!-- Page content ends here-->

    <!-- Main Menu-->
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-components"></div>

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