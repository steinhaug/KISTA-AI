<?php

define('WEBROOT', dirname(dirname(__FILE__)) . '/htdocs');
require './vendor/autoload.php';

define('SKIP_VENDOR_AUTOLOAD', true);
require WEBROOT . '/webhook.php';

