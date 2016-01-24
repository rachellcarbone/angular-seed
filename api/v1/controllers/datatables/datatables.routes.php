<?php namespace API;
 require_once dirname(__FILE__) . '/datatables.controller.php';

class DatatableRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /datatable/admin/ routes - admin users only
        
        $app->group('/datatable/admin', $authenticateForRole('admin'), function () use ($app) {

            $app->post("/users", function () use ($app) {
                DatatablesController::getUsers($app);
            });

            $app->post("/user-groups", function () use ($app) {
                DatatablesController::getUserGroups($app);
            });

            $app->post("/group-roles", function () use ($app) {
                DatatablesController::getGroupRoles($app);
            });

            $app->post("/system-variables", function () use ($app) {
                DatatablesController::getConfigVariables($app);
            });

            $app->post("/visibility-fields", function () use ($app) {
                DatatablesController::getVisibilityFields($app);
            });
            
        });
    }
}