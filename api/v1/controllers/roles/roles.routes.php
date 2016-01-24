<?php namespace API;
 require_once dirname(__FILE__) . '/roles.controller.php';

class RoleRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
            
        //* /role/ routes - admin users only
        
        $app->group('/role', $authenticateForRole('admin'), function () use ($app) {
            
            $app->map("/:roleId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');

            $app->post("/insert/", function () use ($app) {
                RoleController::addRole($app);
            });

            $app->post("/update/:roleId/", function ($roleId) use ($app) {
                RoleController::saveRole($app, $roleId);
            });

            $app->map("/delete/:roleId/", function ($roleId) use ($app) {
                RoleController::deleteRole($app, $roleId);
            })->via('DELETE', 'POST');
            
        });
    }
}