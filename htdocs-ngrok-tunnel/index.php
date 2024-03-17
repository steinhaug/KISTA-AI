<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

use BenBjurstrom\Replicate\Replicate;


$api = new Replicate(
    apiToken: $replicate_api_token,
);

echo time();
