<?php


ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';
require_once 'func.login.php';


if( !empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['message']) ){
    // currate the fields
    $sql = new sqlbuddy;
    $sql->que('created', 'now()', 'raw');
    $sql->que('name', $_POST['name'], 'str');
    $sql->que('email', $_POST['email'], 'str');
    $sql->que('tel', $_POST['tel'], 'str');
    $sql->que('message', $_POST['message'], 'text');

    $mysqli->query( $sql->build('insert', $kista_dp . 'contact_form') );
    $sid = $mysqli->insert_id;
    $_SESSION['info_msg'] = 'Din tilbakemelding har blitt registrert, takk for din interesse.';
    header("Location: contact.php");
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
<link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="contact.php" class="header-title">Contact</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(4)?>

    <div class="page-content header-clear-medium">


        <div class="card card-style">
            <div class="content">
                <h3>Contact</h3>
                
                <div data-splide='{"autoplay":false}' class="splide single-slider slider-no-arrows slider-no-dots" id="user-slider-1">
                    <div class="splide__track">
                        <div class="splide__list">
                            <div class="splide__slide mx-3">
                                <div class="d-flex">
                                    <div><img src="images/avatars/steinhaug.png" class="me-3 rounded-circle shadow-l" width="50"></div>
                                    <div>
                                        <h5 class="mt-1 mb-0">Kim Steinhaug</h5>
                                        <p class="font-10 mt-n1 color-red-dark">Chief Propellorhead</p>
                                    </div>
                                    <div class="ms-auto"><span class="slider-next badge bg-red-dark mt-2 p-2 font-8">TAP FOR MERE</span></div>
                                </div>
                            </div>
                            <div class="splide__slide mx-3">
                                <div class="d-flex">
                                    <div>
                                        <h5 class="mt-1 mb-0">Kim Steinhaug</h5>
                                        <p class="font-10 mt-n1 color-red-dark">Chief Propellorhead</p>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="tel:41323236" class="icon icon-xs rounded-circle shadow-l bg-phone"><i class="fa fa-phone"></i></a>
                                        <a href="https://facebook.com/steinhaug" class="icon icon-xs rounded-circle shadow-l bg-facebook me-2 ms-2"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/steinhaug" class="icon icon-xs rounded-circle shadow-l bg-twitter"><i class="fab fa-twitter"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>      
                    </div>
                </div>
                
            </div>
        </div>



        <div class="card card-style">
            <div class="content mb-0">
                <p class="text-center pt-3">
                    <i class="fa fa-quote-left fa-4x color-green-dark"></i>
                </p>
                <h1 class="text-center font-700 pb-3">My shit is quotable</h1>
                <p class="text-center pb-4 color-highlight">- Kim Steinhaug 🤣</p>
            </div>
        </div>



        <div class="card card-style">
            <div class="content mb-0">        
                <h3>Kontakt skjema</h3>
                <p>
                    Noe du lurer på eller noe du vil spørre meg om, bruk skjemaet under.
                </p>

                <form action="contact.php" method="post" id="contactForm">

                <div class="input-style has-borders has-icon validate-field mb-4">
                    <i class="fa fa-user"></i>
                    <input name="name" type="text" class="form-control validate-name" id="fname" placeholder="Name">
                    <label for="form1" class="color-highlight">Name</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                
                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input name="email" type="email" class="form-control validate-text" id="femail" placeholder="Email">
                    <label for="form2" class="color-highlight">Email</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>

                <div class="input-style has-borders no-icon validate-field mb-4">
                    <input name="tel" type="text" class="form-control validate-text" id="ftel" placeholder="Phone">
                    <label for="form4" class="color-highlight">Phone</label>
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                    <em>(required)</em>
                </div>
                
                <div class="input-style has-borders no-icon mb-4">
                    <textarea id="fmessage" placeholder="Enter your message" name="message"></textarea>
                    <label for="form7" class="color-highlight">Enter your Message</label>
                    <em class="mt-n3">(required)</em>
                </div>


                <div class="row mb-0">
                    <div class="col-4 pe-1">
                    </div>
                    <div class="col-4 ps-1 pe-1">
                        <a href="#" id="submitButton" class="btn btn-3d btn-m btn-full mb-3 rounded-0 text-uppercase font-700 shadow-s border-blue-dark bg-blue-light" style="display:block;">Send skjema</a>
                    </div>
                    <div class="col-4 ps-1">
                    </div>
                </div>

                </form>

            </div>
        </div>
     




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


<div id="error-2" data-dismiss="notification-4" data-bs-delay="1000" data-bs-autohide="true" class="notification bg-red-dark shadow-xl opacity-95 fade hide">
    <div class="toast-body color-white p-3">
        <h1 class="ms-0 ps-0 pb-2 mt-0 color-white">Form error</h1>
        <p id="errorMessage"></p>
    </div>
</div>
<script>
// JavaScript
document.getElementById("submitButton").addEventListener("click", function(event) {
    event.preventDefault(); // Prevent form submission
    
    // Get form fields
    var name = document.getElementById("fname").value.trim();
    var email = document.getElementById("femail").value.trim();
    var message = document.getElementById("fmessage").value.trim();
    
    // Validate form fields
    if (name.length < 5 || message.length < 5) {
        document.getElementById("errorMessage").textContent = "Please fill in all fields correctly.";
        var toastID = document.getElementById('error-2');
        toastID = new bootstrap.Toast(toastID);
        toastID.show();    
        return;
    }

    // Validate form fields
    if (email === "" || !validateEmail(email)) {
        document.getElementById("errorMessage").textContent = "Please check your email adress.";
        var toastID = document.getElementById('error-2');
        toastID = new bootstrap.Toast(toastID);
        toastID.show();    
        return;
    }
    
    // Submit the form if validation passes
    document.getElementById("contactForm").submit();
});

// Function to validate email format
function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
</script>

</body><?php
ob_end_flush();
?>