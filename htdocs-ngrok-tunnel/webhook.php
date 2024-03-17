<?php
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

echo "<h2>Superglobals</h2>";

echo "<h3>\$_SERVER</h3>";
echo "<pre>";
safePrintArray($_SERVER);
echo "</pre>";

echo "<h3>\$_GET</h3>";
echo "<pre>";
safePrintArray($_GET);
echo "</pre>";

echo "<h3>\$_POST</h3>";
echo "<pre>";
safePrintArray($_POST);
echo "</pre>";

echo "<h3>\$_FILES</h3>";
echo "<pre>";
safePrintArray($_FILES);
echo "</pre>";

echo "<h3>\$_COOKIE</h3>";
echo "<pre>";
safePrintArray($_COOKIE);
echo "</pre>";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<h3>\$_SESSION</h3>";
echo "<pre>";
safePrintArray($_SESSION);
echo "</pre>";

echo "<h3>\$_ENV</h3>";
echo "<pre>";
safePrintArray($_ENV);
echo "</pre>";

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