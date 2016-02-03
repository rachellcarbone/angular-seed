<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';   // Composer components
require_once dirname(__FILE__) . '/scripts/phpErrorHandling.php';  // Logging Service
require_once dirname(__FILE__) . '/controllers/api.v1.php';

/*
 * PHP / Server Configuration
 */

// Allow from any origin
if (false && isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
}

// Access-Control headers are received during OPTIONS requests
if (false && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Work around for Slim on GAE
if (false && !isset($_SERVER['SERVER_PORT']))
    $_SERVER['SERVER_PORT'] = 443;


/*
 *  Run API
 */
$api = new \API\V1Controller();
$api->run();

// Google App Engine doesn't set $_SERVER['PATH_INFO']
//$api->environment['PATH_INFO'] = $_SERVER['REQUEST_URI'];