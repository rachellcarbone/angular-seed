<?php namespace API;
 require_once dirname(__FILE__) . '/user.controller.php';

class UserRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /user/id - members can get their own profile
        
        $app->map("/user/:userId/", $authenticateForRole('member'), function ($userId) use ($app) {
            UserController::selectUser($app, $userId);
        })->via('GET', 'POST');
            
        //* /user/ routes - admin only

        $app->group('/user', $authenticateForRole('admin'), function () use ($app) {

            $app->post("/insert/", function () use ($app) {
                UserController::insertUser($app);
            });

            $app->post("/update/:userId/", function ($userId) use ($app) {
                UserController::updateUser($app, $userId);
            });

            $app->map("/delete/:userId/", function ($userId) use ($app) {
                UserController::deleteUser($app, $userId);
            })->via('DELETE', 'POST');
            
        });
    }
}