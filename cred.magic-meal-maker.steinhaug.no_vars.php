<?php

// Parameter added script tags to prevent caching
$PWA_APP_VER = '1.0.2';
$html_NoCache_Version = 'v1.0.2';
$include_login_button = true;

$PWA_APP_NAME       = 'Magic Meal Maker';
$PWA_APP_NAME_SHORT = 'MMealMaker';

//Local Storage Names for PWA
$PWA_Name = 'MagicMealMaker';

//Setting Service Worker Locations scope = folder | location = service worker js location
$PWA_DOMAIN = 'https://magic-meal-maker.steinhaug.no';

$PWA_LANG = [
    'ico_folder' => './app/icons',
    'nb' => ['title'=>'Kjøleskapets hemmelige kokk! - KISTA AI'],
    'en' => ['title'=>'Your Refrigerator\'s Secret Chef! - KISTA AI'],
    'highlight' => ''
];

$_menuSuffix = '';
$HTML_HEADER = 'HTML_HEADER' . $_menuSuffix;
$HTML_FOOTER = 'HTML_FOOTER' . $_menuSuffix;
