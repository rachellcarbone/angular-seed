<?php namespace API;
 require_once dirname(__FILE__) . '/user.controller.php';

class UserRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->get("/user/:userId/", function ($userId) use ($app) {
            UserController::selectUser($app, $userId);
        });
        
        $app->post("/user/", function () use ($app) {
            UserController::insertUser($app);
        });
        
        $app->post("/user/:userId/", function ($userId) use ($app) {
            UserController::updateUser($app, $userId);
        });
        
        $app->delete("/delete/user/:userId/", function ($userId) use ($app) {
            UserController::deleteUser($app, $userId);
        });
    }
}