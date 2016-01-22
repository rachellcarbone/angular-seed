<?php namespace API;
 require_once dirname(__FILE__) . '/config.controller.php';

class ConfigRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/config/:configId/", function ($configId) use ($app) {
            ConfigController::getConfig($app, $configId);
        });
        
        $app->post("/admin/add/config/", function () use ($app) {
            ConfigController::addConfig($app);
        });
        
        $app->post("/admin/update/config/:configId/", function ($configId) use ($app) {
            ConfigController::saveConfig($app, $configId);
        });
        
        $app->delete("/admin/delete/config/:configId/", function ($configId) use ($app) {
            ConfigController::deleteConfig($app, $configId);
        });
    }
}