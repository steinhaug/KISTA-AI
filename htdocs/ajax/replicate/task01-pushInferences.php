<?php

use BenBjurstrom\Replicate\Replicate;

$api = new Replicate(
    apiToken: $replicate_api_token,
);


$st_WWW_path    = 'https://fish-touching-suddenly.ngrok-free.app/images/style-transfers/';
$user_WWW_path  = 'https://fish-touching-suddenly.ngrok-free.app/uploaded_files/r/';
$hook_WWW_path  = 'https://fish-touching-suddenly.ngrok-free.app/';

try {
    copy(
        UPLOAD_PATH . DIRECTORY_SEPARATOR . 'r' . DIRECTORY_SEPARATOR . $item['filename'],
        'I:/python-htdocs/KISTA-AI/htdocs-ngrok-tunnel/uploaded_files/r/' . $item['filename']
    );
} catch (Exception $e) {
    throw new RepliImage('Image copy error, ' . $item['filename']);
}

$user_image          = $user_WWW_path . $item['filename'];
$styleTransfer_image = $st_WWW_path . substr($item['stylename'], 0, -3) . 'jpg';

$version = '42cf9559131f57f018bf8cdc239a74f4871c5852045ce8f23b346e4ef8f56aa8';
$input = [
    "seed"=> 6969696969,
    "image"=> $user_image,
    "prompt"=> "a person",
    "image_to_become"=> $styleTransfer_image,
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

    $sql = new sqlbuddy;
    $sql->que('replicate_id', $data->id, 'string');
    $sql->que('status', $data->id, 'string');
    $success = $mysqli->query($sql->build('update', $kista_dp . "replicate__uploads", 'reid=' . $reid));

} catch (Exception $e) {

    throw new ReplicateAPIException('Replicate API error, ' . $e->getMessage());

}