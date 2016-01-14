<?php namespace API;
 require_once dirname(__FILE__) . '/user.controller.php';

class UserRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        /**
         * @api {get} /user/:userId Get User
         * @apiVersion 2.0.0
         * @apiName getUser
         * @apiGroup Users
         * @apiPermission none
         * 
         * @apiDescription Request a user by id.
         * 
         * @apiSuccess {int} status HTTP Header Status Code.
         * @apiSuccess {bool} error False if no errors occured.
         * @apiSuccess {Object} user The requested data.
         * @apiSuccess {Object} msg.user Array of user objects.
         * @apiSuccess {int} msg.user.id False if no errors occured.
         * @apiSuccess {String} msg.user.firstName The users first name.
         * @apiSuccess {String} msg.user.lastName The users last name.
         * @apiSuccess {String} msg.user.email The users primary email.
         * @apiSuccess {int} msg.user.roleId User role id.
         */
        $app->get("/users/", function () use ($app) {
            UserController::getUsers($app);
        });
        
        $app->get("/user/:userId/", function ($userId) use ($app) {
            UserController::getUser($app, $userId);
        });
        
        $app->post("/user/", function () use ($app) {
            UserController::addUser($app);
        });
        
        $app->post("/user/:userId/", function ($userId) use ($app) {
            UserController::saveUser($app, $userId);
        });
        
        $app->delete("/delete/user/:userId/", function ($userId) use ($app) {
            UserController::deleteUser($app, $userId);
        });
    }
}