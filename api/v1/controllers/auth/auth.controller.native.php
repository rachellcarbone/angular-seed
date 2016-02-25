<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';
require_once dirname(__FILE__) . '/auth.additionalInfo.data.php';

use \Respect\Validation\Validator as v;

class AuthControllerNative {
    
    static $maxattempts = 6;
    static $passwordRules = "Passwords must be at least 8 characters long, contain no whitespace, have at least one letter and one number and any of the following !@#$%^&*_+=-.";
    
    ///// 
    ///// Authentication
    ///// 
    
    static function isAuthenticated($app) {
        $post = $app->request->post();
        
        if(!v::key('apiKey', v::stringType())->validate($post) || 
           !v::key('apiToken', v::stringType())->validate($post)) {
            return array('authenticated' => false, 'msg' => 'Unauthenticated: Invalid request. Check your parameters and try again.');
        }
        
        $user = AuthData::selectUserByIdentifierToken($post['apiKey']);
        
        if(!$user) {
            // Validate existing user
            return array('authenticated' => false, 'msg' => 'Unauthenticated: No User');
        } else if (!password_verify($post['apiToken'], $user->apiToken)) {
            // Validate Password
            return array('authenticated' => false, 'msg' => 'Unauthenticated: Invalid Cookie');
        }
        
        // Go now. Be free little brother.
        if(isset($user->apiKey)){ 
            unset($user->apiKey);
        }
        if(isset($user->apiToken)){ 
            unset($user->apiToken);
        }
        return array('authenticated' => true, 'user' => $user);
    }
    
    // Signup Function
    static function signup($app) {
        // Get Post Data
        $post = $app->request->post();
        
        // Validate Sent Input
        $valid = self::signup_validateSentParameters($post);
        if($valid !== true) {
            return array('registered' => false, 'msg' => $valid);
        }
        
        // Look for user with that email
        $existing = AuthData::selectUserByEmail($post['email']);
        if($existing) { 
            /// FAIL - If a user with that email already exists
            return array('registered' => false, 'msg' => 'Signup failed. A user with that email already exists.');        
        }
        
        // Create and insert a new user
        $validUser = array(
            ':email' => $post['email'],
            ':name_first' => $post['nameFirst'],
            ':name_last' => $post['nameLast'],
            ':password' => password_hash($post['password'], PASSWORD_DEFAULT)
        );
        $userId = AuthData::insertUser($validUser);
        if(!$userId) {
            /// FAIL - If Inserting the user failed
            return array('registered' => false, 'msg' => 'Signup failed. Could not save user.');
        }
        
        // Select our new user
        $user = AuthData::selectUserById($userId);
        if(!$user) { 
            /// FAIL - If Inserting the user failed (hopefully this is redundant)
            return array('registered' => false, 'msg' => 'Signup failed. Could not select user.');    
        }

        // Save "Where did you hear about us" and any other additional questions
        // This is "quiet" in that it may not execute if no paramters match
        // And it doesnt set the response for the api call
        InfoController::quietlySaveAdditional($post, $user->id);

        // Create an authorization
        $token = self::createAuthToken($app, $user->id);
        if($token) {
            // Create the return object
            $found = array('user' => $user);
            $found['user']->apiKey = $token['apiKey'];
            $found['user']->apiToken = $token['apiToken'];
            $found['sessionLifeHours'] = $token['sessionLifeHours'];
            $found['registered'] = true;

            return $found;
        } else {
            /// FAIL - If the auth token couldnt be created and saved
            return array('registered' => false, 'msg' => 'Signup failed to creat auth token.');    
        }
    }
    
    static function validatePasswordRequirements($post, $key = 'password') {
        return (v::key($key, v::stringType()->length(8,255)->noWhitespace()->alnum('!@#$%^&*_+=-')->regex('/^(?=.*[a-zA-Z])(?=.*[0-9])/'))->validate($post));
    }
    
    /*
     * return String|bool Failed message or true 
     */
    private function signup_validateSentParameters($post) {
        if(!v::key('email', v::email())->validate($post) || 
           !v::key('nameFirst', v::stringType())->validate($post) || 
           !v::key('nameLast', v::stringType())->validate($post) || 
           !v::key('password', v::stringType())->validate($post)) {
            return 'Signup failed. Check your parameters and try again.';
        } else if(!self::validatePasswordRequirements($post, 'password')) {
            // Validate that the password is valid
            return 'Signup failed. ' . self::$passwordRules;
        } else {
            return true;
        }
    }

    ///// 
    ///// Login
    ///// 
    
    static function login($app) {
        $post = $app->request->post();
        
        // If anone is logged in currently, log them out
        self::login_logoutCurrentAccount($post);
        
        // Validate input parameters
        if(!v::key('email', v::email())->validate($post) || 
           !v::key('password', v::stringType())->validate($post)) {
            return array('authenticated' => false, 'msg' => 'Login failed. Check your parameters and try again.');
        }
        
        // Validate the user email and password
        $found = self::login_validateFoundUser($post);
        if(!$found['authenticated']) { 
            return $found;
        }
        
        // Create logged in token
        $token = self::createAuthToken($app, $found['user']->id);
        if($token) {
            $found['user']->apiKey = $token['apiKey'];
            $found['user']->apiToken = $token['apiToken'];
            $found['sessionLifeHours'] = $token['sessionLifeHours'];

            // Send the session life back (in hours) for the cookies
            return $found;
        } else {
            return array('authenticated' => false, 'msg' => 'Login failed to create token.');   
        }
    }
    
    static function createAuthToken($app, $userId) {
        $token = array();
        $token['apiKey'] = hash('sha512', uniqid());
        $token['apiToken'] = hash('sha512', uniqid());
        $token['sessionLifeHours'] = self::login_getSessionExpirationInHours($app->request->post());
        
        // Congrats - you're logged in!
        $saved = AuthData::insertAuthToken(array(
            ':user_id' => $userId,
            ':identifier' => $token['apiKey'],
            ':token' => password_hash($token['apiToken'], PASSWORD_DEFAULT),
            ':ip_address' => $app->request->getIp(),
            ':user_agent' => $app->request->getUserAgent(),
            ':expires' => date('Y-m-d H:i:s', time() + ($token['sessionLifeHours'] * 60 * 60))
        ));
        
        AuthData::insertLoginLocation(array(
            ':user_id' => $userId,
            ':ip_address' => $app->request->getIp(),
            ':user_agent' => $app->request->getUserAgent()
        ));
        
        return ($saved) ? $token : false;
    }
    
    private static function login_validateFoundUser($post) {
        $user = AuthData::selectUserByEmail($post['email']);
        
        if(!$user) {
            // Validate existing user
            // TODO: Maxe max login a config variable
            return array('authenticated' => false, 'maxattempts' => self::$maxattempts, 'msg' => 'Login failed. A user with that email could not be found.');
        } else if (!password_verify($post['password'], $user->password)) {
            // Validate Password
            return array('authenticated' => false, 'maxattempts' => self::$maxattempts, 'msg' => 'Login failed. Username and password combination did not match.' );
        }
        
        // Safty first
        unset($user->password);
        
        return array('authenticated' => true, 'user' => $user);
    }
    
    private static function login_getSessionExpirationInHours($post) {
        $remember = (v::key('remember', v::stringType())->validate($post)) ? 
                boolval($post['remember']) : false;
        
        // TODO: Change this to use config var
        return (!$remember) ? 1 : 3 * 24; // 1 Hours or 3 days if remember was checked
    }
    
    
            
    ///// 
    ///// Logout
    ///// 
    
    static function logout($app) {
        return self::login_logoutCurrentAccount($app->request->post());
    }
    
    private static function login_logoutCurrentAccount($post) {
        if(v::key('logout', v::stringType())->validate($post)) {
            AuthData::deleteAuthToken(array(':identifier' => $post['logout']));
            return true;
        }
        return false;
    }
       
    ///// 
    ///// Password Managment
    ///// 
    
    static function updateUserPassword($app) {
        $post = $app->request->post();
        if(!v::key('userId', v::stringType())->validate($post) || 
            !v::key('current', v::stringType())->validate($post) || 
            !v::key('new', v::stringType())->validate($post)) {
            return false;
        }
        
        return self::login_logoutCurrentAccount($app->request->post());
    }
    
    
}
