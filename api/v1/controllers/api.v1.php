<?php namespace API;

require_once dirname(dirname(__FILE__)) . '\vendor\autoload.php';   // Composer components
require_once dirname(dirname(__FILE__)) . '\config\config.php';     // API Coifg File (Add your settings!)
require_once dirname(dirname(__FILE__)) . '\services\logging.php';  // Logging Service
require_once dirname(dirname(__FILE__)) . '\routes\api.router.php'; // Router Module

class V1Controller {

    private $debugEnabled;
    
    public function __construct() {
        /* Get Server Config */
        $config = new Data\APIConfig();
        $this->debugEnabled = $config->get('debugMode');
    }

    public function run() {
        /* Create a new Slim app */
        $app = $this->createSlim($this->debugEnabled);

        $this->setResponseView($app);

        $this->setResponseHeaders($app);

        //$this->addMiddleware($app);
        
        $this->setPHPLogging();

        $this->addRoutes($app, $this->debugEnabled);
        
        /* Start Slim */
        $app->run();
    }

    private function createSlim($debugEnabled) {
        /* Create a PHP Slim API */
        return new \Slim\Slim(array(
            'mode' => 'development',
            'log.enabled' => $debugEnabled,
            'log.level' => \Slim\Log::DEBUG,
            'log.writer' => new APILogWriter()
        ));
    }
    
    // http://www.slimframework.com/docs/concepts/middleware.html
    private function addMiddleware($app) {
        /* Authentication */
        //$app->add(new Middleware\AuthMiddleware());
    }

    private function setResponseHeaders($app) {
        /* Set response content type */
        $app->response->headers->set('Content-Type', 'application/json');
    }

    private function addRoutes($app, $debugEnabled) {
        /* Execute Route */
        $router = new ApiRouter($debugEnabled);
        $router->addRoutes($app);
    }
    
    private function setResponseView($app) {
        /* Slim-jsonAPI */
        $app->view(new \JsonApiView());
        $app->add(new \JsonApiMiddleware());
    }
    
    private function setPHPLogging() {
        /* Set PHP Error Handler to Logging */
        $Log = new Data\Logging();
        
        // http://php.net/manual/en/function.set-error-handler.php
        set_error_handler(array($Log, 'loggingErrorHandler'));
        
        // http://php.net/manual/en/function.set-exception-handler.php
        set_exception_handler (array($Log, 'loggingExceptionHandler'));

    }

}

/*
 * APILogWriter: Custom log writer for our application
 * We must implement write(mixed $message, int $level) */

class APILogWriter extends Data\Logging {

    public function write($message, $level = SlimLog::DEBUG) {
        $this->log("[Slim API : Level {$level}] - {$message}");
    }

} 
