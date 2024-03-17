<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

use BenBjurstrom\Replicate\Replicate;


$api = new Replicate(
    apiToken: $replicate_api_token,
);


/* @var PredictionsData $data */
$data = $api->predictions()->list(
    //cursor: '20', // optional
);

krumo( $data );
// $data->results[0]->id; // la5xlbbrfzg57ip5jlx6obmm5y