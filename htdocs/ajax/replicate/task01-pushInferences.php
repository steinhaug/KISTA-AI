<?php

use BenBjurstrom\Replicate\Replicate;
use Intervention\Image\ImageManagerStatic as Image;

$api = new Replicate(
    apiToken: $replicate_api_token,
);

$imgName = $item['filename'];
$imgPath = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r' . DIRECTORY_SEPARATOR . $imgName;

$st_WWW_path    = $ngrok_tunnel_domain . '/images/style-transfers/';
$user_WWW_path  = $ngrok_tunnel_domain . '/uploaded_files/r/';
$hook_WWW_path  = $ngrok_tunnel_domain . '/';

try {
    // Load and check image size
    $img = Image::make($imgPath)->orientate();
    $exif = $img->exif();
    $o = 1;
    if( !empty($exif['Orientation']) )
        $o = $exif['Orientation'];

    $width = $img->width();
    $height = $img->height();
    if(anyHigher(1200, $width, $height)){
        $newImgNameJpg = get_name_only($imgPath) . '_l.jpg';
        $newImgPathJpg = dirname($imgPath) . DIRECTORY_SEPARATOR . $newImgNameJpg;
        $newImgNamePng = get_name_only($imgPath) . '_l.png';
        $newImgPathPng = dirname($imgPath) . DIRECTORY_SEPARATOR . $newImgNamePng;

        $imgName = $newImgNameJpg;
        [$new_x, $new_y] = calc__fit_constraints_lspt($width, $height, 1024, 768, 768, 1024);
        logfile('Resizing image from ' . $width . 'x' . $height . ' to ' . $new_x . 'x' . $new_y);

        if ($o == 6) {
            $img->resize($new_y, $new_x, function ($constraint) {
                $constraint->aspectRatio();
            })->save($newImgPathJpg, 90);
        } else if ($o == 3) {
            $img->resize($new_x, $new_y)->save($newImgPathPng);
            unset($img);
            $img = new Imagick($newImgPathPng);
            $img->stripImage();
            unlink($newImgPathPng);
            $img->setImageFormat('jpeg');
            $img->setImageCompression(Imagick::COMPRESSION_JPEG);
            $img->setImageCompressionQuality(95);
            $img->writeImage($newImgPathJpg);
        } else {
            $img->resize($new_x, $new_y)->save($newImgPathJpg, 90);
        }
    }
} catch (Exception $e) {
    throw new RepliImage('Image error: ' . $e->getMessage());
}

/*
if ($ngrok_tunnel_domain == 'https://fish-touching-suddenly.ngrok-free.app') {
    try {
        logfile('Dev: copying file to tunneled site, ' . $imgName);
        copy(
            UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r' . DIRECTORY_SEPARATOR . $imgName,
            'I:/python-htdocs/KISTA-AI/htdocs-ngrok-tunnel/uploaded_files/r/' . $imgName
        );
    } catch (Exception $e) {
        throw new RepliImage('Image copy error, ' . $imgName);
    }
}
*/

$prompt = 'a person';
$styleTransfer_image = substr($item['stylename'], 0, -3) . 'jpg';
switch($styleTransfer_image){
    case 'anime-1.jpg':
        $prompt = 'a person, B/W, Lineart Image, duotone, teal and sky blue';
        break;
    case 'sketch.jpg':
        $prompt = 'a person, sketch, duotone, mocca brown and beaver grey';
        break;
    case 'sketch.jpg':
        $prompt = 'a person, sketch, duotone, mocca brown and beaver grey';
        break;
    case 'graffiti-art.jpg':
        $prompt = 'graffiti art of a person with a crown on his head, frida, highly detailed cover art, paid art assets, vivid color, lv, exploited, cash, key art, various artists, despacito, vincent, with neon signs, lowres, panini, wtf';
        break;
}

// Register the inference 
$version = '42cf9559131f57f018bf8cdc239a74f4871c5852045ce8f23b346e4ef8f56aa8';
//    "seed"=> 6969696969,
$input = [
    "image"=> $user_WWW_path . $imgName,
    "prompt"=> $prompt,
    "image_to_become"=> $st_WWW_path . $styleTransfer_image,
    "negative_prompt"=> "",
    "number_of_images"=> 2,
    "denoising_strength"=> 0.75,
    "prompt_strength"=> 2.5,
    "control_depth_strength"=> 0.85,
    "instant_id_strength"=> 0.9,
    "image_to_become_strength"=> 0.9,
    "image_to_become_noise"=> 0.4,
    "disable_safety_checker"=>true
];

try {

    $data = $api->predictions()->withWebhook($hook_WWW_path . 'webhook.php')->create($version, $input);
    //echo $data->id;
    logfile('replicate_id: ' . $data->id);

    $status = updateStatus__replicate($item['reid'], ['status'=>'task1', 'replicate_id'=>$data->id ]);
    logfile('- TASK 1 completed.');

} catch (Exception $e) {

    logfile('Replicate API error, ' . $e->getMessage());
    throw new ReplicateAPIException('Replicate API error, ' . $e->getMessage());

}
