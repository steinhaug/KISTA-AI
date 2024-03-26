<?php
use Intervention\Image\ImageManagerStatic as Image;

ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

$callbackURL = 'upload-face.php';
$continueURL = 'processing-face.php';

if( isset( $_POST ) && is_array( $_POST ) && isset($_SERVER['CONTENT_TYPE']) ) {

    $bonusConf = [];
    $_bonusConf = json_decode($_POST['bonus_conf'], 1);
    if(isset($_bonusConf[2])) $bonusConf[$_bonusConf[2]['name']] = $_bonusConf[2]['value']; else $bonusConf['vol'] = 0;
    if(isset($_bonusConf[3])) $bonusConf[$_bonusConf[3]['name']] = $_bonusConf[3]['value']; else $bonusConf['power'] = 50;

    $file1 = $_FILES['file1']['tmp_name'];
    $file1_name = $_FILES['file1']['name'];
    $file1_size = $_FILES['file1']['size'];

    $_conf = [
        'steps' => $_POST['conf']['steps'] ?? 20,
        'ip_adapter_noise' => $_POST['conf']['ipAdapterNoise'] ?? 75,
        'ip_adapter_weight' => $_POST['conf']['ipAdapterWeight'] ?? 50,
        'instant_id_strength' => $_POST['conf']['instantIdStrength'] ?? 70,
    ];
    $conf = [
        'steps' => make_sure_value_fits_specs($_conf['steps'],                              [20, 'int', 10,  50],   [])
        'ip_adapter_noise' => make_sure_value_fits_specs($_conf['ip_adapter_noise'],        [75, 'int', 10, 100], ['d',100])
        'ip_adapter_weight' => make_sure_value_fits_specs($_conf['ip_adapter_weight'],      [50, 'int', 10, 100], ['d',100])
        'instant_id_strength' => make_sure_value_fits_specs($_conf['instant_id_strength'],  [70, 'int', 10, 100], ['d',100])
    ];


    if(!empty($_FILES['file1']['size'])){

       $upload_path = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r';

        require APPDATA_PATH . '/XUploadFile.inc.php';
        $myU1 = new Xupload("file1",1);
        $myU1->ignore_cls_arr_ext_accepted = true; // Allow all!

        $myU1->setDir($upload_path);
        clearstatcache();
        $_real_filename_from_upload = $myU1->get_filename();
        $filename = prepare_available_filename($_real_filename_from_upload, $upload_path);
        $file_extension = get_extension($_real_filename_from_upload);

        $allowedExtensions = ['jpg','png','gif','tif','bmp','webp','jpeg'];

        $deniedExtensions = [
            'php','php1','php2','php3','php4','shtml','pl','cgi','asp'
        ];
        if(in_array($file_extension, $deniedExtensions)){
            $msg = $filename['type'] . ' er ikke en gyldig filtype, av sikkerhetsmessige årsaker får du ikke adgang å laste opp denne filen.<br>';
            $_SESSION['error_msg'] = $msg;
            header('Location: ' . $callbackURL . '?error');
            exit;
        } else if(in_array(strtolower($file_extension), $allowedExtensions)){

            // Make sure we don't have jpeg files
            $file_extension = str_replace('jpeg','jpg',strtolower($file_extension));

            $myU1->changeFilename($filename['file']);
            $myU1->xCopy($filename['file']);
            if (!$myU1->show_progressStatus()){

                // Make sure we only have png or jpg files in the database, convert anything else into jpg
                $convertExtensions = ['gif','tif','bmp','webp'];
                if(in_array($file_extension, $convertExtensions)){
                    convertImage($upload_path . '/' . $filename['file'], $upload_path . '/' . pathinfo($filename['file'], PATHINFO_FILENAME) . '.jpg');
                    unlink($upload_path . '/' . $filename['file']);
                    logfile('Unlink: ' . $upload_path . '/' . $filename['file']);
                    $filename['file'] = pathinfo($filename['file'], PATHINFO_FILENAME) . '.jpg';
                    $file_extension = 'jpg';
                }

                $filehash = hash_file('sha256', $upload_path . '/' . $filename['file']);

                $sql = new sqlbuddy;

                $sql->que('uuid', generateUuid4(),'string');
                $sql->que('replicate_id', '','int');
                $sql->que('replicate_task', 2,'int');
                $sql->que('user_id', $USER_ID,'int');
                $sql->que('created', 'NOW()','raw');
                $sql->que('updated', 'NULL','raw');
                $sql->que('stylename', '', 'string');
                $sql->que('realname', $_real_filename_from_upload, 'string');
                $sql->que('filehash', $filehash, 'string');
                $sql->que('filename', $filename['file'], 'string');
                $sql->que('extension', $file_extension, 'string');
                $sql->que('filesize', $file1_size, 'int');
                $sql->que('thumbnail', '', 'string');
                $sql->que('status', 'start', 'string');
                $sql->que('data', '', 'string');
                $sql->que('log', '', 'string');
                $sql->que('error', '', 'string');
                $mysqli->query( $sql->build('insert', $kista_dp . "replicate__uploads") );
                $reid = $mysqli->insert_id;
                addSessionTask( ['reid'=>$reid, 'rtask'=>2, 'status'=>'start', 'progress'=>0] );
                header('Location: ' . $continueURL . '?reid=' . $reid);
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
        <a href="error.php" class="header-title">get your StickerfAIse</a>
        <?=$HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER_AVATAR(3)?>


    <style>
    .transfStylesGrid .row a img {
        border: 10px solid #fff;
    }
    .transfStylesGrid .row a.selStyleTrans img {
        border: 10px solid red;
    }
    </style>

    <div class="page-content header-clear-medium bg-green-dark">


            <div class="card card-style ms-0 me-0 rounded-0 bg-grass-dark">
                <div class="content">
                    <p class="mb-n1 color-white font-600">This is where you get your</p>
                    <h1 class="color-white">StickerfAIse</h1>
                    <p class="color-black">Select a photo of yourself to create stickers from</p>
                </div>
            </div>      


        <form action="upload-stickerfaise.php" method="post" enctype="multipart/form-data" id="faceForm">

            <input type="hidden" name="bonus_conf" id="bonus_conf" value="">
            <input type="hidden" name="selected_style_transfer" class="form-control" id="sel_style">




            <div class="card card-style ms-0 me-0 rounded-0 mb-4 bg-green-dark" id="step1">
                <div class="content">
                    <p class="mb-n1 color-white opacity-50 font-600">StickerfAIse creator</p>
                    <h1 class="mt-1 color-white">1: Chose image</h1>
                    <p class="mb-3 color-white opacity-60">
                        Select a half-body portrait and you are ready for some AI-Goodiness.
                    </p>


                </div>

                <div class="card card-style" id="step1-file">
                    <div class="content">

                        <div class="row" hidden><div class="col">
                            <input name="file1" type="file" accept="image/*" class="" id="file1_inp">
                        </div></div>
                        <div class="row mt-4 mb-4"><div class="col" style="text-align:center">
                            <div id="upload_preview" hidden><a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a><img /></div>
                            <a href="#" id="file1_inp_extraBtn" class="btn btn-xxl rounded-s text-uppercase font-700 shadow-s bg-grass-light">Select image</a>
                        </div></div>

                    </div>
                </div>


                <div class="card card-style bg-teal-dark bg-transparent">
                    <div class="content">

                    <p class="mb-n1 color-white opacity-50 font-600">Configurable settings</p>
                    <h1 class="mt-1 color-white">2: Settings</h1>

                        <div class="row mb-0">
                            <div class="col-4 text-center">instant id strength</div>
                            <div class="col-4 text-center">ip adapter weight</div>
                            <div class="col-4 text-center">ip adapter noise</div>
                        </div>
                        <div class="row steppers">
                            <div class="col-4">
                                <div class="mx-auto">
                                    <div class="stepper rounded-s float-start">
                                        <a href="#" class="stepper-sub"><i class="fa fa-minus color-theme opacity-40"></i></a>
                                        <input name="conf[instantIdStrength]" type="number" min="1" max="100" value="70" data-step="2">
                                        <a href="#" class="stepper-add"><i class="fa fa-plus color-theme opacity-40"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mx-auto">
                                    <div class="stepper rounded-s float-none">
                                        <a href="#" class="stepper-sub"><i class="fa fa-minus color-red-dark"></i></a>
                                        <input name="conf[ipAdapterWeight]" type="number" min="1" max="100" value="50" data-step="2">
                                        <a href="#" class="stepper-add"><i class="fa fa-plus color-green-dark"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mx-auto">
                                    <div class="stepper rounded-s float-end">
                                        <a href="#" class="stepper-sub"><i class="fa fa-minus color-red-dark"></i></a>
                                        <input name="conf[ipAdapterNoise]" type="number" min="1" max="100" value="75" data-step="2">
                                        <a href="#" class="stepper-add"><i class="fa fa-plus color-green-dark"></i></a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-0">
                            <div class="col-2 text-left">10</div>
                            <div class="col-2 text-center">17</div>
                            <div class="col-2 text-center">24</div>
                            <div class="col-2 text-center">36</div>
                            <div class="col-2 text-center">43</div>
                            <div class="col-2 text-right">50</div>
                        </div>
                        <div class="range-slider">
                            <input class="classic-slider" type="range" min="10" max="50" value="20" step="1" name="conf[steps]">
                        </div>
                        <div class="row mb-0"><div class="col-12 text-center">steps</div></div>

                    </div>
                </div>

                <div class="card card-style" id="step2">
                    <div class="content">
                        <div class="row mt-4 mb-4"><div class="col" style="text-align:center">
                            <button id="submitBtn" type="submit" class="btn btn-xxl rounded-s text-uppercase font-700 shadow-s bg-gray-dark" disabled="">Upload your image and get your StickerfAIse</button>
                        </div></div>
                    </div>
                </div>

            </div>



        </form>



        <div data-menu-load="<?=$appConf['menuFooter' . $_menuSuffix]?>"></div>
    </div>
    <!-- Page content ends here-->
    
    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html?<?=$html_NoCache_Version?>" data-menu-width="280" _data-menu-active=""></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 

    <?php
    que_modal_tpl('login','logout');
    echo write_modal_tpls();
    ?>

</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js.php?<?=$html_NoCache_Version?>"></script>
<script type="text/javascript" src="scripts/jquery-3.7.1.min.js" defer="defer"></script>
<script type="text/javascript" src="scripts/avatarify-app.js?<?=$html_NoCache_Version?>" defer="defer"></script>

<script>

document.addEventListener("DOMContentLoaded", function() {
  var extraBtn = document.getElementById('file1_inp_extraBtn');
  var fileInput = document.getElementById('file1_inp');
  var submitBtn = document.getElementById('submitBtn');
  var previewContainer = document.getElementById('upload_preview');
  var previewImage = previewContainer.querySelector('img');
  var closeLink = document.querySelector('#upload_preview a.close-menu');

  // Task 1: Trigger file input click and handle image selection
  extraBtn.addEventListener('click', function(event) {
    event.preventDefault();
    fileInput.click(); 
  });

  fileInput.addEventListener('change', function(event) {
    var files = fileInput.files;
    if (files && files[0]) {
      var fileReader = new FileReader();
      
      fileReader.onload = function(e) {
        previewImage.src = e.target.result;
        previewContainer.hidden = false; 
        extraBtn.setAttribute('hidden', true);

        $('#step1-file').addClass('bg-green-light')

        submitBtn.disabled = false;
        submitBtn.classList.remove('bg-gray-dark');
        submitBtn.classList.add('bg-grass-dark');
        var element = document.getElementById("step2");
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

      };

      fileReader.readAsDataURL(files[0]);
    }
  });

  // Task 2: Reset input and adjust buttons when close link is clicked
  closeLink.addEventListener('click', function(event) {
    event.preventDefault();
    fileInput.value = '';
    previewContainer.hidden = true;
    extraBtn.removeAttribute('hidden');
    submitBtn.disabled = true;
  });
});

</script>

<?php
output_session_notification();
?>

</body><?php
ob_end_flush();
?>