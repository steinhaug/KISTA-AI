<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

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
        'title' => 'Your reciepes',
        'paragraph' => 'Click on the thumbnail to view the reciepe.',
        'reciepe' => 'Reciepe'
    ];
} else {
    $txts = [
        'pageTitle' => 'Galleri',
        'supTitle' => 'Galleri',
        'title' => 'Mine oppskrifter',
        'paragraph' => 'Klikk på bilde for å se oppskriften.',
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
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="gallery.php" class="header-title"><?=$txts['pageTitle']?></a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(4)?>

    <div class="page-content header-clear-medium">





        <div class="content mt-4 pt-2 mb-0">
            <div class="d-flex">
                <div class="align-self-center">
                    <h1 class="mb-0 font-18">Our Favorites</h1>
                </div>
                <div class="ms-auto align-self-center">
                    <a href="#" class="float-end font-12 font-400">See All</a>
                </div>
            </div>
        </div>
        <div class="splide double-slider slider-no-arrows slider-no-dots" id="double-slider-2">
            <div class="splide__track">
                <div class="splide__list">
                    <div class="splide__slide">
                        <div class="card  card-style">
                            <img src="images/food/regular/1.jpg" class="img-fluid">
                            <div class="p-2 bg-theme rounded-sm">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="mb-n1 font-14 line-height-xs pb-2">Royal <br>Burger</h4>
                                    </div>
                                    <div class="ms-auto">
                                        <h4 class="font-16">$24,25</h4>
                                    </div>
                                </div>
                                <p class="font-10 mb-0"><i class="fa fa-star color-yellow-dark pe-2"></i>34 Recommend It</p>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide">
                        <div class="card  card-style">
                            <img src="images/food/regular/2.jpg" class="img-fluid">
                            <div class="p-2 bg-theme rounded-sm">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="mb-n1 font-14 line-height-xs pb-2">Pizza <br>Prosciutto</h4>
                                    </div>
                                    <div class="ms-auto">
                                        <h4 class="font-16">$24,25</h4>
                                    </div>
                                </div>
                                <p class="font-10 mb-0"><i class="fa fa-star color-yellow-dark pe-2"></i>34 Recommend It</p>
                            </div>
                        </div>
                    </div>
                    <div class="splide__slide">
                        <div class="card card-style">
                            <img src="images/food/regular/3.jpg" class="img-fluid">
                            <div class="p-2 bg-theme rounded-sm">
                                <div class="d-flex">
                                    <div>
                                        <h4 class="mb-n1 font-14 line-height-xs pb-2">Chocolate <br>Dessert</h4>
                                    </div>
                                    <div class="ms-auto">
                                        <h4 class="font-16">$24,25</h4>
                                    </div>
                                </div>
                                <p class="font-10 mb-0"><i class="fa fa-star color-yellow-dark pe-2"></i>34 Recommend It</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    

<?php
$items_list = [];
$items = $mysqli->result('assoc')->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'");
foreach ($items as $item) {
    if ($item['reciepe'] == '<no_fridge />') {
        $src = reciepe_thumb($item['reciepe_image']);
        $link = 'no-reciepe.php?rid=' . $item['upload_id'];
    } else {
        $src = reciepe_thumb($item['reciepe_image']);
        $link = 'reciepe.php?rid=' . $item['upload_id'];
    }
    $title = reciepe_title($item['reciepe']);
    $items_list[] = [$title, $link, $src];
}

echo count($items_list);






$count = $mysqli->query1("SELECT count(*) as `count` FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'",0);

if($count>5){ ?>
        <div class="splide single-slider slider-arrows slider-no-dots" id="single-slider-1">
            <div class="splide__track">
                <div class="splide__list">
                    <div class="splide__slide">

                        <div class="row me-0 ms-0 mb-0">
    <?php
    $i = 0;
    $items = $mysqli->result('assoc')->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'");
    foreach ($items as $item) {
        if ($item['reciepe'] == '<no_fridge />') {
            $src = reciepe_thumb($item['reciepe_image']);
            $link = 'no-reciepe.php?rid=' . $item['upload_id'];
        } else {
            $src = reciepe_thumb($item['reciepe_image']);
            $link = 'reciepe.php?rid=' . $item['upload_id'];
        }
        if ($i and !($i % 4)) {
            echo '
                        </div>
                    </div>
                    <div class="splide__slide">
                        <div class="row me-0 ms-0 mb-0">
   ';
        }
        $title = reciepe_title($item['reciepe']);
        echo '
            <div class="col-3 ps-0 pe-0">
                <div class="card card-style">
                    <a href="' . $link . '" class="img-fluid"><img src="' . $src . '" class="img-fluid"></a>
                    <div class="content pb-0">
                        <p class="mb-n1 color-highlight font-10 font-600">' . $txts['reciepe'] . '</p>
                        <h1 class="font-15">' . $title . '</h1>
                        <!-- <p class="mb-0">This is a forth of a column.</p> -->
                    </div>
                </div>
            </div>
    ';
        $i++;
    }
    ?>
                        </div>
        
                    </div>
                </div>
            </div>
        </div>
<br>
<?php } ?>





<?php
$count = $mysqli->query1("SELECT count(*) as `count` FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'",0);
if($count>5){
    echo '
        <div class="splide double-slider slider-arrows slider-no-dots" id="double-slider-1">
            <div class="splide__track">
                <div class="splide__list">';
    $i = 0;
    $items = $mysqli->result('assoc')->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'");
    foreach ($items as $item) {
        if ($item['reciepe'] == '<no_fridge />') {
            $src = reciepe_thumb($item['reciepe_image']);
            $link = 'no-reciepe.php?rid=' . $item['upload_id'];
        } else {
            $src = reciepe_thumb($item['reciepe_image']);
            $link = 'reciepe.php?rid=' . $item['upload_id'];
        }
        $title = reciepe_title($item['reciepe']);
        echo '      <div class="splide__slide">
                        <div class="card mx-3 mb-0 card-style" data-card-height="350" style="background-image:url(' . $src . ')">
                            <div class="card-top">
                                <a href="' . $link . '" class="icon icon-xxs bg-white color-black rounded-xl mt-3 me-2 float-end"><i class="fa fa-list-alt"></i></a>
                            </div>
                            <div class="card-bottom">
                                <h3 class="color-white font-800 mb-3 pb-1 ps-3">' . $title . '</h3>
                            </div>
                            <div class="card-overlay bg-gradient"></div>
                        </div>
                        <!-- <p class="mx-3 mb-0 mt-2 color-highlight font-600">Oppskrift</p>
                        <h4 class="mx-3 mb-4">' . $title . '</h4> -->
                    </div>
        ';
    }
    echo '
                </div>
            </div>
        </div>';
}
?>














            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600"><?=$txts['supTitle']?></p>
                    <h1><?=$txts['title']?></h1>
                    <p class="mb-3">
                        <?=$txts['paragraph']?>
                    </p>
                    <div class="row row-cols-3 px-1 mb-0">
<?php

$items = $mysqli->result('assoc')->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `user_id`=" . $USER_ID . " AND `reciepe_image` != '' AND `status`='complete'");
foreach($items as $item){
    #var_dump($item);
    if( $item['reciepe'] == '<no_fridge />' ){
        echo '
                        <a class="col p-2" href="no-reciepe.php?rid=' . $item['upload_id'] . '" _data-gallery="gallery-a">
                            <img src="' . reciepe_thumb($item['reciepe_image']) . '" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
        ';
    } else {
        echo '
                        <a class="col p-2" href="reciepe.php?rid=' . $item['upload_id'] . '" _data-gallery="gallery-a">
                            <img src="' . reciepe_thumb($item['reciepe_image']) . '" alt="img" class="img-fluid rounded-s shadow-xl">
                        </a>
        ';
    }
}
?>
                    </div>
                </div>
            </div>

        <div data-menu-load="<?=$appConf['menuFooter']?>"></div>
    </div>
    
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-media"></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 

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