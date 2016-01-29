<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';   // Composer components
require_once dirname(__FILE__) . '/scripts/phpErrorHandling.php';  // Logging Service
require_once dirname(__FILE__) . '/controllers/api.v1.php';

$api = new \API\V1Controller();
$api->run();