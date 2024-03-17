<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

use BenBjurstrom\Replicate\Replicate;


$api = new Replicate(
    apiToken: $replicate_api_token,
);

$version = '42cf9559131f57f018bf8cdc239a74f4871c5852045ce8f23b346e4ef8f56aa8';
$input = [
    "seed"=> 6969696969,
    "image"=> "https://fish-touching-suddenly.ngrok-free.app/uploads/upl001.jpg",
    "prompt"=> "a person",
    "image_to_become"=> "https://fish-touching-suddenly.ngrok-free.app/uploads/tpl_sketch.jpg",
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

#$data = $api->predictions()->create($version, $input);
#$data->id; // yfv4cakjzvh2lexxv7o5qzymqy

$data = $api->predictions()->withWebhook('https://fish-touching-suddenly.ngrok-free.app/webhook.php')->create($version, $input);
echo $data->id;
krumo($data); // aj6willbmrycmrhhwwdv734w7q, vcomv2db4w6i5jzqud42aw7kda

