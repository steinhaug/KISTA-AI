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
        [$new_x, $new_y] = calc__fit_constraints_lspt($width, $height, 896, 832, 832, 896);
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



// Register the inference 
$version = '764d4827ea159608a07cdde8ddf1c6000019627515eb02b6b449695fd547e5ef';

$user_settings = json_decode($item['data'], true);
$conf = [
    'steps' => make_sure_value_fits_specs($user_settings['steps'],                              [20, 'int', 10,  50], []),
    'ip_adapter_noise' => make_sure_value_fits_specs($user_settings['ip_adapter_noise'],        [.75, 'int', 0.1, 1], []),
    'ip_adapter_weight' => make_sure_value_fits_specs($user_settings['ip_adapter_weight'],      [.50, 'int', 0.1, 1], []),
    'instant_id_strength' => make_sure_value_fits_specs($user_settings['instant_id_strength'],  [.70, 'int', 0.1, 1], []),
];

$input = [
    "image" => $user_WWW_path . $imgName,
    "width" => 1024,
    "height" => 1024,
    "prompt" => "a person",
    "negative_prompt" => "",
    "upscale" => false,
    "upscale_steps" => 10,
    "prompt_strength" => 3.5,
    "steps" => $conf['steps'],
    "ip_adapter_noise" => $conf['ip_adapter_noise'],
    "ip_adapter_weight" => $conf['ip_adapter_weight'],
    "instant_id_strength" => $conf['instant_id_strength'],
    "disable_safety_checker"=>true,
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
