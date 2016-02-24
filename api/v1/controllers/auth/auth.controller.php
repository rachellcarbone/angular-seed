<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';
require_once dirname(__FILE__) . '/auth.additionalInfo.data.php';
require_once dirname(__FILE__) . '/auth.controller.native.php';
require_once dirname(__FILE__) . '/auth.controller.facebook.php';

use \Respect\Validation\Validator as v;

class AuthController {
    
    ///// 
    ///// Authentication
    ///// 
            
    /*
     * apiKey, apiToken
     */
    static function isAuthenticated($app) {
        $found = AuthControllerNative::isAuthenticated($app);
        if($found['authenticated']) {
            return $app->render(200, $found);
        }  else {
            return $app->render(401, "Unauthenticated");
        }
        
        /*
        $fb = AuthControllerFacebook::isAuthenticated($app);
        if($fb) {
            return $app->render(222, $found);
        } else {
            return $app->render(400, $found);
        }
        */
    }
            
    ///// 
    ///// Sign Up
    ///// 
            
    /*
     * email, nameFirst, nameLast, password
     */
    static function signup($app) {
        $result = AuthControllerNative::signup($app);
        if($result['registered']) {
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }
    
    static function facebookSignup($app) {
        $result = AuthControllerFacebook::signup($app);
        if($result['registered']) {
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    ///// 
    ///// Authentication
    ///// 
              
    /*
     * email, password, remember
     */
    static function login($app) {
        $result = AuthControllerNative::login($app);
        if($result['authenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(401, $result);
        }
    }
    
    static function facebookLogin($app) {
        $result = AuthControllerFacebook::login($app);
        if($result['authenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(401, $result);
        }
    }
            
    ///// 
    ///// Logout
    ///// 

    /*
     * logout (apiKey)
     */
    static function logout($app) {
        if(AuthControllerNative::logout($app)) {
            return $app->render(200, array('msg' => "User sucessfully logged out." ));
        } else {
            return $app->render(400, array('msg' => "User could not be logged out. Check your parameters and try again." ));
        }
    }
        
    ///// 
    // System Admin
    // TODO: Create system functions class
    ///// 
    
    // TODO: Add this to Cron Job
    static function deleteExpiredAuthTokens($app) {
        AuthData::deleteExpiredAuthTokens($app);
        return $app->render(200, array('msg' => "Deleted expired auth tokens." ));
    }
    
}
