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
            <a href="#" class="btn btn-border btn-m btn-full mb-3 rounded-s text-uppercase font-700 border-gray-dark color-night-dark bg-theme">
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