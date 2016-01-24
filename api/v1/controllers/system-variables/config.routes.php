<?php namespace API;
 require_once dirname(__FILE__) . '/config.controller.php';

class ConfigRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/admin/add/config/", function () use ($app) {
            ConfigController::addVariable($app);
        });
        
        $app->post("/admin/save/config/:variableId/", function ($variableId) use ($app) {
            ConfigController::saveVariable($app, $variableId);
        });
        
        $app->map("/admin/delete/config/:variableId/", function ($variableId) use ($app) {
            ConfigController::deleteVariable($app, $variableId);
        })->via('GET', 'POST');
    }
}