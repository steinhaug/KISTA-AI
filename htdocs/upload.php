<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

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

    $bonusConf = [];
    $_bonusConf = json_decode($_POST['bonus_conf'], 1);
    if(isset($_bonusConf[2])) $bonusConf[$_bonusConf[2]['name']] = $_bonusConf[2]['value']; else $bonusConf['vol'] = 0;
    if(isset($_bonusConf[3])) $bonusConf[$_bonusConf[3]['name']] = $_bonusConf[3]['value']; else $bonusConf['power'] = 50;

    $file1 = $_FILES['file1']['tmp_name'];
    $file1_name = $_FILES['file1']['name'];
    $file1_size = $_FILES['file1']['size'];

    if(!empty($_FILES['file1']['size'])){

        require APPDATA_PATH . '/XUploadFile.inc.php';
        $myU1 = new Xupload("file1",1);
        $myU1->ignore_cls_arr_ext_accepted = true; // Allow all!

        $myU1->setDir(UPLOAD_PATH);
        clearstatcache();
        $_real_filename_from_upload = $myU1->get_filename();
        $filename = prepare_available_filename($_real_filename_from_upload, UPLOAD_PATH);
        $file_extension = get_extension($_real_filename_from_upload);


        $allowedExtensions = ['jpg','png','gif','tif','bmp','webp','jpeg'];

        $deniedExtensions = [
            'php','php1','php2','php3','php4','shtml','pl','cgi','asp'
        ];
        if(in_array($file_extension, $deniedExtensions)){
            $msg = $filename['type'] . ' er ikke en gyldig filtype, av sikkerhetsmessige årsaker får du ikke adgang å laste opp denne filen.<br>';
            $msg .= 'Om du ønsker å legge opp filen i Easy CMS kan du pakke den ned som ZIP å laste den opp.';
            $_SESSION['error_msg'] = $msg;
            header('Location: ' . $callbackURL . '?error');
            exit;
        } else if(in_array($file_extension, $allowedExtensions)){

            // Make sure we don't have jpeg files
            $file_extension = str_replace('jpeg','jpg',strtolower($file_extension));

            $myU1->changeFilename($filename['file']);
            $myU1->xCopy($filename['file']);
            if (!$myU1->show_progressStatus()){

                // Make sure we only have png or jpg files in the database, convert anything else into jpg
                $convertExtensions = ['gif','tif','bmp','webp'];
                if(in_array($file_extension, $convertExtensions)){
                    convertImage(UPLOAD_PATH . '/' . $filename['file'], UPLOAD_PATH . '/' . pathinfo($filename['file'], PATHINFO_FILENAME) . '.jpg');
                    unlink(UPLOAD_PATH . '/' . $filename['file']);
                    logfile('Unlink: ' . UPLOAD_PATH . '/' . $filename['file']);
                    $filename['file'] = pathinfo($filename['file'], PATHINFO_FILENAME) . '.jpg';
                    $file_extension = 'jpg';
                }

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
        } else {
            $msg = 'Filtypen (' . $file_extension . ') du har forsøkt laste opp er ikke støttet, forsøk igjen med å leaste opp et JPG eller PNG bilde.';
            $_SESSION['error_msg'] = $msg;
            header('Location: upload.php?error');
            exit;
        }

    } else {
        // an empty submit
        $display_empty_toast_alert = true;
    }

}

if($lang == 'en'){
    $txts = [
        'pageTitle' => 'Upload image',
        'supTitle' => 'Upload image',
        'title' => 'Upload image',
        'paragraph' => 'Snap a photo of your fridge and upload it here.',
        'submit' => 'Upload image',
    ];
} else {
    $txts = [
        'pageTitle' => 'Last opp bilde',
        'supTitle' => 'Last opp bilde',
        'title' => 'Last opp bilde',
        'paragraph' => 'Ta bilde av kjøleskapet ditt og last det opp her.',
        'submit' => 'Last opp bilde',
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
<link rel="manifest" href="_manifest.json.php" data-pwa-version="<?=$PWA_APP_VER?>">
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>

<body class="theme-light">

<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>






<div id="page" data-show-menus="modalMenu-logout">

    <div class="header header-fixed header-logo-center header-auto-show">
        <a href="upload.php" class="header-title"><?=$txts['pageTitle']?></a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(3)?>

    <div class="page-title page-title-fixed">
        <h1><?=$txts['pageTitle']?></h1>
        <?=HTML_HEADER('page-title-fixed')?>
        
    </div>
    <div class="page-title-clear"></div>

    <div class="page-content">



        <div class="card card-style shadow-xl">
            <div class="content">

                <p class="color-highlight font-600 mb-n1"><?=$txts['supTitle']?></p>
                <h1 class="font-24 font-700 mb-2"><?=$txts['title']?> <i class="fa fa-star mt-n2 font-30 color-yellow-dark float-end me-2 scale-box"></i></h1>
                <p class="mb-1">
                    <?=$txts['paragraph']?>
                </p>


                <form action="upload.php" method="post" enctype="multipart/form-data" style="text-align:center" id="uploadForm">

                    <input type="hidden" name="bonus_conf" id="bonus_conf" value="">
                    <input name="file1" type="file" accept="image/*" class="" id="file1_inp">

                    <br><br>
                    <button type="submit" class="btn btn-m btn-full mb-3 rounded-0 text-uppercase font-900 shadow-s bg-red-light" style="margin:0 auto;" id="file1_sub"><?=$txts['submit']?></button>
                </form>

            </div>
        </div>




<!-- splide --><div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-1"><div class="splide__track"><div class="splide__list"><div class="splide__slide mx-3">
<!-- /splide --></div><div class="splide__slide mx-3" style="padding: 0 40px;">
                <p class="color-highlight font-600 mb-n1">Mystery config</p>
                <h1 class="font-24 font-700 mb-2">Sliders without context </h1>
                <form class="range-slider bottom-15 range-slider-icons">
                    <i class="fa fa-range-icon-1 fa-volume-down color-theme"></i>
                    <i class="fa fa-range-icon-2 fa-volume-up color-theme"></i>
                    <input name="vol" class="ios-slider" type="range" value="<?=getSessionKeyIfOr('seed','vol',true,0)?>">
                </form>
                <form class="range-slider bottom-15 range-slider-icons">
                    <i class="fa-range-icon-1 fas color-theme"></i>
                    <i class="fa-range-icon-2 fas color-theme"></i>
                    <input name="power" class="classic-slider" type="range" value="<?=getSessionKeyIfOr('seed','power',true,50)?>" style="margin-top: 10px;">
                </form>
<!-- /splide --></div></div></div></div>



        <div data-menu-load="<?=$appConf['menuFooter']?>"></div>
    </div>
    <!-- Page content ends here-->

    <div id="toast-1" class="toast toast-tiny toast-top bg-blue-dark" data-bs-delay="1000" data-bs-autohide="true"><i class="fa fa-info me-3"></i>No image</div>

    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-components"></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div>

</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js?<?=$html_NoCache_Version?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

    var fileInput = document.getElementById('file1_inp');
    var submitButton = document.getElementById('file1_sub');
    var bonus_conf = document.getElementById('bonus_conf');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            submitButton.style.display = 'block';
        } else {
            submitButton.style.display = 'none';
        }
    });

    document.getElementById('uploadForm').addEventListener('submit', function() {
        submitButton.disabled = true;
        bonus_conf.value = check_bonus();
    });

    submitButton.style.display = 'none';

});
</script>
<script>
// Select all forms with the specified criteria
function check_bonus(){
    const forms = document.querySelectorAll('#page .page-content form.range-slider');
    const vals = [];
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name;
            const value = input.value;
            vals.push({ name, value });
        });
    });
    const jsonString = JSON.stringify(vals);
    return jsonString;
}

<?php
if(!empty($display_empty_toast_alert)){
    echo "var toastID = document.getElementById('toast-1'); toastID = new bootstrap.Toast(toastID); toastID.show();" . "\n";
}
?>
</script>

<?php
output_session_notification();
?>

<?php
    que_modal_tpl('login','logout');
    echo write_modal_tpls();
?>

</body><?php
ob_end_flush();
?>