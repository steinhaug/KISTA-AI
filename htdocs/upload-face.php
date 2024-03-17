<?php
if(!(in_array($_SERVER['SERVER_NAME'],['kista-ai.steinhaug.no','kista-ai.local']))) { http_response_code(404); exit; }

ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';

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
        <a href="error.php" class="header-title">AI Avatar Maker</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(3)?>


    <div class="page-content header-clear-medium">


            <div class="card card-style ms-0 me-0 rounded-0">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">The Awesome</p>
                    <h1>Create your AI Avatar in 1,2,3</h1>
                    <p>Select your style and upload your image, let the AI do the rest!</p>
                </div>
            </div>      

<?php
$styles = [
    '-9c3a6E911o.png','advance-sketch-3.png','advance-sketch-5.png','angrybird.png','anime-1.png','antheia4.png','art-1.png',
    'art-2.png','art-from-renaissance.png','blyant.png','graffiti-art.png','great-wave-off-kanagawa-crop.png','karrikatur-1.png',
    'kim-jong-1.png','kim-jung-2.png','lennon-blue.png','maleri.png','mona-lisa.png','pastel.png','putin-1.png','sketch.png','starry-night.png',
    'stefano_phen.png','tattoo-1.png','tattoo-2-3.png','van-gogh.png','van-gogh-2.png','vladimir-putin.png','VrQAuHMYfxA.png'
];
?>
            <style>
            .transfStylesGrid .row a img {
                border: 10px solid #fff;
            }
            .transfStylesGrid .row a.selStyleTrans img {
                border: 10px solid red;
            }
            </style>
            <div class="card card-style ms-0 me-0 rounded-0 mb-0">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">AI Avatar</p>
                    <h1>Select style:</h1>
                    <p class="mb-3">
                        Swipe to browse the available styles, pick one.
                    </p>

                    <div data-splide='{"autoplay":false}' class="transfStylesGrid splide single-slider slider-arrows slider-no-dots" id="single-slider-1"><div class="splide__track"><div class="splide__list">
<?php
$i = 0;
foreach( $styles as $style ){

        $modulus = ($i % 4) + 1;
        switch($modulus){
            case 1:
            echo '
                <div class="splide__slide">
                    <div class="row row-cols-2 px-1 mb-0">
                        <a class="col p-2" href="#" data-style="' . $style . '">
                            <img src="/images/style-transfers/' . $style . '" alt="img" class="img-fluid rounded-sm shadow-xl">
                        </a>';
                break;
            case 2:
            echo '
                        <a class="col p-2" href="#" data-style="' . $style . '">
                            <img src="/images/style-transfers/' . $style . '" alt="img" class="img-fluid rounded-sm shadow-xl">
                        </a>
                    </div>';
                break;
            case 3:
            echo '
                    <div class="row row-cols-2 px-1 mb-0">
                        <a class="col p-2" href="#" data-style="' . $style . '">
                            <img src="/images/style-transfers/' . $style . '" alt="img" class="img-fluid rounded-sm shadow-xl">
                        </a>';
                break;
            case 4:
            echo '
                        <a class="col p-2" href="#" data-style="' . $style . '">
                            <img src="/images/style-transfers/' . $style . '" alt="img" class="img-fluid rounded-sm shadow-xl">
                        </a>
                    </div>
                </div>';
                break;
            default:
                break;
        }
        $i++;
}

$modulus = $i % 4;
switch($modulus) {
    case 1:
        echo '</div></div>';
        break;
    case 2:
        echo '</div>';
        break;
    case 3:
        echo '</div>';
        break;
}

?>

                    </div></div></div>
                   
                </div>
            </div>
 



        <form action="contact.php" method="post" id="contactForm">
            <input type="hidden" name="bonus_conf" id="bonus_conf" value="">
            <input name="selected_style_transfer" type="hidden" class="form-control" id="sel_style" placeholder="">

            <div class="card card-style ms-0 me-0 rounded-0">
                <div class="content">



                    <h1 class="mt-4">Select your image:</h1>
                    <p class="mb-3">
                        Select a half-body portrait and you are ready for some AI-Goodiness.
                    </p>


                    <div class="row" hidden><div class="col">
                        <input name="file1" type="file" accept="image/*" class="" id="file1_inp">
                    </div></div>
                    <div class="row mt-4"><div class="col" style="text-align:center">
                        <div id="upload_preview" hidden><a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a><img /></div>
                        <a href="#" id="file1_inp_extraBtn" class="btn btn-xxl mb-3 rounded-s text-uppercase font-700 shadow-s bg-highlight">Select image</a>
                    </div></div>



                </div>
            </div>


            <div class="card card-style">
                <div class="content mb-0">
                    <div class="row mt-4"><div class="col" style="text-align:center">
                        <button id="submitBtn" class="btn btn-xxl mb-3 rounded-s text-uppercase font-700 shadow-s bg-green-dark" disabled="">Upload and create my new AI Images</button>
                    </div></div>
                </div>
            </div>


        </form>



        <div data-menu-load="<?=$appConf['menuFooter']?>"></div>
    </div>
    <!-- Page content ends here-->
    
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

<script>

document.addEventListener("DOMContentLoaded", function() {
  // Select all the links within the .transfStylesGrid div
  var links = document.querySelectorAll('.transfStylesGrid a');

  // Function to handle link click
  function handleLinkClick(event) {
    event.preventDefault(); // Disable the link's default action

    // Remove .selStyleTrans class from all links
    links.forEach(function(link) {
      link.classList.remove('selStyleTrans');
    });

    // Add .selStyleTrans class to the clicked link
    this.classList.add('selStyleTrans');

    // Update the value of the #sel_style input field
    var styleValue = this.getAttribute('data-style');
    document.getElementById('sel_style').value = styleValue;
  }

  // Attach the click event listener to each link
  links.forEach(function(link) {
    link.addEventListener('click', handleLinkClick);
  });
});

document.addEventListener("DOMContentLoaded", function() {
  var extraBtn = document.getElementById('file1_inp_extraBtn');
  var fileInput = document.getElementById('file1_inp');
  var submitBtn = document.getElementById('submitBtn');
  var previewContainer = document.getElementById('upload_preview');
  var previewImage = previewContainer.querySelector('img');
  var closeLink = document.querySelector('#upload_preview a.close-menu');

  // Task 1: Trigger file input click and handle image selection
  extraBtn.addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default anchor behavior
    fileInput.click(); // Trigger the file input click
  });

  fileInput.addEventListener('change', function(event) {
    var files = fileInput.files;
    if (files && files[0]) {
      var fileReader = new FileReader();
      
      fileReader.onload = function(e) {
        previewImage.src = e.target.result;
        previewContainer.hidden = false; // Show the preview container
        extraBtn.setAttribute('hidden', true); // Hide the extra button
        submitBtn.disabled = false; // Enable the submit button
      };

      fileReader.readAsDataURL(files[0]);
    }
  });

  // Task 2: Reset input and adjust buttons when close link is clicked
  closeLink.addEventListener('click', function(event) {
    event.preventDefault();
    fileInput.value = ''; // Reset the file input
    previewContainer.hidden = true; // Hide the preview container
    extraBtn.removeAttribute('hidden'); // Show the extra button
    submitBtn.disabled = true; // Disable the submit button
  });
});



</script>


</body><?php
ob_end_flush();
?>