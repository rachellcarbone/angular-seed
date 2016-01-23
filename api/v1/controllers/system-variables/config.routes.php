<?php namespace API;
 require_once dirname(__FILE__) . '/config.controller.php';

class ConfigRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->map("/config/:configId/", function ($configId) use ($app) {
            ConfigController::getConfig($app, $configId);
        })->via('GET', 'POST');
        
        $app->post("/admin/add/config/", function () use ($app) {
            ConfigController::addConfig($app);
        });
        
        $app->post("/admin/save/config/:configId/", function ($configId) use ($app) {
            ConfigController::saveConfig($app, $configId);
        });
        
        $app->map("/admin/delete/config/:configId/", function ($configId) use ($app) {
            ConfigController::deleteConfig($app, $configId);
        })->via('GET', 'POST');
    }
}