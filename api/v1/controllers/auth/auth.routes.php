<?php namespace API;
 require_once dirname(__FILE__) . '/auth.controller.php';

class AuthRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        
        $app->map("/admin/auth/delete/expired-tokens/", $authenticateForRole('admin'), function () use ($app) {
            AuthController::deleteExpiredAuthTokens($app);
        })->via(['DELETE', 'POST']);
        
        
        //* /auth/ routes - publicly accessable        
            
        $app->group('/auth', $authenticateForRole('public'), function () use ($app) {

            // Authentication

            $app->post("/login/", function () use ($app) {
                AuthController::login($app);
            });

            $app->post("/logout/", function () use ($app) {
                AuthController::logout($app);
            });

            $app->post("/authenticate/", function () use ($app) {
                AuthController::isAuthenticated($app);
            });

            $app->post("/validate-password/", function () use ($app) {
                AuthController::testValidatePassword($app);
            });

            // Email Managment

            $app->post("/send-email/validate-new-email/", function () use ($app) {
                AuthController::sendPasswordResetEmail($app);
            });

            $app->post("/validate-new-email-token/", function () use ($app) {
                AuthController::validateResetToken($app);
            });

            // Forgot Password        

            $app->post("/send-email/reset-password/", function () use ($app) {
                AuthController::sendPasswordResetEmail($app);
            });

            $app->post("/validate-reset-token/", function () use ($app) {
                AuthController::validateResetToken($app);
            });

            $app->post("/change-password/", function () use ($app) {
                AuthController::changePasword($app);
            });
            
        });
        
    }
}