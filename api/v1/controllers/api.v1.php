<?php
namespace API;

require_once dirname(dirname(__FILE__)) . '\vendor\autoload.php';   // Composer components
require_once dirname(dirname(__FILE__)) . '\config\config.php';     // API Coifg File (Add your settings!)
require_once dirname(dirname(__FILE__)) . '\routes\api.router.php'; // Router Module

class V1Controller {

    private $debugEnabled;

    public function __construct() {
        /* Get Server Config */
        $config = new APIConfig();
        $this->debugEnabled = $config->get('debugMode');
    }

    public function run() {
        /* Create a new Slim app */
        $app = $this->createSlim();

        $this->addMiddleware($app);

        $this->addRoutes($app, $this->debugEnabled);

        $this->setResponseHeaders($app);

        /* Start Slim */
        $app->run();
    }

    private function createSlim() {
        /* Create a PHP Slim API */
        return new \Slim\Slim(array('mode' => 'development'));
    }

    // http://www.slimframework.com/docs/concepts/middleware.html
    private function addMiddleware($app) {
        /* Slim-jsonAPI */
        $app->view(new \JsonApiView());
        $app->add(new \JsonApiMiddleware());

        /* Authentication */
        //$app->add(new Middleware\AuthMiddleware());
    }

    private function setResponseHeaders($app) {
        /* Set response content type */
        $app->response->headers->set('Content-Type', 'application/json');
    }

    private function addRoutes($app, $debugEnabled) {
        /* Execute Route */
        $router = new ApiRouter();
        $router->addRoutes($app, $debugEnabled);
    }
}
