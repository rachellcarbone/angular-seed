<?php namespace API;
 require_once dirname(__FILE__) . '/auth.controller.php';

class AuthRoutes {
    
    static function addRoutes() {
        $app = \Slim\Slim::getInstance();
        
        // Authentication
        
        $app->post("/auth/login", function () use ($app) {
            AuthController::login($app);
        });
        
        $app->post("/auth/logout", function () use ($app) {
            AuthController::logout($app);
        });
        
        $app->post("/auth/validate-password", function () use ($app) {
            AuthController::testValidatePassword($app);
        });
        
        // Email Managment
        
        $app->post("/auth/send-email/validate-new-email", function () use ($app) {
            AuthController::sendPasswordResetEmail($app);
        });
        
        $app->post("/auth/validate-new-email-token", function () use ($app) {
            AuthController::validateResetToken($app);
        });
        
        // Forgot Password        
        
        $app->post("/auth/send-email/reset-password", function () use ($app) {
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