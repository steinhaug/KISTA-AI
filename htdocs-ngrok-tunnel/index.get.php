<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';
require dirname(__FILE__) . '/func.inc.php';



use BenBjurstrom\Replicate\Replicate;

$api = new Replicate(
    apiToken: $replicate_api_token,
);


$data = $api->predictions()->get('vcomv2db4w6i5jzqud42aw7kda');
//echo $data->output[0];
krumo($data, KRUMO_EXPAND_ALL);

if($data->status == 'succeeded'){

    foreach($data->output as $v){
        echo $v . '<br>';
        $filename = pathinfo($v, PATHINFO_BASENAME);

        if (!file_exists('./downloads/' . $filename)) {
            $data = openai__guzzleDownloader($v);
            if ($data[0]=='200' and $data[2]=='png') {
                file_put_contents('./downloads/' . $filename, $data[1]);
                echo '<img src="/downloads/' . $filename . '">';
            }
        } else {
            echo '<img src="/downloads/' . $filename . '">';
        }
    }

}