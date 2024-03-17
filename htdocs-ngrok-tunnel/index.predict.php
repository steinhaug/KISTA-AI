<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

use BenBjurstrom\Replicate\Replicate;


$api = new Replicate(
    apiToken: $replicate_api_token,
);

echo time();


$version = 'db21e45d3f7023abc2a46ee38a23973f6dce16bb082a930b0c49861f96d1e5bf';
$input = [
    '_model' => 'stable-diffusion-2-1',
    'prompt' => 'a photo of an astronaut riding a horse on mars',
    'negative_prompt' => 'moon, alien, spaceship',
    'width' => 768,
    'height' => 768,
    'num_inference_steps' => 50,
    'guidance_scale' => 7.5,
    'scheduler' => 'DPMSolverMultistep',
    '_seed' => 6969,
];

#$data = $api->predictions()->create($version, $input);
#$data->id; // yfv4cakjzvh2lexxv7o5qzymqy

$data = $api->predictions()->withWebhook('https://fish-touching-suddenly.ngrok-free.app/webhook.php')->create($version, $input);
krumo($data->id); // 6vyxmplbrgowsx4rgi6kflhoqu, hj2awxdbc5twbwp2mpvvspmzba, k4as3mtbcpgqqql67j2exzgxpe

/*
HTTP_ACCEPT_ENCODING => gzip
HTTP_X_FORWARDED_PROTO => https
HTTP_X_FORWARDED_HOST => fish-touching-suddenly.ngrok-free.app
HTTP_X_FORWARDED_FOR => 44.228.126.217
HTTP_WEBHOOK_TIMESTAMP => 1710649573
HTTP_WEBHOOK_SIGNATURE => v1,ED76rUIN2QvC7fin4jNLPiXXYZEoE/W5UpFc7aKKslA=
HTTP_WEBHOOK_ID => msg_2dnh6Q3fbSq1Llolavu3fVX49mt
*/