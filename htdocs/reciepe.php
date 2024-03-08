<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

if(empty($_GET['rid'])){
    header('Location: gallery.php');
    exit;
}


// Failsafe
$upload_id = (int) $_GET['rid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID . " AND `status`='complete'");
if (!$res->num_rows) {
    $_SESSION['error_msg'] = 'Resource does not exist.';
    header('Location: gallery.php?rid=' . $upload_id);
    exit;
}


// Send to no-reciepe if no_fridge
$item = $res->fetch_assoc();
if($item['reciepe']=='<no_fridge />'){
    header('Location: no-reciepe.php?rid=' . $upload_id);
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
<link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="reciepe.php" class="header-title">Reciepe</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(2)?>

    <div class="page-content header-clear-medium">






            <div class="card card-style">

                <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows" id="single-slider-1">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="splide__slide">

                                <div class="content">
                                    <p class="mb-n1 color-highlight font-600">Reciepe</p>
                                        <?php
                                        //$upload_id = (int) $_SESSION['task']['aiid'];
                                        $log = [];
                                        openai__generateReciepe($item['reciepe'], $item['reciepe_image']);
                                        $log = json_decode($item['log'], 1);
                                        ?>
                                </div>

                            </div><!-- /slide -->
                            <div class="splide__slide"><div class="content">
                                <?php
                                    $Parsedown = new Parsedown();
                                    echo $Parsedown->text( '## Items detected from image:' . "\n\n" . $log['list'] );
                                    #foreach($log as $k=>$v)
                                    #    echo $k . '<br>';
                                ?>
                            </div></div><!-- /slide -->
                        </div>   
                    </div>
                </div><!-- /splide -->

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
output_session_notification();
?>

</body><?php
ob_end_flush();
?>