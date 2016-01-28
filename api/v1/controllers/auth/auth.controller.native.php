<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/user/user.data.php';
require_once dirname(__FILE__) . '/auth.data.php';

use \Respect\Validation\Validator as v;

class AuthControllerNative {
    
    static $maxattempts = 6;
    static $passwordRules = "Passwords must be at least 8 characters long, contain no whitespace, have at least one letter and one number and any of the following !@#$%^&*_+=-.";
    
    // Signup Function
    static function signup($app) {
        if(!v::key('email', v::email())->validate($app->request->post()) || 
           !v::key('nameFirst', v::stringType())->validate($app->request->post()) || 
           !v::key('nameLast', v::stringType())->validate($app->request->post()) || 
           !v::key('password', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Signup failed. Check your parameters and try again.'));
        }
        
        $existing = UserData::selectUserByEmail($app->request->post('email'));
        if($existing) { 
            return $app->render(400, array('msg' => 'Signup failed. A user with that email already exists.'));        
        }
        
        $validUser = array(
            ':email' => $app->request->post('email'),
            ':name_first' => $app->request->post('nameFirst'),
            ':name_last' => $app->request->post('nameLast'),
            ':password' => password_hash($app->request->post('password'), PASSWORD_DEFAULT)
        );
        $userId = UserData::insertUser($validUser);
        if($userId) {
            $user = UserData::selectUserById($userId);
            if(!$user) { 
                return $app->render(400, array('msg' => 'Signup failed. Could not select user.'));        
            }
            $user->apiKey = hash('sha512', uniqid());
            $user->apiToken = hash('sha512', uniqid());
            $hours = self::login_getSessionExpirationInHours($app);
        
            // Congrats - you're logged in!
            AuthData::insertAuthToken(array(
                ':user_id' => $user->id,
                ':identifier' => $user->apiKey,
                ':token' => password_hash($user->apiToken, PASSWORD_DEFAULT),
                ':expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
            ));

            // Send the session life back (in hours) for the cookies
            return $app->render(200, array('user' => $user, 'sessionLifeHours' => $hours));
        }
        return $app->render(400, array('msg' => 'Signup failed. Could not save user.'));
    }
    
    static function facebookSignup($app) {
        if(!v::key('facebookId', v::stringType())->validate($app->request->post()) || 
           !v::key('nameFirst', v::stringType())->validate($app->request->post()) || 
           !v::key('nameLast', v::stringType())->validate($app->request->post()) || 
           !v::key('email', v::email())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Facebook signup failed. Check your parameters and try again.'));
        }
        
        $existing = UserData::selectUserByEmail($app->request->post('email'));
        if($existing) { 
            return $app->render(400, array('msg' => 'Facebook signup failed. A user with that email already exists.'));        
        }
        
        $validUser = array(
            ':email' => $app->request->post('email'),
            ':name_first' => $app->request->post('nameFirst'),
            ':name_last' => $app->request->post('nameLast'),
            ':facebook_id' => $app->request->post('facebookId')
        );
        $userId = UserData::insertFacebookUser($validUser);
        if($userId) {
            $user = UserData::selectUserById($userId);
            if(!$user) { 
                return $app->render(400, array('msg' => 'Facebook signup failed. Could not select user.'));        
            }
            $user->apiKey = hash('sha512', uniqid());
            $user->apiToken = hash('sha512', uniqid());
            $hours = self::login_getSessionExpirationInHours($app);
        
            // Congrats - you're logged in!
            AuthData::insertAuthToken(array(
                ':user_id' => $user->id,
                ':identifier' => $user->apiKey,
                ':token' => password_hash($user->apiToken, PASSWORD_DEFAULT),
                ':expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
            ));

            // Send the session life back (in hours) for the cookies
            return $app->render(200, array('user' => $user, 'sessionLifeHours' => $hours));
        }
        return $app->render(400, array('msg' => 'Facebook signup failed. Could not save user.'));
        
    }
    
    // Login Function
    
    static function facebookLogin($app) {
        
    }
    
    static function login($app) {
        // If anone is logged in currently, log them out
        self::login_logoutCurrentAccount($app);
        
        // Validate input parameters
        if(!self::login_validateParams($app)) { return; }
        
        $user = self::login_validateFoundUser($app);
        if(!$user) { return; }
        $user->apiKey = hash('sha512', uniqid());
        $user->apiToken = hash('sha512', uniqid());
        
        $hours = self::login_getSessionExpirationInHours($app);
        
        // Congrats - you're logged in!
        AuthData::insertAuthToken(array(
            ':user_id' => $user->id,
            ':identifier' => $user->apiKey,
            ':token' => password_hash($user->apiToken, PASSWORD_DEFAULT),
            ':expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
        ));
        
        // Send the session life back (in hours) for the cookies
        return $app->render(200, array('user' => $user, 'sessionLifeHours' => $hours));
    }
    
    private static function login_logoutCurrentAccount($app) {
        if(v::key('logout', v::stringType())->validate($app->request->post())) {
            AuthData::deleteAuthToken(array(':identifier' => $app->request->post('logout')));
            return true;
        }
        return false;
    }
    
    private static function login_validateParams($app) {
        if(!v::key('email', v::email())->validate($app->request->post()) || 
           !v::key('password', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Login failed. Check your parameters and try again.'));
        }
        return true;
    }
    
    private static function login_validateFoundUser($app) {
        $user = UserData::selectUserByEmail($app->request->post('email'));
        
        if(!$user) {
            // Validate existing user
            return $app->render(400, array('maxattempts' => self::$maxattempts, 'msg' => 'Login failed. A user with that email could not be found.'));
        } else if (!password_verify($app->request->post('password'), $user->password)) {
            // Validate Password
            return $app->render(400, array('maxattempts' => self::$maxattempts, 'msg' => 'Login failed. Username and password combination did not match.' ));
        }
        
        // Safty first
        unset($user->password);
        
        return $user;
    }
    
    private static function login_getSessionExpirationInHours($app) {
        $remember = (v::key('remember', v::stringType())->validate($app->request->post())) ? 
                boolval($app->request->post('remember')) : false;
        
        // TODO: Change this to use config var
        return (!$remember) ? 1 : 3 * 24; // 1 Hours or 3 days if remember was checked
    }
    
    // Logout Function
    
    static function logout($app) {
        if(self::login_logoutCurrentAccount($app)) { 
            return $app->render(200, array('msg' => 'User successfully logged out.'));
        }
        return $app->render(400, array('msg' => 'Could not log out user.'));
    }
    
    static function authorizeApiToken($app) {
        if(!v::key('apiKey', v::stringType())->validate($app->request->post()) || 
           !v::key('apiToken', v::stringType())->validate($app->request->post())) {
            return false;
        }
        $user = UserData::selectUserByIdentifierToken($app->request->post('apiKey'));
        if(!$user) {
            return "user";
        }
        if(!password_verify($app->request->post('apiToken'), $user->apiToken)) {
            return "password";
        }
        // Go now. Be free little brother.
        return $user->id;
    }
    
    static function isAuthenticated($app) {
        if(!v::key('apiKey', v::stringType())->validate($app->request->post()) || 
           !v::key('apiToken', v::stringType())->validate($app->request->post())) {
            return $app->render(400, array('msg' => 'Unauthenticated: Invalid request. Check your parameters and try again.'));
        }
        $user = UserData::selectUserByIdentifierToken($app->request->post('apiKey'));
        
        if(!$user) {
            // Validate existing user
            return $app->render(400, array('msg' => 'Unauthenticated: No User'));
        } else if (!password_verify($app->request->post('apiToken'), $user->apiToken)) {
            // Validate Password
            return $app->render(400, array('msg' => 'Unauthenticated: Invalid Cookie'));
        }
        
        // Go now. Be free little brother.
        unset($user->apiKey);
        unset($user->apiToken);
        return $app->render(200, array('user' => $user));
    }
    
    // Delete Expired Auth Tokens
    
    static function deleteExpiredAuthTokens($app) {
        AuthData::deleteExpiredAuthTokens();
        return $app->render(200, array('msg' => "Deleted expired auth tokens." ));
    }
    
    static function isFacebookAuthenticated($param) {
        $user = FacebookAuthController::checkLoginStatus();
        if($user) {
            
        }
    }
    
}
