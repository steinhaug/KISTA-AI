<?php
use Intervention\Image\ImageManagerStatic as Image;

define('APPDATA_PATH', dirname(__FILE__) . '/inc_appdata');
define('UPLOAD_PATH', dirname(__FILE__) . '/uploaded_files');

session_start();

require_once 'func.inc.php';
require_once 'func.login.php';

echo 'USER_ID: ' . $USER_ID . '<br>';

$my_reid = _GET('reid');

if( ($item = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` WHERE `reid`=?", 's', [$my_reid], true)) === null ){
    echo 'No record found with reid: ' . $my_reid . '<br>';
    echo '<a href="info.php?reid=1">info.php?reid=1</a><br>';
} else {
    echo '<img src="/uploaded_files/r/' . $item['filename'] . '" width="256" height="256"><br>';
    echo '<a href="info.php?reid=' . $my_reid . '&continue_import=true">info.php?reid=' . $my_reid . '&continue_import=true</a><br>';

}

if( !_bool(_GET('continue_import')) ){
    die('<hr><p>awaiting continue_import</p>');
}


$files = getDirContents(dirname(__FILE__) . '/import', 1);

foreach($files as $fileData){

    [$name, $is_dir, $depth ] = $fileData;
    if(!empty($is_dir) or !$depth)
        continue;

    $source = dirname(__FILE__) . '/import/' . $name;
    if( !file_exists($source) )
        continue;

    echo '.';
}


echo '<p><a href="info.php?reid=' . $my_reid . '&continue_import=true&execute=true">Execute the import</a></p>';

if( !_bool(_GET('execute')) ){
    die('<hr><p>awaiting execute import</p>');
}


$_real_filename_from_upload = 'source.jpg';

$source = dirname(__FILE__) . '/import/' . $_real_filename_from_upload;
$filehash = hash_file('sha256', $source);
$file1_size = filesize($source);
$file_extension = get_extension($_real_filename_from_upload);

$_real_filename_from_upload = $item['reid'] . '_' . 'source.jpg';
if (!copy($source, dirname(__FILE__) . '/uploaded_files/r/' . $_real_filename_from_upload)) {
    die('<h1 style="color:red">Copy error!</h1>');
}


$sql = new sqlbuddy;
$sql->que('uuid', generateUuid4(),'string');
$sql->que('replicate_id', '--imported--','string');
$sql->que('replicate_task', 2,'int');
$sql->que('user_id', $item['user_id'],'int');
$sql->que('created', 'NOW()','raw');
$sql->que('updated', 'NULL','raw');
$sql->que('stylename', '', 'string');
$sql->que('realname', $_real_filename_from_upload, 'string');
$sql->que('filehash', $filehash, 'string');
$sql->que('filename', $_real_filename_from_upload, 'string');
$sql->que('extension', $file_extension, 'string');
$sql->que('filesize', $file1_size, 'int');
$sql->que('thumbnail', '', 'string');
$sql->que('status', 'complete', 'string');
$sql->que('data', '{"steps":20,"ip_adapter_noise":"0.75","ip_adapter_weight":"0.50","instant_id_strength":"0.70"}', 'string');
$sql->que('log', '', 'string');
$sql->que('error', '', 'string');
$mysqli->query( $sql->build('insert', $kista_dp . "replicate__uploads") );
$reid = $mysqli->insert_id;

$files = getDirContents(dirname(__FILE__) . '/import/results');
/*
echo '<pre>';
echo print_r($files, 1);
echo '</pre>';
*/
foreach($files as $fileData){

    [$name, $is_dir, $depth ] = $fileData;
    if(!empty($is_dir) or $depth)
        continue;

    $source = dirname(__FILE__) . '/import/results/' . $name;
    if( !file_exists($source) )
        continue;

    $image_filename = $reid . '-' . $name;
    $destination = dirname(__FILE__) . '/uploaded_files/ri/' . $image_filename;
    $size = filesize($source);

    if (!copy($source, $destination)) {
        die('<h1 style="color:red">Copy error!</h1>');
    }

    $sql = new sqlbuddy;
    $sql->que('deleted', 0, 'int');
    $sql->que('uuid', generateUuid4(), 'string');
    $sql->que('reid', $reid, 'int');
    $sql->que('created', 'NOW()', 'raw');
    $sql->que('url', '//imported', 'string');
    $sql->que('filename', $image_filename, 'string');
    $sql->que('extension', get_extension($image_filename), 'string');
    $sql->que('filesize', $size, 'int');
    $sql->que('thumbnail', '', 'string');
    $sql->que('status', 'done', 'string');
    $mysqli->query($sql->build('insert', $kista_dp . "replicate__images"));
    $image_id = $mysqli->insert_id;

    echo $image_filename . ', ';
    //echo $source . '<br>';
}


//var_dump( $reid );


die('Done');




echo '<pre>';
echo print_r($files, 1);
echo '</pre>';
exit;


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
