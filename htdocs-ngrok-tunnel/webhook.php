<?php
require './vendor/autoload.php';
require dirname(dirname(__FILE__)) . '/credentials.php';

ob_start();

echo "<h1>Environment Debug Script</h1>";

// Function to safely print arrays with htmlspecialchars
function safePrintArray($array) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            echo htmlspecialchars($key) . ' => Array:<br>';
            echo '<ul>';
            safePrintArray($value);
            echo '</ul>';
        } else {
            echo htmlspecialchars($key) . ' => ' . htmlspecialchars($value) . '<br>';
        }
    }
}

/*
echo "<h2>Superglobals</h2>";
echo "<h3>\$_SERVER</h3>";
echo "<pre>";
safePrintArray($_SERVER);
echo "</pre>";
*/

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$rawData = file_get_contents("php://input");
$jsonData = json_decode($rawData, true);

if ($jsonData) {

    $replicate_id = basename($jsonData['urls']['get']);
    if( ($data = $mysqli->prepared_query1("SELECT * FROM `" . $kista_dp . "replicate__uploads` `ru` WHERE `ru`.`replicate_id`=?", 's', [$replicate_id], true)) !== null ){
        echo 'Data located.';
    } else {
        echo 'Data NOT FOUND...';
    }


    echo "Data received:\n";
    print_r($jsonData);

} else {
    echo "No valid JSON data received.";
}

// Debugging HTTP Request
echo "<h2>HTTP Request Details</h2>";
echo "<strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "<strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<strong>Query String:</strong> " . $_SERVER['QUERY_STRING'] . "<br>";
echo "<strong>Remote Address:</strong> " . $_SERVER['REMOTE_ADDR'] . "<br>";

$page = ob_get_contents();
ob_end_clean();

echo 'OK';
file_put_contents('webhook.log', $page . "\n", FILE_APPEND);