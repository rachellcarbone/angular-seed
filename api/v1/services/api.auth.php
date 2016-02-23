<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';
require_once dirname(dirname(__FILE__)) . '/controllers/auth/auth.data.php';

use \Respect\Validation\Validator as v;

class APIAuth {    
    
    const APISESSIONNAME = 'API_AUTHENTICATED_USER_ID';
    
    static function isAuthorized($app, $role = 'public') {
        if(strtolower($role) === 'public') {
            return true;
        }
        
        $user = self::authorizeApiToken($app);
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
    
    private static function authorizeApiToken($app) {
        if(!v::key('apiKey', v::stringType())->validate($app->request->post()) || 
           !v::key('apiToken', v::stringType())->validate($app->request->post())) {
            return false;
        }
        $user = AuthData::selectUserByIdentifierToken($app->request->post('apiKey'));
        if(!$user) {
            return "user";
        }
        if(!password_verify($app->request->post('apiToken'), $user->apiToken)) {
            return "password";
        }
        // Go now. Be free little brother.
        return $user->id;
    }
    
    static function getUserId() {        
        return (isset($_SESSION[self::APISESSIONNAME])) ? $_SESSION[self::APISESSIONNAME] : '0';
    }
    
}