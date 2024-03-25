<?php


if( isset($_GET['action']) and ($_GET['action']=='logout') ){ require 'auto-logout-from-site.php'; exit; }
if( !empty($_GET['google_login']) ){ require 'auto-google-login-redirect.php'; exit; }



ob_start();
session_cache_expire(720);
session_start();

//logfile($_SERVER);

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

<meta property="og:site_name" content="Avatarify: Forvandle Selfien Din til et Mesterverk! - KISTA AI">
<meta property="og:title" content="Avatarify: Forvandle Selfien Din til et Mesterverk! - KISTA AI">
<meta property="og:url" content="https://avatarify.steinhaug.no/">
<meta property="og:type" content="website">
<meta property="og:image" content="https://avatarify.steinhaug.no/images/avatarify-screenshot.jpg">
<meta property="og:description" content="den ultimate lekeplassen for selvuttrykk, hvor dine selfies transcenderer det ordinære og entrer et rike av ekstraordinær kunstnerisk kreativitet. Med Avatarify Yourself blir portrettet ditt lerretet, og din fantasi setter grensene.">
<?=$PWA_LANG['highlight']?>
<style>
<?php
if($lang=='en'){
    $bgImgStyle = "background-image: url('/images/avatarify-main.jpg');";
    //.bg-fpnb { background-image: url(/images/pictures/refrigerator-700-en.png); }
} else {
    $bgImgStyle = "background-image: url('/images/avatarify-main.jpg');";
    // .bg-fpnb { background-image: url(/images/pictures/refrigerator-700-nb.png); }
}
?>
.bg-gradient-2 {
    background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 0%, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0.1) 60%, rgba(0, 0, 0, 0.8) 80%, black 100%) !important;
}

</style>

</head>

<body class="theme-light">

<div id="preloader" class="preloader-hide"><div class="spinner-border color-highlight" role="status"></div></div>

<div id="page">

    <div class="header header-fixed header-logo-center header-auto-show">
        <a href="index.php" class="header-title">Avatarify</a>
        <?=HTML_HEADER('header-fixed')?>
    </div>

    <?=HTML_FOOTER_AVATAR(2)?>

    <div class="page-content">



        <div class="card rounded-0 bg-fpnb" data-card-height="450" style="<?=$bgImgStyle?>">
            <div class="card-bottom text-end pe-3 pb-4 mb-4">
                <h1 class="color-white font-21 mb-n1">
                    Avatarify <sup>v1.0</sup>
                </h1>
                <p class="color-white font-12">
                    Another KISTA-AI doing AI stuff
                </p>
            </div>
            <div class="card-top mt-3 pb-5 ps-3">
                <a href="#" data-back-button class="icon icon-s bg-theme rounded-xl float-start me-3"><i class="fa color-theme fa-arrow-left"></i></a>
                <a href="#" data-menu="menu-share"  class="icon icon-s bg-theme rounded-xl float-end me-3"><i class="fa color-theme fa-share-alt"></i></a>
                <a href="#" data-menu="menu-heart"  class="icon icon-s bg-theme rounded-xl float-end me-2"><i class="fa color-red-dark fa-bookmark"></i></a>
            </div>
            <div class="card-overlay bg-gradient-2"></div>
        </div>
        

<?php if($lang=='en'){ ?>


            <div class="card card-style" style="margin-top:-100px; z-index:1">
                <div class="content mb-0">


            <div class="card card-style mx-0 mt-3" style="background-image: url(/images/pictures/groceries-bar.jpg);" data-card-height="100" id="install" hidden>
                <div class="card-center px-3 no-click">
                    <h1 class="color-white mb-n2 font-24">Avatarify App</h1>
                    <h5 class="color-white mt-n1 opacity-80 font-14">Installer KISTA-AI på telefonen</h5>
                </div>
                <div class="card-center">
                    <a href="#" class="float-end mx-3 gradient-highlight btn-s rounded-sm shadow-xl text-uppercase font-800" id="install-button">Install App</a>
                </div>
                <div class="card-overlay bg-black opacity-60"></div>
            </div>

                    <div class="row"><div class="col">
                        <p class="mb-n1 color-highlight font-600">KISTA-AI</p>
                        <h1>Avatarify Yourself</h1>
                        <p class="mb-3">
                            Welcome to the ultimate self-expression playground, where your selfies transcend the ordinary and enter the realm of extraordinary artistry. With Avatarify Yourself, your portrait becomes the canvas, and your imagination is the limit. Here's how it works:
                        </p>
                        <ol>
                            <li><b>Capture or Upload:</b> Snap a selfie or choose your favorite portrait from your gallery.</li>
                            <li><b>Select Your Style:</b> Browse our diverse gallery of themed images, each one a unique artistic universe waiting to be explored.</li>
                            <li><b>Transform:</b> With just a click, our advanced AI blends your photo with your selected theme, crafting a personalized masterpiece that mirrors your individuality.</li>
                            <li><b>Share and Impress:</b> Download your transformed avatar and share it with the world. Be it for your social media profiles, a fun project, or a digital keepsake, your Avatarify creation is sure to turn heads and spark conversations.</li>
                        </ol>
                        <p><a href="/upload-face.php" class="btn btn-full btn-s font-600 rounded-s gradient-highlight mt-1 float-start external-link">Avatarify Yourself</a></p>
                    </div></div>
                </div>
            </div>
<?php } else { ?>

            <div class="card card-style" style="margin-top:-100px; z-index:1">
                <div class="content mb-0">


            <div class="card card-style mx-0 mt-3" style="background-image: url(/images/pictures/groceries-bar.jpg);" data-card-height="100" id="install" hidden>
                <div class="card-center px-3 no-click">
                    <h1 class="color-white mb-n2 font-24">Avatarify App</h1>
                    <h5 class="color-white mt-n1 opacity-80 font-14">Installer KISTA-AI på telefonen</h5>
                </div>
                <div class="card-center">
                    <a href="#" class="float-end mx-3 gradient-highlight btn-s rounded-sm shadow-xl text-uppercase font-800" id="install-button">Install App</a>
                </div>
                <div class="card-overlay bg-black opacity-60"></div>
            </div>

                    <div class="row"><div class="col">
                        <p class="mb-n1 color-highlight font-600">KISTA-AI</p>
                        <h1>Avatarify: Forvandle Selfien Din til et Mesterverk</h1>
                        <p class="mb-3">
                            Velkommen til den ultimate lekeplassen for selvuttrykk, hvor dine selfies transcenderer det ordinære og entrer et rike av ekstraordinær kunstnerisk kreativitet. Med Avatarify Yourself blir portrettet ditt lerretet, og din fantasi setter grensene. Slik fungerer det:
                        </p>
                        <ol>
                            <li><b>Ta bilde eller last opp:</b> Knips en selfie eller velg ditt favorittportrett fra galleriet ditt.</li>
                            <li><b>Velg din stil:</b> Bla gjennom vårt mangfoldige galleri av temabilder, hvor hvert ett er et unikt kunstnerisk univers som venter på å bli utforsket.</li>
                            <li><b>Transformér:</b> Med bare ett klikk blander vår avanserte AI bildet ditt med det valgte temaet, og skaper et personlig mesterverk som speiler din individualitet.</li>
                            <li><b>Del og imponér:</b> Last ned ditt transformerte avatar og del det med verden. Enten det er for dine sosiale medieprofiler, et morsomt prosjekt, eller en digitalt minnesmerke, vil din Avatarify-kreasjon garantert vekke oppsikt og starte samtaler.</li>
                        </ol>
                        <p>
                            Legg ut på en kreativ reise med Avatarify Yourself og se dine selfies forvandles til fengslende kunstverk som forteller din historie på måter ord aldri kunne.
                        </p>
                        <p><a href="/upload-face.php" class="btn btn-full btn-s font-600 rounded-s gradient-highlight mt-1 float-start external-link">Avatarify Yourself</a></p>
                    </div></div>
                </div>
            </div>
<?php } ?>



<?php if($lang=='en'){ ?>
        <!-- margin top  negative value repesent how much you want the article to go over the above image-->
        <div class="card card-style card-full-left">
            <div class="content">

                <p class="mb-n1 color-highlight font-600">Magic Meal Maker</p>
                <h1>
                    Your Refrigerator's Secret Chef!
                </h1>
                <p>
                    Ever stare at your fridge, wondering what to cook? Say goodbye to culinary conundrums with Magic Meal Maker! Our innovative app transforms your fridge contents into delicious, easy-to-follow recipes at the touch of a button.
                </p>
                <h3>How It Works:</h3>
                <ol>
                    <li><b>Snap & Upload:</b> Simply take a picture of your refrigerator's contents and upload it to our app.</li>
                    <li><b>AI-Powered Analysis:</b> Our advanced AI chef quickly identifies your ingredients and suggests a variety of recipes you can create.</li>
                    <li><b>Cook & Enjoy:</b> Choose a recipe that tantalizes your taste buds, follow the step-by-step instructions, and dive into a delightful meal made from your own kitchen!</li>
                </ol>
                <p>
                    With Magic Meal Maker, unleash your inner chef, reduce food waste, and discover new ways to enjoy the ingredients you already have. Whether you're a cooking novice or a culinary wizard, our app is designed to inspire creativity and bring joy to your mealtime. Let's turn your fridge finds into your next gastronomic adventure!
                </p>
                <p><a href="https://magic-meal-maker.steinhaug.no" class="btn btn-full btn-s font-600 rounded-s gradient-highlight mt-1 float-start ">Go to: Magic Meal Maker App</a></p>
            </div>
        </div>
<?php } else { ?>
        <!-- margin top  negative value repesent how much you want the article to go over the above image-->
        <div class="card card-style card-full-left">
            <div class="content">

                <p class="mb-n1 color-highlight font-600">Magic Meal Maker</p>
                <h1>
                    Kjøleskapets hemmelige kokk!
                </h1>

                <p>
                    Har du noen gang stirret inn i kjøleskapet ditt, lurer på hva du skal lage? Si farvel til kulinariske gåter med Magic Meal Maker! Vår innovative app forvandler innholdet i kjøleskapet ditt til deilige, enkle å følge oppskrifter med et knappetrykk.
                </p>
                <h3>Hvordan det fungerer:</h3>
                <ol>
                    <li><b>Knips og Last opp:</b> Ta ganske enkelt et bilde av innholdet i kjøleskapet ditt og last det opp til appen vår.</li>
                    <li><b>AI-drevet Analyse:</b> Vår avanserte AI-kokk identifiserer raskt ingrediensene dine og foreslår en rekke oppskrifter du kan lage.</li>
                    <li><b>Lag mat og Nyt:</b> Velg en oppskrift som frister smaksløkene dine, følg trinn-for-trinn-instruksjonene, og dykk inn i et deilig måltid laget fra ditt eget kjøkken!</li>
                </ol>
                <p>
                    Med Magic Meal Maker, frigjør din indre kokk, reduser matsvinn, og oppdag nye måter å nyte ingrediensene du allerede har. Enten du er en matlagingsnovise eller en kulinarisk trollmann, er vår app designet for å inspirere kreativitet og bringe glede til måltidet ditt. La oss forvandle funnene fra kjøleskapet ditt til ditt neste gastronomiske eventyr!
                </p>
                <p><a href="https://magic-meal-maker.steinhaug.no" class="btn btn-full btn-s font-600 rounded-s gradient-highlight mt-1 float-start ">Gå til: Magic Meal Maker</a></p>
            </div>
        </div>
<?php } ?>


        <div data-menu-load="<?=$appConf['menuFooter']?>"></div>
    </div>
    <!-- Page content ends here-->
        

    <div id="menu-main" class="menu menu-box-left rounded-0" data-menu-load="menu-main.html" data-menu-width="280" data-menu-active="nav-media"></div>
    <div id="menu-share" class="menu menu-box-bottom rounded-m" data-menu-load="menu-share.html" data-menu-height="370"></div>  
    <div id="menu-colors" class="menu menu-box-bottom rounded-m" data-menu-load="menu-colors.html" data-menu-height="480"></div> 

</div>

<script type="text/javascript" src="scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="scripts/custom.js.php?<?=$html_NoCache_Version?>"></script>

<?php
output_session_notification();
que_modal_tpl('bookmark','login','logout','toast');
echo write_modal_tpls();
?>

<script>
let installPrompt = null;
const installAlert = document.querySelector("#install");
const installButton = document.querySelector("#install-button");

window.addEventListener("beforeinstallprompt", (event) => {
    event.preventDefault();
    installPrompt = event;
    installAlert.removeAttribute("hidden");
});

installButton.addEventListener("click", async () => {
    if (!installPrompt) {
        return;
    }
    const result = await installPrompt.prompt();
    console.log(`Install prompt was: ${result.outcome}`);
    disableInAppInstallPrompt();
});

window.addEventListener("appinstalled", () => {
    disableInAppInstallPrompt();
});

function disableInAppInstallPrompt() {
    console.log('disableInAppInstallPrompt() triggered');
    installPrompt = null;
    installAlert.setAttribute("hidden", "");
}

</script>

</body><?php
ob_end_flush();
?>
