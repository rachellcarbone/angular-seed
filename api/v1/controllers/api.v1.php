<?php
namespace API;

require_once dirname(dirname(__FILE__)) . '/services/ApiConfig.php';     // API Coifg File (Add your settings!)
require_once dirname(dirname(__FILE__)) . '/services/ApiLogging.php';  // Router Module
require_once dirname(dirname(__FILE__)) . '/services/api.auth.php'; // Auth Service
require_once dirname(dirname(__FILE__)) . '/slimMiddleware/JsonResponseView.php'; // Response middleware to neatly format API responses to JSON
require_once dirname(dirname(__FILE__)) . '/slimMiddleware/RouteAuthenticationMiddleware.php'; // Slim PHP Middleware to authenticate incomming requests for individual routes

/* API Route Controllers */
require_once dirname(__FILE__) . '/action-tracking/actions.routes.php';
require_once dirname(__FILE__) . '/api-test/test.routes.php';
require_once dirname(__FILE__) . '/auth/auth.routes.php';
require_once dirname(__FILE__) . '/datatables/datatables.routes.php';
require_once dirname(__FILE__) . '/emails/emails.routes.php';
require_once dirname(__FILE__) . '/field-visibility/fields.routes.php';
require_once dirname(__FILE__) . '/groups/groups.routes.php';
require_once dirname(__FILE__) . '/roles/roles.routes.php';
require_once dirname(__FILE__) . '/simple-lists/lists.routes.php';
require_once dirname(__FILE__) . '/system/system.routes.php';
require_once dirname(__FILE__) . '/system-variables/config.routes.php';
require_once dirname(__FILE__) . '/user/user.routes.php';

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class V1Controller {

    public function run() {
        /* Get our system config */
        $systemConfig = new ApiConfig();
        
        /* Create a new Slim app */
        $slimApp = $this->createSlim($systemConfig->get('debugMode'));

        /* Format the API Response as JSON */
        //$slimApp->add(new \API\JsonResponseView());

        /* Add API Routes */
        $this->addRoutes($slimApp, $systemConfig->get('debugMode'));

        /* Start Slim */
        $slimApp->run();
    }

    private function createSlim($debugEnabled) {
        $slimSettings = $this->getSlimConfig($debugEnabled);
        
        $slimContainer = $this->getSlimContainer($slimSettings, $debugEnabled);

        /* Create new Slim PHP API App */
        // http://www.slimframework.com/docs/objects/application.html
        $slimApp = new \Slim\App($slimContainer);
        
        return $slimApp;
    }

    private function getSlimConfig($debugEnabled) {
        /* Slim PHP Congif Settings */
        // http://www.slimframework.com/docs/objects/application.html

        $slimSettings = [
            'settings' => [
                /* If false, then no output buffering is enabled. If 'append' or 'prepend', 
                 * then any echo or print statements are captured and are either appended 
                 * or prepended to the Response returned from the route callable. 
                 * (Default: 'append') */
                'outputBuffering' => 'append',
                /* When true, the route is calculated before any middleware is executed. 
                 * This means that you can inspect route parameters in middleware if you need to. 
                 * (Default: false) */
                'determineRouteBeforeAppMiddleware' => true,
                /* When true, additional information about exceptions are displayed by the default error handler. 
                 * (Default: false) */
                'displayErrorDetails' => true,
                /* Filename for caching the FastRoute routes. Must be set to to a valid filename 
                 * within a writeable directory. If the file does not exist, then it is created 
                 * with the correct cache information on first run.
                 * Set to false to disable the FastRoute cache system. 
                 * (Default: false) */
                'routerCacheFile' => false,
                /* When true, Slim will add a Content-Length header to the response. If you are using a 
                 * runtime analytics tool, such as New Relic, then this should be disabled. 
                 * (Default: true) */
                'addContentLengthHeader' => true
            ]
        ];

        /* Dev Mode / Debug Settings */
        /* if($debugEnabled) { } */
        
        return $slimSettings;
    }

    private function getSlimContainer($slimSettings, $debugEnabled) {
        /* Create new Slim PHP Dependency Container */
        // http://www.slimframework.com/docs/concepts/di.html
        $slimContainer = new \Slim\Container($slimSettings);

        /* Add app services to the container */

        /* An instance of \API\ApiConfig */
        $slimContainer['_ApiConfig'] = function($this) {
            /* Return the System Config Class */
            $ApiConfig = new \API\ApiConfig(); 
            return $ApiConfig;
        };

        /* Create an instance of \API\ApiLogging */
        $slimContainer['_ApiLogging'] = function($this) {
            /* Set PHP Error Handler to ApiLogging */
            $logging = new \API\ApiLogging($this->_ApiConfig); 
            return $logging;
        };

        /* Create an instance of \API\DBConn */
        $slimContainer['_DBConn'] = function($this) {
            /* Return the Database Connection Class */
            $db = new \API\DBConn($this->_ApiConfig, $this->_ApiLogging); 
            return $db;
        };

        /* An instance of \API\SystemConfig */
        $slimContainer['_SystemConfig'] = function($this) {
            /* Return the System Config Class */
            $systemConfig = new \API\SystemConfig($this->_DBConn, $this->_ApiLogging); 
            return $systemConfig;
        }; 
        
        $slimContainer['view'] = new \API\JsonResponseView();

        /* Dev Mode / Debug Settings */
        /* if(!$debugEnabled) { 
            $this->addErrorHandlers($slimContainer);
        } */

        $this->addErrorHandlers($slimContainer);
        
        return $slimContainer;
    }
    
    public function addRoutes($slimApp, $debugEnabled) {
        
        $authenticateForRole = function ($role = 'public') use ($slimApp) {
            return function () use ($slimApp, $role) {
                APIAuth::isAuthorized($slimApp, $role);
            };
        };

        $this->addDefaultRoutes($slimApp);
        //$this->addErrorRoutes($slimApp, $debugEnabled);
        
        /*
        TestRoutes::addRoutes($slimApp, $authenticateForRole);
        ActionRoutes::addRoutes($slimApp, $authenticateForRole);
        AuthRoutes::addRoutes($slimApp, $authenticateForRole);
        DatatableRoutes::addRoutes($slimApp, $authenticateForRole);
        EmailRoutes::addRoutes($slimApp, $authenticateForRole);
        FieldRoutes::addRoutes($slimApp, $authenticateForRole);
        GroupRoutes::addRoutes($slimApp, $authenticateForRole);
        RoleRoutes::addRoutes($slimApp, $authenticateForRole);
        ListRoutes::addRoutes($slimApp, $authenticateForRole);
        SystemRoutes::addRoutes($slimApp, $authenticateForRole);
        ConfigRoutes::addRoutes($slimApp, $authenticateForRole);
        UserRoutes::addRoutes($slimApp, $authenticateForRole);
        */
    }
    
    private function addDefaultRoutes($slimApp) {
        $slimApp->any('/', function ($request, $response, $args) {
            $this->view->render($response, 222, 'Congratulations, you have reached the Slim PHP API v1.1!');
        });
        
        $slimApp->any('/about-this-api/possible-headers', function ($request, $response, $args) {
            $headers = $response->getHeaders();
            foreach ($headers as $name => $values) {
                echo $name . ": " . implode(", ", $values);
            }
        });
        
    }
    
    private function addErrorHandlers($slimContainer) {
        
        $slimContainer['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($container) {
                return $c['response']->withStatus(500)
                                    ->withHeader('Content-Type', 'text/html')
                                    ->write('Something went wrong!');
            };
        };

        return $slimContainer;
    }
}

/*
 * APILogWriter: Custom log writer for our application
 * We must implement write(mixed $message, int $level) */
class APILogWriter extends ApiLogging {
    public function write($message, $level = SlimLog::DEBUG) {
        /* Set PHP Error Handler to ApiLogging */
        $logger = new ApiLogging(new ApiConfig(), 'api_log'); 
        $logger->write("[Slim API : Level {$level}] - {$message}");
    }
}