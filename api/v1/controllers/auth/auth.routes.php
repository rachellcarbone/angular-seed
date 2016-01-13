<?php namespace API;
 require_once dirname(__FILE__) . '/auth.controller.php';

class AuthRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        $app->post("/auth/login", function () use ($app) {
            AuthController::authenticate($app);
        });
        
        $app->post("/auth/logout", function () use ($app) {
            AuthController::logout($app);
        });
        
        $app->post("/auth/reset-password-email", function () use ($app) {
            AuthController::sendPasswordResetEmail($app);
        });
        
        $app->post("/auth/validate-reset-token", function () use ($app) {
            AuthController::validateResetToken($app);
        });
        
        $app->post("/auth/change-password", function () use ($app) {
            AuthController::changePasword($app);
        });
        
    }
}