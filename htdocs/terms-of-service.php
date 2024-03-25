<?php


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
<link rel="apple-touch-icon" sizes="180x180" href="<?=$PWA_LANG['ico_folder']?>/icon-192x192.png">
<?=$PWA_LANG['highlight']?>
</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center">
        <a href="index.php" class="header-title">Terms of Service</a>
        <?=$HTML_HEADER('header-fixed')?>
    </div>

    <?=$HTML_FOOTER(4)?>

    <div class="page-content header-clear-medium">

            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">Legal v1.0.1</p>
<?php
$terms = '### ' . strtoupper($PWA_APP_NAME) . ' - Terms of Service

These Terms of Service ("Terms") govern your access to and use of the ' . strtoupper($PWA_APP_NAME) . ' website ("Website") and any services provided therein. Please read these Terms carefully before using the Website.

##### 1. Acceptance of Terms

By accessing or using the Website, you agree to be bound by these Terms. If you do not agree to these Terms, please do not use the Website.

##### 2. Use of Services

a. Uploads: Users may upload images to the Website to utilize the provided service.  
b. Data Collection: We do not collect any personal data other than the images uploaded to the Website. Images uploaded by users may be used to improve the service, but will not be shared with or made available to any third parties.  
c. Service Accuracy: The data provided by the analysis of uploaded images is for entertainment purposes only and should not be considered as professional advice or factual information. We do not guarantee the accuracy of the data and it may occasionally result in offensive material due to unforeseen circumstances.  

##### 3. User Conduct

a. Responsibility: Users are responsible for the content they upload to the Website.  
b. Prohibited Content: Users must not upload any content that is illegal, obscene, defamatory, or infringes on the rights of others.  
c. Security: Users must not attempt to interfere with the security or integrity of the Website or access any data not intended for them.  

##### 4. Intellectual Property

a. Ownership: All intellectual property rights in the Website and its content belong to ' . strtoupper($PWA_APP_NAME) . '.  
b. Use of Content: Users may not reproduce, distribute, or modify any content from the Website without prior consent.  

##### 5. Limitation of Liability

a. No Warranty: The Website is provided "as is" and ' . strtoupper($PWA_APP_NAME) . ' makes no warranties, express or implied, regarding its accuracy, reliability, or suitability for any purpose.  
b. Limitation of Liability: ' . strtoupper($PWA_APP_NAME) . ' shall not be liable for any direct, indirect, incidental, special, or consequential damages arising out of the use of or inability to use the Website.  

##### 6. Indemnification

Users agree to indemnify and hold harmless ' . strtoupper($PWA_APP_NAME) . ', its officers, directors, employees, and agents from any claims, liabilities, damages, or expenses arising out of their use of the Website or violation of these Terms.

##### 7. Modifications

' . strtoupper($PWA_APP_NAME) . ' reserves the right to modify or update these Terms at any time without prior notice. Users are encouraged to review these Terms regularly for any changes.

##### 8. Governing Law

These Terms shall be governed by and construed in accordance with the laws of Norway, without regard to its conflict of laws principles.

##### 9. Contact Information

If you have any questions or concerns about these Terms, please contact us at steinhaug@gmail.com.

By using the ' . strtoupper($PWA_APP_NAME) . ' Website, you acknowledge that you have read, understood, and agree to be bound by these Terms.';
$Parsedown = new Parsedown();
echo $Parsedown->text( $terms );
?>
                </div>
            </div>


        <div data-menu-load="<?=$appConf['menuFooter' . $_menuSuffix]?>"></div>
    </div>
    <!-- Page content ends here-->
    
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
<script type="text/javascript" src="scripts/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="scripts/avatarify-app.js?<?=$html_NoCache_Version?>"></script>

<?php
output_session_notification();
?>

</body><?php
ob_end_flush();
?>