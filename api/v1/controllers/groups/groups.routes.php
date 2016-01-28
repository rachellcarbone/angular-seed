<?php namespace API;
 require_once dirname(__FILE__) . '/groups.controller.php';

class GroupRoutes {
    
    static function addRoutes($app, $authenticateForRole) { 
        
        //* /group/ routes - admin users only
        
        $app->group('/group', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/get/:groupId/", function ($groupId) use ($app) {
                GroupController::getGroup($app, $groupId);
            })->via('GET', 'POST');

            /*
             * group, desc
             */
            $app->post("/insert/", function () use ($app) {
                GroupController::addGroup($app);
            });

            /*
             * id, group, desc
             */
            $app->post("/update/:groupId/", function ($groupId) use ($app) {
                GroupController::saveGroup($app, $groupId);
            });

            /*
             * id
             */
            $app->map("/delete/:groupId/", function ($groupId) use ($app) {
                GroupController::deleteGroup($app, $groupId);
            })->via('DELETE', 'POST');
            
        });
    }
}