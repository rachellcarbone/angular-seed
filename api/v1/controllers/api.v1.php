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
        $slimContainer['_ApiLogging'] = function($container) {
            /* Set PHP Error Handler to ApiLogging */
            $logging = new \API\ApiLogging($container['_ApiConfig'], 'api'); 
            return $logging;
        };

        /* Create an instance of \API\DBConn */
        $slimContainer['_DBConn'] = function($container) {
            /* Return the Database Connection Class */
            $db = new \API\DBConn($container['_ApiConfig'], $container['_ApiLogging']); 
            return $db;
        };

        /* An instance of \API\SystemConfig */
        $slimContainer['_SystemConfig'] = function($container) {
            /* Return the System Config Class */
            $systemConfig = new \API\SystemConfig($container['_DBConn'], $container['_ApiLogging']); 
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
            return $this->view->render($response, 200, 'Congratulations, you have reached the Slim PHP API v1.1!');
        });
        
        $slimApp->any('/about-this-api', function ($request, $response, $args) {
            $data = array(
                'title' => 'Angular Seed Slim PHP API',
                'version' => $this['_ApiConfig']->get('apiVersion'),
                'author' => 'Rachel L Carbone <hello@rachellcarbone.com>',
                'website' => 'https://gitlab.com/rachellcarbone/angular-seed'
            );
            return $this->view->render($response, 200, $data);
        });
        
    }
    
    private function addErrorHandlers($slimContainer) {
        /*
         * Override the default Not Found Handler
         *
         * http://www.slimframework.com/docs/handlers/error.html
         */
        $slimContainer['errorHandler'] = function ($container) {
            return function ($request, $response, $exception) use ($container) {
                // Build log message
                $msg = '[500] System Error';
                // https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php
                if($request->getAttribute('routeInfo')) {
                    $req = $request->getAttribute('routeInfo');
                    // Add the type (GET, POST, etc) and Route (http://api.seed.com/path/path)
                    if(isset($req['request'])) {
                        $type = (isset($req['request'][0])) ? $req['request'][0] : 'unknown';
                        $path = (isset($req['request'][1])) ? $req['request'][1] : 'unknown';
                        $msg .= " - [{$type}] $path";
                    }
                }
                // Log the 500
                $container['_ApiLogging']->log($msg, 'error', 'api_errors');
                $container['_ApiLogging']->logException($exception, 'error', 'api_errors');

                // Return nice JSON 500 Message
                return $container['view']->render($response, 500, 'Unknown System Error');
            };
        };

        /*
         * If your Slim Framework application has a route that matches the current HTTP request URI 
         * but NOT the HTTP request method, the application invokes its Not Allowed handler and 
         * returns a HTTP/1.1 405 Not Allowed response to the HTTP client.
         *
         * http://www.slimframework.com/docs/handlers/not-allowed.html
         */
        $slimContainer['notAllowedHandler'] = function ($container) {
            return function ($request, $response, $methods) use ($container) {                    
                // Build log message
                $msg = '[405] Invalid method requested for app route.';
                // https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php
                if($request->getAttribute('routeInfo')) {
                    $req = $request->getAttribute('routeInfo');
                    // Add the type (GET, POST, etc) and Route (http://api.seed.com/path/path)
                    if(isset($req['request'])) {
                        $type = (isset($req['request'][0])) ? $req['request'][0] : 'unknown';
                        $path = (isset($req['request'][1])) ? $req['request'][1] : 'unknown';
                        $msg .= " - [{$type}] $path";
                    }
                }
                $msg .= ' Accepted Methods: ' . implode(', ', $methods);

                // Log the 405
                $container['_ApiLogging']->log($msg, 'debug');

                // Return nice JSON 405 Message
                return $container['view']->render($response, 405, 'This header method is not defined for this route. Accepted method(s) are: ' . implode(', ', $methods));
            };
        };

        /*
         * Override the default Not Found Handler
         *
         * http://www.slimframework.com/docs/handlers/not-found.html
         */
        $slimContainer['notFoundHandler'] = function ($container) {
            return function ($request, $response) use ($container) {
                // Build log message
                $msg = '[404] Undefined app route was requested';
                // https://github.com/php-fig/http-message/blob/master/src/ServerRequestInterface.php
                if($request->getAttribute('routeInfo')) {
                    $req = $request->getAttribute('routeInfo');
                    // Add the type (GET, POST, etc) and Route (http://api.seed.com/path/path)
                    if(isset($req['request'])) {
                        $type = (isset($req['request'][0])) ? $req['request'][0] : 'unknown';
                        $path = (isset($req['request'][1])) ? $req['request'][1] : 'unknown';
                        $msg .= " - [{$type}] $path";
                    }
                }
                // Log the 404
                $container['_ApiLogging']->log($msg, 'debug');

                // Return nice JSON 404 Message
                return $container['view']->render($response, 404, 'This API route is not defined.');
            };
        };

        return $slimContainer;
    }
}