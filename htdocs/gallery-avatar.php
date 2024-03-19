<?php


ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

if($lang == 'en'){
    $txts = [
        'pageTitle' => 'Gallery',
        'supTitle' => 'Gallery',
        'title' => 'Your avatars',
        'paragraph' => 'Click on the thumbnail to view more.',
        'reciepe' => 'Reciepe'
    ];
} else {
    $txts = [
        'pageTitle' => 'Galleri',
        'supTitle' => 'Galleri',
        'title' => 'Mine avatarer',
        'paragraph' => 'Klikk på bilde for å se mer.',
        'reciepe' => 'Oppskrift'
    ];
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
<link rel="stylesheet" type="text/css" href="styles/style.css?<?=$html_NoCache_Version?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
<link rel="manifest" href="_manifest.json.php?<?=$PWA_APP_VER?>" data-pwa-version="<?=$PWA_APP_VER?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?=$PWA_LANG['ico_folder']?>/icon-192x192.png">
<?=$PWA_LANG['highlight']?>
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="gallery.php" class="header-title"><?=$txts['pageTitle']?></a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER_AVATAR(4)?>

    <div class="page-content header-clear-medium">




        <div class="content mt-0 mb-0">
            <div class="d-flex">
                <div class="align-self-center">
                    <h1 class="mb-0 font-18">AI Avatar result</h1>
                </div>
                <!-- <div class="ms-auto align-self-center">
                    <a href="#" class="float-end font-12 font-400">See All</a>
                </div> -->
            </div>
        </div>
        
<?php



define('REPLICATE_INFERENCE_FOLDER', UPLOAD_PATH . '/ri');
if (($items = $mysqli->prepared_query("SELECT *, `reim`.`filename` AS `filename` FROM `" . $kista_dp . "replicate__images` `reim` INNER JOIN `" . $kista_dp . "replicate__uploads` `reup` ON `reim`.reid = `reup`.reid AND `reup`.user_id = ?", 'i', [$USER_ID])) !== []) {

    echo '
        <div class="splide double-slider visible-slider slider-no-arrows slider-no-dots" id="double-slider-1">
            <div class="splide__track">
                <div class="splide__list">
    ';

    foreach($items as $img){

        if( !file_exists(REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $img['filename']) )
            continue;

        $reid = $img['reid'];
        $src_name = $img['filename'];
        $medium = get_name_only($src_name) . '_m.' . 'jpg';
        $webUrl = '/uploaded_files/ri/';
        if( file_exists(REPLICATE_INFERENCE_FOLDER . DIRECTORY_SEPARATOR . $medium)){
            $src_name = $medium;
        }
        echo '
                    <div class="splide__slide">
                        <div class="card m-2 card-style">
                            <a href="' . $webUrl . $img['filename'] . '" data-gallery="new-inference"><img src="' . $webUrl . $src_name . '" class="img-fluid"></a>
                            <div class="p-2 bg-theme rounded-sm">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="mb-n1 font-14 line-height-xs pb-2">' . $img['created'] . '</h4>
                                    </div>
                                    <div class="ms-auto">

                                    <a href="show-avatar.php?reid=' . $reid . '&download=' . $img['filename'] . '" class="icon icon-s bg-theme shadow-xl rounded-xl float-end external-link"><i class="fa color-theme fa-download"></i></a>
                                    <a href="#" data-menu="timed-2" class="icon icon-s bg-theme shadow-xl rounded-xl float-end me-2 external-link" data-imid="' . $img['image_id'] . '" data-action="rotate-left"><i class="fa color-theme fa-rotate-left"></i></a>
                                    <a href="#" data-menu="timed-2" class="icon icon-s bg-theme shadow-xl rounded-xl float-end me-2 external-link" data-imid="' . $img['image_id'] . '" data-action="rotate-right"><i class="fa color-theme fa-rotate-right"></i></a>

                                    </div>
                                </div>
                                <!-- fa-rotate-left fa-rotate-right <p class="font-10 mb-0"><i class="fa fa-star color-yellow-dark pe-2"></i>34 Recommend It</p> -->
                            </div>
                        </div>
                    </div>
        ';
    }

    echo '
                </div>
            </div>
        </div>
    ';

}
?>



        <div data-menu-load="<?=$appConf['menuFooterAvatar']?>"></div>
    </div>
    
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-media"></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 
    <div id="timed-2" 
         class="menu menu-box-modal rounded-m" 
         data-menu-hide="1000"
         data-menu-width="220"
         data-menu-height="160">
         <h1 class="text-center fa-5x mt-2 pt-3 pb-2"><i class="fa fa-times-circle color-red-dark"></i></h1>
         <h2 class="text-center">Funksjon ikke aktivert!</h2>
    </div>

</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js?<?=$html_NoCache_Version?>"></script>

<?php
output_session_notification();
que_modal_tpl('login','logout');
echo write_modal_tpls();
?>

</body><?php
ob_end_flush();
?>