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



//    <!-- Template for quickToast() -->
$_html_modal_templates['tplToast'] = <<<EOF
    <div id="myToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
            <strong class="me-auto">Bootstrap toast</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
    <div class="toast-container">
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11" id="myToasts">
        </div>
    </div>
EOF;

//    <!-- Template for quickAlert() -->
$_html_modal_templates['tplAlert'] = <<<EOF
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
    </svg>
    <div class="ms-3 me-3 alert alert-small rounded-s shadow-xl" role="alert" id="myAlert" style="display: none;">
        <span><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"></svg></span>
        <strong class="color-white">Alert title!</strong>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>    
EOF;