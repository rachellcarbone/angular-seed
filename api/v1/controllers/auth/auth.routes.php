<?php namespace API;
 require_once dirname(__FILE__) . '/auth.controller.php';
 require_once dirname(__FILE__) . '/auth.additionalInfo.controller.php';

class AuthRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        ///// 
        // System Admin
        // TODO: Create system functions route
        ///// 
        
        $app->map("/admin/auth/delete/expired-tokens/", $authenticateForRole('admin'), function () use ($app) {
            AuthController::deleteExpiredAuthTokens($app);
        })->via(['DELETE', 'POST']);

        /*
         * id, nameFirst, nameLast, email
         */
        $app->post("/user/update/password/", $authenticateForRole('member'), function () use ($app) {
            AuthController::changeUserPassword($app);
        });
        
        
        //* /auth/ routes - publicly accessable        
            
        $app->group('/auth', $authenticateForRole('public'), function () use ($app) {
            
            ///// 
            ///// Authentication
            ///// 

            $app->post("/authenticate/", function () use ($app) {
                AuthController::isAuthenticated($app);
            });
            
            ///// 
            ///// Sign Up
            ///// 

            $app->post("/signup/", function () use ($app) {
                AuthController::signup($app);
            });

            $app->post("/signup/facebook/", function () use ($app) {
                AuthController::facebookSignup($app);
            });

            $app->post("/signup/additional/", function () use ($app) {
                InfoController::saveAdditional($app);
            });
            
            ///// 
            ///// Login
            ///// 
            
            /*
             * email, passowrd
             */
            $app->post("/login/", function () use ($app) {
                AuthController::login($app);
            });
            
            $app->post("/login/facebook/", function () use ($app) {
                AuthController::facebookLogin($app);
            });
            
            ///// 
            ///// Logout
            ///// 

            $app->post("/logout/", function () use ($app) {
                AuthController::logout($app);
            });
            
        });
        
    }
}