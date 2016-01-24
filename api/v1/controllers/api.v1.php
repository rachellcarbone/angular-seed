<?php
namespace API;

require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';   // Composer components
require_once dirname(dirname(__FILE__)) . '/config/config.php';     // API Coifg File (Add your settings!)
require_once dirname(dirname(__FILE__)) . '/services/logging.php'; // Router Module
require_once dirname(dirname(__FILE__)) . '/controllers/auth/auth.controller.php'; // Router Module
require_once dirname(__FILE__) . '/api.router.php'; // Router Module

class V1Controller {

    public static function run() {
        $config = new APIConfig();
        
        /* Create a new Slim app */
        $app = self::createSlim();

        self::addMiddleware($app);
        
        ApiRouter::addRoutes($app, $config->get('debugMode'));

        self::setResponseHeaders($app);

        /* Start Slim */
        $app->run();
    }

    private static function createSlim() {
        /* 
         * Create a PHP Slim API 
         * 
         * http://docs.slimframework.com/configuration/modes/
         */
        $app = new \Slim\Slim(array(
            'mode' => 'development'
        ));
        
        // Only invoked if mode is "production"
        $app->configureMode('production', function () use ($app) {
            $app->config(array(
                'log.enable' => true,
                'debug' => false
            ));
        });

        // Only invoked if mode is "development"
        $app->configureMode('development', function () use ($app) {
            $app->config(array(
                //'log.enable' => false,
                'debug' => true,
                
                'log.enabled' => true,
                'log.level' => \Slim\Log::DEBUG,
                'log.writer' => new APILogWriter()
            ));
        });
        
        return $app;
    }

    // http://docs.slimframework.com/middleware/overview/
    private static function addMiddleware($app) {
        
        /* Slim-jsonAPI */
        $app->view(new \JsonApiView('data', 'meta'));
        $app->add(new \JsonApiMiddleware());
        
        /* Authentication */
        $app->add(new AuthMiddleware());
        
        
    }

    private static function setResponseHeaders($app) {
        /* Set response content type */
        $app->response->headers->set('Content-Type', 'application/json');
    }
}


class AuthMiddleware extends \Slim\Middleware {
    public function call()
    {
        //The Slim application
        $app = $this->app;

        //The Environment object
        //$env = $app->environment;

        //The Request object
        //$req = $app->request;
        
        //The Response object
        //$res = $app->response;
        
        $user = AuthController::authorizeApiToken($app);
        if(!$user) {
            // Save that user id
            $_SESSION['authenticatedApiUser'] = $user;
            //Optionally call the next middleware
            $this->next->call();
        } else {
            $response = array('data' => array('msg' => 'Unauthorized API Access'), 'meta' => array('error' => true, 'status' => 401));
            $app->response()->body(json_encode($app->request->params));
            $app->response()->status(401);
            $app->response()->headers->set('Content-Type', 'application/json');
        }

        
    }
}

/*
 * APILogWriter: Custom log writer for our application
 * We must implement write(mixed $message, int $level) */
class APILogWriter extends Logging {
    public function write($message, $level = SlimLog::DEBUG) {
        /* Set PHP Error Handler to Logging */
        $logger = new Logging('api_log'); 
        $logger->write("[Slim API : Level {$level}] - {$message}");
    }
}