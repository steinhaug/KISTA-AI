<?php
require './vendor/autoload.php';
require dirname(__FILE__) . '/func.inc.php';

end_connection('ok');

sleep(5);

echo '... 5 seconds later closed.';
logfile('... 5 seconds later closed.');
