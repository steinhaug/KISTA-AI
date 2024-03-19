<?php
require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';

    $url = 'https://fish-touching-suddenly.ngrok-free.app/process-sleep.php';


function getCurlContent() {
    $url = "https://fish-touching-suddenly.ngrok-free.app/process-sleep.php";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Verify the peer's SSL certificate

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);
}
function getCurlContentWithoutSSLVerification() {
    $url = "https://fish-touching-suddenly.ngrok-free.app/process-sleep.php";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // NOT recommended for production, only for testing
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);  // Set a very short timeout

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    } else {
        echo $response;
    }

    curl_close($ch);
}

getCurlContentWithoutSSLVerification();
#getCurlContent();

echo '<hr>Completed. ' . time();