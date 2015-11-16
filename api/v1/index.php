<?php
require_once '/scripts/phpErrorHandling.php';  // Logging Service
require_once '/controllers/api.v1.php';

$api = new \API\V1Controller();
$api->run();