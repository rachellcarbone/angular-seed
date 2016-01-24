<?php namespace API;
 require_once dirname(__FILE__) . '/config.controller.php';

class ConfigRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /config/ routes - admin users only
        
        $app->group('/config', $authenticateForRole('admin'), function () use ($app) {

            $app->map("/:variableId/", function ($variableId) use ($app) {
                ConfigController::getVariable($app, $variableId);
            })->via('GET', 'POST');
        
            $app->post("/insert/", function () use ($app) {
                ConfigController::addVariable($app);
            });

            $app->post("/update/:variableId/", function ($variableId) use ($app) {
                ConfigController::saveVariable($app, $variableId);
            });

            $app->map("/delete/:variableId/", function ($variableId) use ($app) {
                ConfigController::deleteVariable($app, $variableId);
            })->via('DELETE', 'POST');
            
        });
        
    }
}