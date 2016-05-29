<?php namespace API;
 require_once dirname(__FILE__) . '/roles.controller.php';

class RoleRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
            
        //* /role/ routes - admin users only
        
        $app->group('/store/admin', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
            $app->map("/get/:roleId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');
             */
        });
        
        $app->group('/store/category', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/:categoryId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');
        });
        
        $app->group('/store/tag', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/:tagId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');
        });
        
        $app->group('/store/product', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/:productId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');
        });
    }
}