<?php namespace API;
 require_once dirname(__FILE__) . '/groups.controller.php';

class GroupRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/group/:groupId/", function ($groupId) use ($app) {
            GroupController::getGroup($app, $groupId);
        });
        
        $app->post("/admin/add/group/", function () use ($app) {
            GroupController::addGroup($app);
        });
        
        $app->post("/admin/update/group/:groupId/", function ($groupId) use ($app) {
            GroupController::saveGroup($app, $groupId);
        });
        
        $app->post("/admin/delete/group/:groupId/", function ($groupId) use ($app) {
            GroupController::deleteGroup($app, $groupId);
        });
    }
}