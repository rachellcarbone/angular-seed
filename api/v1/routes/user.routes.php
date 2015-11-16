<?php namespace API;

class UserRoutes {

    public function __construct() {}
    
    static function addRoutes($app) {
        
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
        $app->map("/user/:userId", function () use ($app, $class, $method) {
            $apiRoute = new Users();
            $apiRoute->getUser($app);
        });
    }
}