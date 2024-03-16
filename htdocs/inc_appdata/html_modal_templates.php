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

        if($key=='login'){

            //$html .= str_replace('{{URL_GOOGLE_LOGIN}}', $_SESSION['url_google_login'], $_html_modal_templates[$key]);
            $html .= str_replace('{{URL_GOOGLE_LOGIN}}', 'index.php?google_login=' . rawurlencode(prepareLocation()), $_html_modal_templates[$key]);
        } else if($key=='logout'){
            if (!empty($_SESSION['USER_GOOGLE_LOGIN'])) {
                [$a, $b, $data] = $_SESSION['USER_GOOGLE_LOGIN'];
            } else {
                $data = ['image'=>'/images/avatars/person.png','email'=>'example-email@gmail.com','name'=>'Ola Nordmann'];
            }
            $html .= str_replace(
                ['{{USER_IMAGE}}',  '{{USER_EMAIL}}',   '{{USER_NAME}}'], 
                [   $data['image'],    $data['email'],     $data['name']], 
                $_html_modal_templates[$key]
            );
        } else {
            $html .= $_html_modal_templates[$key];
        } 

    }
    return '<!-- write_modal_tpls -->' . LF . minify_html($html, 'modals') . LF;
}


if($lang == 'nb'){
    $_l = [
        'sign_in' => 'Logg inn',
        'login_google' => 'Logg inn med Google',
        'login_skip' => 'Fortsett uten innlogging',
        'sign_out' => 'Utlogging',
        'logout' => 'Logg ut',
        'or' => 'eller',
    ];
} else {
    $_l = [
        'sign_in' => 'Sign In',
        'login_google' => 'Sign In with Google',
        'login_skip' => 'Continue without logging in',
        'sign_out' => 'Sign Out',
        'logout' => 'Logout',
        'or' => 'or',
    ];
}



$_html_modal_templates = [];
$_html_modal_templates['login'] = '
    <div id="modalMenu-login" 
         class="menu menu-box-modal rounded-m" 
         data-menu-height="310" 
         data-menu-width="350">
        <div class="menu-title">
            <h1 class="font-24">' . $_l['sign_in'] . '</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <a href="{{URL_GOOGLE_LOGIN}}" class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-700 border-gray-dark color-night-dark bg-theme external-link">
                <img class="login-btn-icon" src="/app/icons/google-logo.svg" alt="Google Logo">
                <span>' . $_l['login_google'] . '</span>
            </a>
            <div class="divider-spanp" role="separator"><span><p>' . strtoupper($_l['or']) . '</p></span></div>
            <a href="#" class="btn close-menu btn-full btn-m color-blue-dark border-blue-light font-600 rounded-s external-link">
                ' . $_l['login_skip'] . '
            </a>
        </div>
    </div>
';


$_html_modal_templates['logout'] = '
    <div id="modalMenu-logout" 
         class="menu menu-box-modal rounded-m" 
         data-menu-height="250" 
         data-menu-width="350">
        <div class="menu-title">
            <h1 class="font-24">' . $_l['sign_out'] . '</h1>
            <a href="#" class="close-menu"><i class="fa fa-times-circle"></i></a>
        </div>
        <div class="content mb-0 mt-2">
            <div class="d-flex">
                <div><img src="{{USER_IMAGE}}" class="me-3 rounded-circle shadow-l" width="50"></div>
                <div>
                    <h5 class="mt-1 mb-0">{{USER_NAME}}</h5>
                    <p class="font-10 mt-n1 color-gray-dark">{{USER_EMAIL}}</p>
                </div>
            </div>
            <br>
            <p><a href="index.php?action=logout" class="close-menu btn btn-full btn-m shadow-l rounded-s text-uppercase font-600 gradient-blue mt-n2 external-link">' . $_l['logout'] . '</a></p>
        </div>
    </div>
';

$_html_modal_templates['toast'] = '
    <div id="toast-1" class="toast toast-tiny toast-top bg-blue-dark" data-bs-delay="1000" data-bs-autohide="true"><i class="fa fa-info me-3"></i>No image</div>
    <div id="toast-2" class="toast toast-tiny toast-top bg-green-dark" data-bs-delay="2000" data-bs-autohide="true"><i class="fa fa-info me-3"></i>You are logged in</div>
';

$_html_modal_templates['bookmark'] = '
    <div id="menu-heart" 
         class="menu menu-box-modal rounded-m" 
         data-menu-hide="800"
         data-menu-width="250"
         data-menu-height="170">
        
        <h1 class="text-center mt-3 pt-2">
            <i class="fa fa-check-circle color-green-dark fa-3x"></i>
        </h1>
        <h3 class="text-center pt-2">Added to Bookmarks</h3>
    </div>
';