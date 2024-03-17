<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

use BenBjurstrom\Replicate\Replicate;


$api = new Replicate(
    apiToken: $replicate_api_token,
);


echo '<p><a href="index.predict.php">predict</a></p>';
echo '<p><a href="index.predict2.php">predict 2</a></p>';


echo '<p><a href="index.get.php">get</a></p>';
echo '<p><a href="index.list.php">list</a></p>';


/*
$data = $api->predictions()->get('6vyxmplbrgowsx4rgi6kflhoqu');
echo $data->output[0]; // https://replicate.delivery/pbxt/6UFOVtl1xCJPAFFiTB2tfveYBNRLhLmJz8yMQAYCOeZSFhOhA/out-0.png
krumo($data);

$data = $api->predictions()->get('hj2awxdbc5twbwp2mpvvspmzba');
echo $data->output[0]; // https://replicate.delivery/pbxt/6UFOVtl1xCJPAFFiTB2tfveYBNRLhLmJz8yMQAYCOeZSFhOhA/out-0.png
krumo($data);

$data = $api->predictions()->get('k4as3mtbcpgqqql67j2exzgxpe');
echo $data->output[0]; // https://replicate.delivery/pbxt/6UFOVtl1xCJPAFFiTB2tfveYBNRLhLmJz8yMQAYCOeZSFhOhA/out-0.png
krumo($data);
*/