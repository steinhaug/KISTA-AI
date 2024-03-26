<?php
use Intervention\Image\ImageManagerStatic as Image;

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

require_once 'func.inc.php';



    if( ($reid = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `replicate_id`=?", 's', ['2u3inelbksrzr7cbwstctxst4i'], true)) === null ){
        echo 'NULL';
    }

echo '<pre>';
var_dump($reid);
echo '</pre>';


exit;
echo '<pre>';
echo print_r($_SERVER,1);
echo '</pre>';
exit;



if( file_exists('I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\test\20240326_021842_l.jpg')) 
    unlink('I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\test\20240326_021842_l.jpg');
if( file_exists('I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\test\20240326_021842_l.png')) 
    unlink('I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\test\20240326_021842_l.png');


$imgs = [
    'docs/figs/style-transfer-01.png',
    'docs/figs/style-transfer-02.png',
    'docs/figs/style-transfer-03.png',
];

$imgpath = 'I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\test\img-0529.jpg';

$i = ['20240326_021838.jpg','20240326_021842.jpg','20240326_021846.jpg','20240326_021852.jpg'];

$imgpath = dirname($imgpath) . DIRECTORY_SEPARATOR . $i[3];
echo $imgpath . '<br>';

$img = Image::make($imgpath)->orientate();

$data = $img->exif();
$o = 1;
if( !empty($data['Orientation']) )
    $o = $data['Orientation'];

echo '<h1>' . $o . '</h1>';

/*
echo '<pre>';
echo print_r($data,1);
echo '</pre>';

echo $data['Orientation'];

exit;

*/

$width = $img->width();
$height = $img->height();
if(anyHigher(1200, $width, $height)){
    logfile('resizing');
    $newImgName = get_name_only($imgpath) . '_l.jpg';
    [$new_x, $new_y] = calc__fit_constraints_lspt($width, $height, 1024, 768, 768, 1024);
    logfile('Resizing image from ' . $width . 'x' . $height . ' to ' . $new_x . 'x' . $new_y);

    if ($o == 6) {
        $img->resize($new_y, $new_x, function ($constraint) {
            $constraint->aspectRatio();
        })->save(dirname($imgpath) . DIRECTORY_SEPARATOR . $newImgName, 90);
    } else if( $o == 3 ){
        $newImgNamePng = get_name_only($imgpath) . '_l.png';
        $newImgNameJpg = get_name_only($imgpath) . '_l.jpg';
        $newImgPathPng = dirname($imgpath) . DIRECTORY_SEPARATOR . $newImgNamePng;
        $newImgPathJpg = dirname($imgpath) . DIRECTORY_SEPARATOR . $newImgNameJpg;
        $img->resize($new_x, $new_y)->save($newImgPathPng);
        unset($img);

        $img = new Imagick($newImgPathPng);
        $img->stripImage();
        unlink($newImgPathPng);

        $img->setImageFormat('jpeg');
        $img->setImageCompression(Imagick::COMPRESSION_JPEG);
        $img->setImageCompressionQuality(95);
        $img->writeImage($newImgPathJpg);

        //$img->setImageFormat('png');
        //$img->writeImage($newImgPathPng);
        // - convert png to jpg
        //$img = Image::make($newImgPathPng)->save($newImgPathJpg);
        //unset($img);
        //unlink($newImgPathPng);

        /*
        $img = Image::make($newImgPathJpg);
        $exif = $img->exif();
        //echo '<h1>' . $exif['Orientation'] . '</h1>';
        echo '<pre>';
        echo print_r($exif,1);
        echo '</pre>';
        */

    } else {
        $img->resize($new_x, $new_y)->save(dirname($imgpath) . DIRECTORY_SEPARATOR . $newImgName, 90);
    }


}

echo 'done';
exit;



$json = file_get_contents('I:\python-htdocs\KISTA-AI\htdocs\uploaded_files\ri\djCj6eeu5SlFZkM4Z6BYW7JEZsGLmscl2AJFGXbWrpqaxShSA.png.identify-result');
$data = json_decode($json, 1);
echo '<pre>';
echo print_r($data,1);
echo '</pre>';


/*
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
*/
