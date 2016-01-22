<?php namespace API;
 require_once dirname(__FILE__) . '/datatables.controller.php';

class DatatableRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/datatable/admin/users", function () use ($app) {
            DatatablesController::getUsers($app);
        });
        
        $app->post("/datatable/admin/user-groups", function () use ($app) {
            DatatablesController::getUserGroups($app);
        });
        
        $app->post("/datatable/admin/group-roles", function () use ($app) {
            DatatablesController::getGroupRoles($app);
        });
        
        $app->post("/datatable/admin/system-variables", function () use ($app) {
            DatatablesController::getConfigVariables($app);
        });
    }
}