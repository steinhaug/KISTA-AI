<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

if( empty($_GET['aiid']) ){
    header('Location: error.php');
    exit;
}

?>
<!DOCTYPE HTML>
<html lang="<?=$lang?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<title><?=$appConf['headTitle']?></title>
<link rel="stylesheet" type="text/css" href="styles/bootstrap.css">
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
<link rel="manifest" href="_manifest.json.php" data-pwa-version="<?=$PWA_APP_VER?>">
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="processing.php" class="header-title">Processing</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(3)?>


    <div class="page-content header-clear-medium">

            <div class="card card-style">
                <div class="content" id="page-content">
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


        <div data-menu-load="<?=$appConf['menuFooter']?>"></div>
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
<script type="text/javascript" src="scripts/custom.js?<?=$html_NoCache_Version?>"></script>

<?php
$rid = (int) $_GET['aiid'];
?>
<script>
function initiateImageProcessing(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/ajax.php?aiid=<?=$rid?>&t=init", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            if (json.status === "complete") {
                window.location.href = "reciepe.php?rid=<?=$rid?>";
            } else if(json.status === "error") {
                window.location.href = "error.php?rid=<?=$rid?>";
            } else if(json.status === "idle") {
                var progressElement = document.getElementById('page-content');
                progressElement.innerHTML = '<h1>Page idle</h1><p>Nothing to do...</p>';
            }
        }
    };
    xhr.send();
}
function pollImageProcessing() {
    var xhr = new XMLHttpRequest();
    var timestamp = new Date().getTime();
    xhr.open("GET", "/ajax.php?aiid=<?=$rid?>" + "&t=" + timestamp, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(xhr.responseText);
            if (json.status === "idle") {
                var progressElement = document.getElementById('page-content');
                progressElement.innerHTML = '<h1>Page idle</h1><p>Nothing to do...</p>';
            } else if (json.status === "complete") {
                window.location.href = "reciepe.php?rid=<?=$rid?>";
            } else if (json.status === "error") {
                window.location.href = "error.php?rid=<?=$rid?>";
            } else {
                if (json.hasOwnProperty('progress') && Number.isInteger(json.progress)) {
                    updateProgress(json.progress);
                }
                setTimeout(pollImageProcessing, 7500);
            }
        }
    };
    xhr.send();
}
function updateProgress(value) {
    var progressElement = document.getElementById('kista-ai-progress');
    if (progressElement) {
        progressElement.style.width = value + '%';
        progressElement.innerHTML = value + '% Complete';
        progressElement.setAttribute('aria-valuenow', value);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initiateImageProcessing, 1);
    setTimeout(pollImageProcessing, 2000);
    //console.log('disabled!');
});
</script>

<?php
output_session_notification();
?>

</body><?php
ob_end_flush();
?>