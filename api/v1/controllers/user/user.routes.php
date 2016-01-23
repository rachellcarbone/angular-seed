<?php namespace API;
 require_once dirname(__FILE__) . '/user.controller.php';

class UserRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->map("/user/:userId/", function ($userId) use ($app) {
            UserController::selectUser($app, $userId);
        })->via('GET', 'POST');
        
        $app->post("/user/add/", function () use ($app) {
            UserController::insertUser($app);
        });
        
        $app->post("/user/save/:userId/", function ($userId) use ($app) {
            UserController::updateUser($app, $userId);
        });
        
        $app->delete("/user/delete/:userId/", function ($userId) use ($app) {
            UserController::deleteUser($app, $userId);
        })->via('GET', 'POST');
    }
}