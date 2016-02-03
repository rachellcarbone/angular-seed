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
    
    static function isAuthenticated($post) {
        if(!v::key('apiKey', v::stringType())->validate($post) || 
           !v::key('apiToken', v::stringType())->validate($post)) {
            return array('authenticated' => false, 'msg' => 'Unauthenticated: Invalid request. Check your parameters and try again.');
        }
        
        $user = UserData::selectUserByIdentifierToken($post['apiKey']);
        
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
    static function signup($post) {
        if(!v::key('email', v::email())->validate($post) || 
           !v::key('nameFirst', v::stringType())->validate($post) || 
           !v::key('nameLast', v::stringType())->validate($post) || 
           !v::key('password', v::stringType())->validate($post)) {
            // Validate input parameters
            return array('registered' => false, 'msg' => 'Signup failed. Check your parameters and try again.');
        }
        
        $existing = UserData::selectUserByEmail($post['email']);
        if($existing) { 
            return array('registered' => false, 'msg' => 'Signup failed. A user with that email already exists.');        
        }
        
        $validUser = array(
            ':email' => $post['email'],
            ':name_first' => $post['nameFirst'],
            ':name_last' => $post['nameLast'],
            ':password' => password_hash($post['password'], PASSWORD_DEFAULT)
        );
        $userId = UserData::insertUser($validUser);
        if($userId) {
            $user = UserData::selectUserById($userId);
            if(!$user) { 
                return array('registered' => false, 'msg' => 'Signup failed. Could not select user.');        
            }
            $user->apiKey = hash('sha512', uniqid());
            $user->apiToken = hash('sha512', uniqid());
            $hours = self::login_getSessionExpirationInHours($post);
        
            // Congrats - you're logged in!
            AuthData::insertAuthToken(array(
                ':user_id' => $user->id,
                ':identifier' => $user->apiKey,
                ':token' => password_hash($user->apiToken, PASSWORD_DEFAULT),
                ':expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
            ));

            // Save "Where did you hear about us" question
            InfoController::quietlySaveAdditional($post, $user->id);
            
            // Send the session life back (in hours) for the cookies
            return array('registered' => true, 'user' => $user, 'sessionLifeHours' => $hours);
        }
        return array('registered' => false, 'msg' => 'Signup failed. Could not save user.');
    }
    

    ///// 
    ///// Login
    ///// 
    
    static function login($post) {
        // If anone is logged in currently, log them out
        self::login_logoutCurrentAccount($post);
        
        // Validate input parameters
        if(!v::key('email', v::email())->validate($post) || 
           !v::key('password', v::stringType())->validate($post)) {
            return array('authenticated' => false, 'msg' => 'Login failed. Check your parameters and try again.');
        }
        
        $found = self::login_validateFoundUser($post);
        if(!$found['authenticated']) { 
            return $found;
        }
        $found['user']->apiKey = hash('sha512', uniqid());
        $found['user']->apiToken = hash('sha512', uniqid());
        
        $found['sessionLifeHours'] = self::login_getSessionExpirationInHours($post);
        
        // Congrats - you're logged in!
        AuthData::insertAuthToken(array(
            ':user_id' => $found['user']->id,
            ':identifier' => $found['user']->apiKey,
            ':token' => password_hash($found['user']->apiToken, PASSWORD_DEFAULT),
            ':expires' => date('Y-m-d H:i:s', time() + ($found['sessionLifeHours'] * 60 * 60))
        ));
        
        // Send the session life back (in hours) for the cookies
        return $found;
    }
    
    private static function login_validateFoundUser($post) {
        $user = UserData::selectUserByEmail($post['email']);
        
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
    
    static function logout($post) {
        return self::login_logoutCurrentAccount($post);
    }
    
    private static function login_logoutCurrentAccount($post) {
        if(v::key('logout', v::stringType())->validate($post)) {
            AuthData::deleteAuthToken(array(':identifier' => $post['logout']));
            return true;
        }
        return false;
    }
}
