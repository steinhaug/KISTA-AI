<?php


ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

if(empty($_GET['rid'])){
    header('Location: upload.php');
    exit;
}


if($lang == 'en'){
    $txts = [
        'pageTitle' => 'Nice refrigerator!',
        'supHead' => 'Hmmm... that\'s a funny fridge!',
        'head' => 'Chef wants to know',
        'subHead' => 'Are you sure you uploaded the right image?',
        'btn' => 'Upload page'
    ];
} else {
    $txts = [
        'pageTitle' => 'Flott kjøleskap!',
        'supHead' => 'Hmmm... spesiellt kjøleskap!',
        'head' => 'Kokken er i tvil',
        'subHead' => 'Lastet du opp riktig bilde? Kjøleskap vet du...',
        'btn' => 'Opplastningsside'
    ];
}


$upload_id = (int) $_GET['rid'];
$res = $mysqli->query("SELECT * FROM `" . $kista_dp . "uploaded_files` WHERE `upload_id`=" . $upload_id . " AND `user_id`=" . $USER_ID . " AND `status`='complete'");
if (!$res->num_rows) {
    $_SESSION['error_msg'] = 'Resource does not exist.';
    header('Location: gallery.php?rid=' . $upload_id);
    exit;
}

// Send to no-reciepe if no_fridge
$item = $res->fetch_assoc();
if($item['reciepe']!='<no_fridge />'){
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
<link rel="stylesheet" type="text/css" href="styles/style.css?<?=$html_NoCache_Version?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/css/fontawesome-all.min.css">
<link rel="manifest" href="_manifest.json.php?<?=$PWA_APP_VER?>" data-pwa-version="<?=$PWA_APP_VER?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?=$PWA_LANG['ico_folder']?>/icon-192x192.png">
<?=$PWA_LANG['highlight']?>
<?php
$bgImgStyle = "background-image:url('/uploaded_files/" . $item['reciepe_image'] . "');";
/*
echo '<style>';
echo '.no-fridge {';
echo 'background-image:url("/uploaded_files/' . $item['reciepe_image'] . '");';
echo '}';
echo '</style>';
*/
?>
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="upload.php" class="header-title"><?=$txts['pageTitle']?></a>
        <?=$HTML_HEADER('header-fixed')?>
    </div>

    <?=$HTML_FOOTER(2)?>

    <div class="page-content pb-0">
        <div data-card-height="cover" class="card card-style no-fridge m-0" style="<?=$bgImgStyle?>">
            <div class="card-center text-center">
				<div class="text-center pb-2">
					<p class="font-600 color-highlight mb-2 font-16"><?=$txts['supHead']?></p>
					<h1 class="font-50 color-white mb-4"><?=$txts['head']?></h1>
				</div>
                <p class="boxed-text-xl opacity-70 color-white">
                    <?=$txts['subHead']?>
                </p>
                <a href="upload.php" data-back-button class="btn btn-m bg-highlight btn-center-m rounded-s gradient-highlight font-600"><?=$txts['btn']?></a>
            </div>
            <div class="card-overlay bg-black opacity-40"></div>
        </div>
    </div>

    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html?<?=$html_NoCache_Version?>" data-menu-width="280" data-menu-active=""></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 

    <?php
    que_modal_tpl('login','logout');
    echo write_modal_tpls();
    ?>

</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js.php?<?=$html_NoCache_Version?>"></script>

<?php
output_session_notification();
?>

</body><?php
ob_end_flush();
?>