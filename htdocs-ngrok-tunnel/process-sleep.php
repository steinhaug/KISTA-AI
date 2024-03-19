<?php
require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';

    // Start output buffering
    ob_start();

    // Your message to the user
    echo 'OK';
    logfile('Opened...');

    // Calculate the size of the output
    $size = ob_get_length();

    // Send headers to tell the browser to close the connection
    header("Content-Length: $size");
    header('Connection: close');

    // Flush all output buffers to the client
    ob_end_flush();
    flush();

    // Continue processing after the client disconnects
    ignore_user_abort(true);
    set_time_limit(0); // Remove time limit for script execution if needed

    // Close session write if needed
    if (session_id()) session_write_close();

    // Send additional data to ensure the browser considers the response complete
    echo str_repeat(' ', 1024*64); // Send 64KB of whitespace
    flush();

//do processing here
sleep(5);

echo '... 5 seconds later closed.';
logfile('... 5 seconds later closed.');
