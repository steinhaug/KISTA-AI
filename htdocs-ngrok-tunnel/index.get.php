<?php

#echo 'Current PHP version: ' . phpversion();

require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';



use BenBjurstrom\Replicate\Replicate;

$api = new Replicate(
    apiToken: $replicate_api_token,
);

$replicate_id = '2u3inelbksrzr7cbwstctxst4i';

$data = $api->predictions()->get($replicate_id);
//echo $data->output[0];
krumo($data, KRUMO_EXPAND_ALL);

try {
    $sql = new sqlbuddy;
    $sql->que('processed', 0, 'int');
    $sql->que('replicate_id', $replicate_id, 'string:26');
    $sql->que('created', 'NOW()', 'raw');
    $sql->que('json', '', 'string');
    echo htmlentities($sql->build('insert', $kista_dp . "replicate__hooks") );
} catch (Exception $e) {
    $error = $e->getMessage();
    logfile('- HOOK ERROR: ' . $e->getMessage());
}

exit;
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