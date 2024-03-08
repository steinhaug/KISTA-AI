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
        <a href="index.html" class="header-title">Privacy Policy</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER(4)?>

    <div class="page-content header-clear-medium">

            <div class="card card-style">
                <div class="content">
                    <p class="mb-n1 color-highlight font-600">Legal</p>
<?php
$privacy = '### Privacy Policy for KISTA AI

This Privacy Policy governs the manner in which KISTA AI collects, uses, maintains, and discloses information collected from users ("Users") of the KISTA AI website ("Website"). This Privacy Policy applies solely to the information collected by KISTA AI.

##### 1. Information Collection

a. Uploaded Images: KISTA AI collects images uploaded by Users for the purpose of providing the service offered on the Website. These images are not stored after the analysis is complete and are used solely to improve the service.

##### 2. Use of Information

a. Improvement of Service: Images uploaded by Users may be used to improve the service provided by KISTA AI. However, no personally identifiable information is collected or stored.

##### 3. Data Sharing

a. Third Parties: KISTA AI does not share or disclose any information collected from Users, including uploaded images, with any third parties.

##### 4. Data Security

a. Protection Measures: KISTA AI employs appropriate data collection, storage, and processing practices and security measures to protect against unauthorized access, alteration, disclosure, or destruction of Users\' personal information.

##### 5. Disclaimer

a. Entertainment Purposes: The data provided by KISTA AI through the analysis of uploaded images is for entertainment purposes only and should not be considered as professional advice or factual information. KISTA AI does not guarantee the accuracy of the data.

##### 6. Children\'s Privacy

a. Age Requirement: KISTA AI does not knowingly collect any personally identifiable information from children under the age of 13. The service provided by KISTA AI is intended for individuals 13 years of age and older.

##### 7. Changes to this Privacy Policy

a. Modification: KISTA AI reserves the right to update or change this Privacy Policy at any time. Users are encouraged to review this Privacy Policy periodically for any changes.

##### 8. Contact Information

a. Questions: If Users have any questions about this Privacy Policy or the practices of KISTA AI, they may contact us at [Contact Email].

By using the KISTA AI Website, Users acknowledge that they have read, understood, and agree to be bound by this Privacy Policy.';
$Parsedown = new Parsedown();
echo $Parsedown->text( $privacy );
?>

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
output_session_notification();
?>

</body><?php
ob_end_flush();
?>