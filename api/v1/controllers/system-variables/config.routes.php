<?php namespace API;
 require_once dirname(__FILE__) . '/config.controller.php';

class ConfigRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /config/ routes - admin users only
        
        $app->group('/config', $authenticateForRole('admin'), function () use ($app) {
        
            /*
             * id
             */
            $app->map("/get/:variableId/", function ($variableId) use ($app) {
                ConfigController::getVariable($app, $variableId);
            })->via(['GET', 'POST']);
            
            /*
             *  name
             */
            $app->post("/get/", function () use ($app) {
                ConfigController::getVariableByName($app);
            });
        
            /*
             *  name, value
             */
            $app->post("/insert/", function () use ($app) {
                ConfigController::addVariable($app);
            });

            /*
             * id, indestructible, locked
             */
            $app->post("/update/permissions/:variableId/", function ($variableId) use ($app) {
                ConfigController::saveVariablePermissions($app, $variableId);
            });

            /*
             *  id, name, value, disabled
             */
            $app->post("/update/:variableId/", function ($variableId) use ($app) {
                ConfigController::saveVariable($app, $variableId);
            });

            /*
             * id
             */
            $app->map("/delete/:variableId/", function ($variableId) use ($app) {
                ConfigController::deleteVariable($app, $variableId);
            })->via(['DELETE', 'POST']);
            
        });
        
    }
}