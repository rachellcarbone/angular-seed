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

            $app->post("/login/facebook/", function () use ($app) {
                AuthController::facebookLogin($app);
            });
            
            /*
             * email, passowrd
             */
            $app->post("/login/", function () use ($app) {
                AuthController::login($app);
            });

            $app->post("/signup/facebook/", function () use ($app) {
                AuthController::facebookSignup($app);
            });

            $app->post("/signup/", function () use ($app) {
                AuthController::signup($app);
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
            
        });
        
    }
}