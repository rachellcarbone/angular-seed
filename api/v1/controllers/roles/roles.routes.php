<?php namespace API;
 require_once dirname(__FILE__) . '/roles.controller.php';

class RoleRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/role/:roleId/", function ($roleId) use ($app) {
            RoleController::getRole($app, $roleId);
        });
        
        $app->post("/admin/add/role/", function () use ($app) {
            RoleController::addRole($app);
        });
        
        $app->post("/admin/update/role/:roleId/", function ($roleId) use ($app) {
            RoleController::saveRole($app, $roleId);
        });
        
        $app->post("/admin/delete/role/:roleId/", function ($roleId) use ($app) {
            RoleController::deleteRole($app, $roleId);
        });
    }
}