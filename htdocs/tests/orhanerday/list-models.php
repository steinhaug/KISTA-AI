<?php
require '../../../vendor/autoload.php';
require '../../../credentials.php';

use Orhanerday\OpenAi\OpenAi;
$open_ai = new OpenAi($open_ai_key);

echo $open_ai->listModels();
var_dump($open_ai->getCURLInfo());