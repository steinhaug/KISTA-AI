<?php

use BenBjurstrom\Replicate\Replicate;
use Intervention\Image\ImageManagerStatic as Image;

$api = new Replicate(
    apiToken: $replicate_api_token,
);

$imgName = $item['filename'];

$st_WWW_path    = $ngrok_tunnel_domain . '/images/style-transfers/';
$user_WWW_path  = $ngrok_tunnel_domain . '/uploaded_files/r/';
$hook_WWW_path  = $ngrok_tunnel_domain . '/';

// Load and check image size
$img = Image::make(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r' . DIRECTORY_SEPARATOR . $imgName);
$width = $img->width();
$height = $img->height();
if(anyHigher(1200, $width, $height)){
    $imgName = get_name_only($item['filename']) . '_l.jpg';
    [$new_x, $new_y] = calc__fit_constraints_lspt($width, $height, 1024, 768, 768, 1024);
    logfile('Resizing image from ' . $width . 'x' . $height . ' to ' . $new_x . 'x' . $new_y);
    $img->resize($new_x, $new_y)->save(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r' . DIRECTORY_SEPARATOR . $imgName, 90);
}

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
$input = [
    "seed"=> 6969696969,
    "image"=> $user_WWW_path . $imgName,
    "prompt"=> $prompt,
    "image_to_become"=> $st_WWW_path . $styleTransfer_image,
    "negative_prompt"=> "",
    "prompt_strength"=> 2,
    "number_of_images"=> 2,
    "denoising_strength"=> 0.8,
    "instant_id_strength"=> 1,
    "image_to_become_noise"=> 0.25,
    "control_depth_strength"=> 0.9,
    "image_to_become_strength"=> 0.8,
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