<?php

// Parameter added script tags to prevent caching
$PWA_APP_VER = '1.0';
$html_NoCache_Version = 'v1.0';
$include_login_button = true;

$PWA_APP_NAME       = 'KISTA-AI APP';
$PWA_APP_NAME_SHORT = 'Kista-AI';

//Local Storage Names for PWA
$PWA_Name = 'KistaAiApp';

//Setting Service Worker Locations scope = folder | location = service worker js location
$PWA_DOMAIN = 'https://fish-touching-suddenly.ngrok-free.app';

$PWA_LANG = [
    'ico_folder' => './app/avatarify',
    'nb' => ['title'=>'Kjøleskapets hemmelige kokk! - KISTA AI'],
    'en' => ['title'=>'Your Refrigerator\'s Secret Chef! - KISTA AI'],
    'highlight' => '<link rel="stylesheet" class="page-highlight" type="text/css" href="styles/highlights/highlight_red.css">'
];
