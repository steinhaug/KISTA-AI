<?php

$que_modal_tpl__list = [];
function que_modal_tpl(...$args){
    global $que_modal_tpl__list;
    foreach($args as $arg){
        if( !in_array($arg, $que_modal_tpl__list) )
            $que_modal_tpl__list[] = $arg;
    }
}

function write_modal_tpls(){
    global $que_modal_tpl__list, $_html_modal_templates;
    $html = '';
    foreach($que_modal_tpl__list as $key){
        $html .= $_html_modal_templates[$key];
    }
    return '<!-- write_modal_tpls -->' . LF . minify_html($html, 'modals') . LF;
}


$client = new Google_Client();
$client->setClientId($google_client_id);
$client->setClientSecret($google_client_secret);
$client->setRedirectUri('https://kista-ai.steinhaug.no/login.php');
$client->addScope("email");
$client->addScope("profile");
$google_url = $client->createAuthUrl();
logfile($google_url);

$_html_modal_templates = [];
$_html_modal_templates['login'] = '
    <div id="modalMenu-login" 
         class="menu menu-box-modal rounded-m" 
         data-menu-height="310" 
         data-menu-width="350">
        <div class="menu-title">
            <h1 class="font-24">Sign In</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <a href="' . $google_url . '" class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-700 border-gray-dark color-night-dark bg-theme">
                <img class="login-btn-icon" src="/app/icons/google-logo.svg" alt="Google Logo">
                <span>Sign In with Google</span>
            </a>
            <div class="divider-spanp" role="separator"><span><p>OR</p></span></div>
            <a href="#" class="btn close-menu btn-full btn-m color-blue-dark border-blue-light font-600 rounded-s">
                Continue without logging in
            </a>
        </div>
    </div>
';

$src = 'https://lh3.googleusercontent.com/a/ACg8ocJNC3s8hzeoUVz8J20KfBQCd3lUJ3373b51AvuVau6KJ18U=s96-c';
$name = 'Kim Steinhaug';
$email = 'steinhaug@gmail.com';

$_html_modal_templates['logout'] = '
    <div id="modalMenu-logout" 
         class="menu menu-box-modal rounded-m" 
         data-menu-height="250" 
         data-menu-width="350">
        <div class="menu-title">
            <h1 class="font-24">Sign Out</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <div class="d-flex">
                <div><img src="' . $src . '" class="me-3 rounded-circle shadow-l" width="50"></div>
                <div>
                    <h5 class="mt-1 mb-0">' . $name . '</h5>
                    <p class="font-10 mt-n1 color-gray-dark">' . $email . '</p>
                </div>
            </div>
            <br>
            <p><a href="index.php?action=logout" class="close-menu btn btn-full btn-m shadow-l rounded-s text-uppercase font-600 gradient-blue mt-n2">Logout from site</a></p>
        </div>
    </div>
';

