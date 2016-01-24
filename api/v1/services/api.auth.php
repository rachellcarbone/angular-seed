<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';
require_once dirname(dirname(__FILE__)) . '/controllers/auth/auth.controller.php';

class APIAuth {    
    
    const APISESSIONNAME = 'API_AUTHENTICATED_USER_ID';
    
    static function isAuthorized($app, $role = 'public') {
        if(strtolower($role) === 'public') {
            return true;
        }
        
        $user = AuthController::authorizeApiToken($app);
        if($user) {
            // Save that user id
            $_SESSION[self::APISESSIONNAME] = $user;
            return true;
        } else {
            $response = array('data' => array(
                'msg' => 'Unauthorized API Access', 
                'sent' => $app->request->post()), 
                'user' => $user,
                'meta' => array('error' => true, 'status' => 401));
            //$response = array('data' => array('msg' => 'Unauthorized API Access'), 'meta' => array('error' => true, 'status' => 401));
            $app->halt(401, json_encode($response));
            return false;
        }
    }
    
    static function getUserId() {        
        return (isset($_SESSION[self::APISESSIONNAME])) ? $_SESSION[self::APISESSIONNAME] : '0';
    }
    
}