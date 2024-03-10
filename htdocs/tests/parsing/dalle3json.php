<?php
ob_start();
session_cache_expire(720);
session_start();

define('APPDATA_PATH', dirname(dirname(dirname(__FILE__))) . '/inc_appdata');
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))) . '/uploaded_files');
require dirname(APPDATA_PATH) . '/func.inc.php';
require dirname(APPDATA_PATH) . '/func.login.php';

#$filename = 'something.png';
#echo substr($filename, 0, -4);

$string2 = '```json
[
    "Plate of Smørbrød on rustic wooden board with cucumber & tomato, lit by natural light.",
    "Plate of Smørbrød on white porcelain, adorned with dill & parsley, with leading lines.",
    "Bowl of Smørbrød on marble countertop, surrounded by vibrant veggies, capturing details.",
    "Plate of Smørbrød on slate platter, garnished with pickles, focusing on meticulous styling."
]
```';


$dalle_prompts = getDelimitedStrings_string($string2, '[', ']', false);
var_dump($dalle_prompts);
echo '<pre>';
echo print_r(json_decode($dalle_prompts, 1),1);
echo '</pre>';
/*
echo '<pre>';
echo json_encode($dalle_prompts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
echo '</pre>';
*/