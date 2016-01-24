<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/services/logging.php';  // Logging Service
require_once dirname(__FILE__) . '/auth/auth.routes.php';
require_once dirname(__FILE__) . '/datatables/datatables.routes.php';
require_once dirname(__FILE__) . '/field-visibility/fields.routes.php';
require_once dirname(__FILE__) . '/groups/groups.routes.php';
require_once dirname(__FILE__) . '/roles/roles.routes.php';
require_once dirname(__FILE__) . '/system-variables/config.routes.php';
require_once dirname(__FILE__) . '/user/user.routes.php';

class ApiRouter {
    
    public static function addRoutes($app, $debugEnabled, $isAuthorized) {
        self::addDefaultRoutes();
        //self::addErrorRoutes($app, $debugEnabled);
        
        AuthRoutes::addRoutes();
        DatatableRoutes::addRoutes();
        FieldRoutes::addRoutes($isAuthorized);
        GroupRoutes::addRoutes();
        RoleRoutes::addRoutes();
        ConfigRoutes::addRoutes();
        UserRoutes::addRoutes();
    }
    
    
    private static function addDefaultRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->get('/',  function () use ($app) {
            $app->render(418, array("msg" => "Congratulations, you have reached the Slim PHP API v1!"));
        });
    }
    
    private static function addErrorRoutes() {
        /*
        $logger = new Logging('router_warning');
        
        $c = $app->getContainer();
        
        $c['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($c) {
                return $c['response']->withStatus(500)
                                ->withHeader('Content-Type', 'text/html')
                                ->write('Something went wrong!');
            };
        };
        
        //Override the default Not Found Handler
        $c['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                return $c['response']
                                ->withStatus(404)
                                ->withHeader('Content-Type', 'text/html')
                                ->write('Page not found');
            };
        };*
         * @apiDefine ResourceNotFoundError
         *
         * @apiError ResourceNotFound The requested route was not found.
         *
         * @apiErrorExample Error-Response:
         *     HTTP/1.1 404 Not Found
         *     {
         *          "status": 404,
         *          "error": true,
         *          "msg": "Error 404: Requested resource could not be found."
         *     }
         
        $app->notFound(function ($debugEnabled) use ($app, $debugEnabled) {
            if ($debugEnabled) {
                // Get request object
                $req = $app->request;
                $this->warningLog->log('[Error 404] From ' . $req->getIp() . ' - To: ' . $req->getUrl() . $req->getRootUri() . $req->getResourceUri());
            }
            $app->render(404, array("msg" => "Error 404: Requested resource could not be found."));
        });*/
        
        /**
         * @apiDefine InternalError
         *
         * @apiError InternalError The system experienced an error.
         *
         * @apiErrorExample Error-Response:
         *     HTTP/1.1 500 Internal Server Error
         *     {
         *          "status": 500,
         *          "error": true,
         *          "msg": "Empty response."
         *     }
         
        $app->error(function (\Exception $e) use ($app) {
                $this->warningLog->logException($e);
            $app->render(500, array("msg" => $e->getMessage()));
        });*/
    }

}
