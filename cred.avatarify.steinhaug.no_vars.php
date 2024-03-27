<?php

// Parameter added script tags to prevent caching
$PWA_APP_VER = '1.0.2';
$html_NoCache_Version = 'v1.0.2';
$include_login_button = true;

$PWA_APP_NAME       = 'Avatarify';
$PWA_APP_NAME_SHORT = 'Avatarify';

//Local Storage Names for PWA
$PWA_Name = 'Avatarify';

//Setting Service Worker Locations scope = folder | location = service worker js location
$PWA_DOMAIN = 'https://avatarify.steinhaug.no';

$PWA_LANG = [
    'ico_folder' => './app/avatarify',
    'nb' => ['title'=>'Avatarify! - KISTA AI'],
    'en' => ['title'=>'Avatarify! - KISTA AI'],
    'highlight' => '<link rel="stylesheet" class="page-highlight" type="text/css" href="styles/highlights/highlight_red.css">'
];

$_menuSuffix = '_AVATAR';
$HTML_HEADER = 'HTML_HEADER' . $_menuSuffix;
$HTML_FOOTER = 'HTML_FOOTER' . $_menuSuffix;
