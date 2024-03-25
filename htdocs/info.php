<?php
use Intervention\Image\ImageManagerStatic as Image;

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';

$imgs = [
    'docs/figs/style-transfer-01.png',
    'docs/figs/style-transfer-02.png',
    'docs/figs/style-transfer-03.png',
];

foreach( $imgs as $img ){

        $imgName = get_name_only($img) . '_thumb.jpg';
        $dirPath = realpath('./docs/figs/');

        if( !file_exists($dirPath . $imgName) ){
            if( file_exists(realpath($img)) ){
                $newImg = Image::make(realpath($img))->resize(1024, 1024, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($dirPath . '/' . get_name_only($img) . '.jpg', 90)->fit(300,300)->save($dirPath . '/' . $imgName, 90);
            } else { echo 'not found: ' . realpath($img) . '<br>'; }
        } else {
            echo 'already exist: ' . $dirPath . $imgName . '<br>';
        }

}
