<?php namespace API;
 require_once dirname(__FILE__) . '/roles.controller.php';

class RoleRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
            
        //* /role/ routes - admin users only
        
        $app->group('/role', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/get/:roleId/", function ($roleId) use ($app) {
                RoleController::getRole($app, $roleId);
            })->via('GET', 'POST');

            /*
             * role, desc
             */
            $app->post("/insert/", function () use ($app) {
                RoleController::addRole($app);
            });

            /*
             * id, role, desc
             */
            $app->post("/update/:roleId/", function ($roleId) use ($app) {
                RoleController::saveRole($app, $roleId);
            });

            /*
             * id
             */
            $app->map("/delete/:roleId/", function ($roleId) use ($app) {
                RoleController::deleteRole($app, $roleId);
            })->via('DELETE', 'POST');
            
            /*
             * roleId, fieldId
             */
            $app->post("/unassign-field/", function () use ($app) {
                RoleController::unassignField($app);
            });
            
            /*
             * roleId, fieldId
             */
            $app->post("/assign-field/", function () use ($app) {
                RoleController::assignField($app);
            });
            
            /*
             * roleId, groupId
             */
            $app->post("/unassign-group/", function () use ($app) {
                RoleController::unassignGroup($app);
            });
            
            /*
             * roleId, groupId
             */
            $app->post("/assign-group/", function () use ($app) {
                RoleController::assignGroup($app);
            });
        });
    }
}