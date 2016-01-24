<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/user/user.data.php';
require_once dirname(__FILE__) . '/auth.data.php';

use \Respect\Validation\Validator as v;

class AuthController {
    
    static $maxattempts = 6;
    
    static $passwordRules = "Passwords must be at least 8 characters long, contain no whitespace, have at least one letter and one number and any of the following !@#$%^&*_+=-.";
    
    // Login Function
    
    static function login($app) {
        // If anone is logged in currently, log them out
        self::login_logoutCurrentAccount($app);
        
        // Validate input parameters
        if(!self::login_validateParams($app)) { return; }
        
        $user = self::login_validateFoundUser($app);
        if(!$user) { return; }
        
        $session = self::login_createSession();
        
        $hours = self::login_getSessionExpirationInHours($app);
        
        // Congrats - you're logged in!
        AuthData::insertAuthToken(array(
            ':user_id' => $user->id,
            ':identifier' => $session->identifier,
            ':token' => password_hash($session->token, PASSWORD_DEFAULT),
            ':expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
        ));
        
        $user->apiKey = $session->identifier;
        $user->apiToken = $session->token;
        
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
            return $app->render(401, array('msg' => 'Login failed. Check your parameters and try again.'));
        }
        return true;
    }
    
    private static function login_validateFoundUser($app) {
        $user = UserData::selectUserByEmail($app->request->post('email'));
        
        if(!$user) {
            // Validate existing user
            return $app->render(401, array('maxattempts' => self::$maxattempts, 'msg' => 'Login failed. A user with that email could not be found.'));
        } else if (!password_verify($app->request->post('password'), $user->password)) {
            // Validate Password
            return $app->render(401, array('maxattempts' => self::$maxattempts, 'msg' => 'Login failed. Username and password combination did not match.' ));
        }
        
        // Safty first
        unset($user->password);
        
        return $user;
    }
    
    private static function login_createSession() {
        return (object) array(
            'identifier' => hash('sha256', uniqid()),
            'token' => hash('sha256', uniqid())
        );
    }
    
    private static function login_getSessionExpirationInHours($app) {
        $remember = (v::key('remember', v::stringType())->validate($app->request->post())) ? 
                boolval($app->request->post('remember')) : false;
        
        /* TO DO Change this to use config var */
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
        if(!v::key('userKey', v::stringType())->validate($app->request->post()) || 
           !v::key('userToken', v::stringType())->validate($app->request->post())) {
            return false;
        }
        $user = UserData::selectUserByIdentifierToken($app->request->post('userKey'));
        if(!$user || !password_verify($app->request->post('userToken'), $user->apiToken)) {
            return false;
        }
        // Go now. Be free little brother.
        return $user->id;
    }
    
    static function isAuthenticated($app) {
        if(!v::key('key', v::stringType())->validate($app->request->post()) || 
           !v::key('token', v::stringType())->validate($app->request->post())) {
            return $app->render(400, array('msg' => 'Unauthenticated: Invalid request. Check your parameters and try again.'));
        }
        $user = UserData::selectUserByIdentifierToken($app->request->post('key'));
        
        if(!$user) {
            // Validate existing user
            return $app->render(401, array('msg' => 'Unauthenticated: No User'));
        } else if (!password_verify($app->request->post('token'), $user->apiToken)) {
            // Validate Password
            return $app->render(401, array('msg' => 'Unauthenticated: Invalid Cookie'));
        }
        
        // Go now. Be free little brother.
        return $app->render(200, array('user' => $user));
    }
    
    // Test Password Route
    
    static function testValidatePassword($app) {
        return $app->render(200, array( 
            'valid' => (self::validatePassword($app->request->post()))
        ));
    }
    
    static function validatePassword($post, $key = 'password') {
        return (v::key($key, v::stringType()->length(8,255)->noWhitespace()->alnum('!@#$%^&*_+=-')->regex('/^(?=.*[a-zA-Z])(?=.*[0-9])/'))->validate($post));
    }
        
    // Delete Expired Auth Tokens
    
    public static function deleteExpiredAuthTokens($app) {
        AuthData::deleteExpiredAuthTokens();
        return $app->render(200, array('msg' => "Deleted expired auth tokens." ));
    }
    
    
    
    
    /*
    
    
    private static $logger;
    private static $routeChangePassword;
    private static $routeConfirmEmail;
    
    static function hmmm() {
        parent::__construct();
        self::logger = new \API\Data\Logging();
        self::routeChangePassword = '#/change-password/';
        self::routeConfirmEmail = '#/confirm-email/';
        session_start();
    }
    

    private static function isTokenValid($token) {
        $found = AuthData::selectResetToken($token);
        if ($found && date('Y-m-d H:i:s', strtotime('NOW')) <= date('Y-m-d H:i:s', strtotime($found['expires']))) {
            return AuthData::selectUserById($found['analyst_id']);
        } else if ($found) {
            AuthData::deleteResetToken($found['id']);
        }
        return false;
    }
    
    private static function hashForgotLinkToken() {
        /* Note incase this is modified: 
         * The fact that this hash is exactly 32 characters long 
         * is validated in validateResetToken() below. 
        return md5(uniqid(rand(),1));
    }

    
    static function forgotPassword($app) {
        if(!v::email()->validate($app->request->post('email'))) {
            $app->render(400, self::setResponse->fail('Invalid email address.'));
            return;
        }
        
        $user = AuthData::selectUserByEmail($app->request->post('email'));
        
        if (!$user) {
            $app->render(400, self::setResponse->fail('A user for that email could not be found. Password reset instructions were not sent.'));
        }
        
        $timestamp = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $token = self::hashForgotLinkToken();

        if(!AuthData::updatePasswordResetToken($user['id'], $token, $timestamp)) {
            $app->render(500, self::setResponse->fail('Password reset failed. Please try again later.'));
        }
        
        $mail = new \API\Data\EmailManager();
        $route = self::routeChangePassword . urlencode($token);
        $sent = $mail->emailForgotPassword($user, $route, '6 hours');

        if ($sent) {
            $app->render(200, self::setResponse->success('Password reset instructions have been sent. Please check your email.'));
        } else {
            $app->render(500, self::setResponse->fail('Sending password reset email failed. Please try again later.'));
        }
    }

    static function validateResetToken($app) {
        $token = urldecode($app->request->post('token'));
        
        if(v::stringType()->length(32)->validate($token)) {
            $user = self::isTokenValid($token);
            $app->render(200, self::setResponse->success(array('user' => $user)));
        } else {
            $app->render(400, self::setResponse->fail('Token Invalid'));
        }        
    }

    static function changePassword($app) {
        $token = ($app->request->post('token')) ? urldecode($app->request->post('token')) : false;
        
        if(!self::validatePassword($app->request->post('password'))) {
            $app->render(400, self::setResponse->fail("Error saving password. Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number."));
                    return;
        } 
        
        if(!v::stringType()->length(32)->validate($token)) {
            $app->render(400, self::setResponse->fail('Invalid token. Request another forgot password link from the login page.'));
        }
        
        if(v::int()->length(1,11)->validate($app->request->post('id')) &&
           v::email()->validate($app->request->post('email'))) {
            
            $user = self::isTokenValid($token);
            $id = $app->request->post('id');
            
            if ($user && $user['id'] == $id && $user['email'] === $app->request->post('email')) {
                $saved = AuthData::updateUserPassword($id, $app->request->post('password'));
                
                if($saved) {
                    AuthData::deleteResetTokenForUser($id);
                    $app->render(200, self::setResponse->success('Your password has been updated. You may now login using your new credentials.'));
                    return;
                }
            }
        }
        
        $app->render(500, self::setResponse->fail('Error saving password. Please refresh the page and try again.'));
    }

    static function userChagePassword($app, $userId) {
        $newPassword = ($app->request->post('newPassword')) ? trim($app->request->post('newPassword')) : false;
        if(!self::validatePassword($newPassword)) {
            $app->render(400, self::setResponse->fail("Error saving password. Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number, and include only letters, numbers and "
                    . "the following special characters \'!@#$%^&*_+=-\'."));
            
            return;
        } 
        
        if(v::int()->length(1,11)->validate($userId) &&
           v::stringType()->notEmpty()->validate($app->request->post('password'))) {
            
            $user = AuthData::selectUserPasswordById($userId);

            if (!$user) {
                $app->render(400, self::setResponse->fail('Error saving password. Please check your parameters and try again.'));
                return;
            } else if (!password_verify($app->request->post('password'), $user['password'])) {
                $app->render(400, self::setResponse->fail('Incorrect current password. Please check your Caps Lock and try again.'));
                return;
            } else {
                $saved = AuthData::updateUserPassword($userId, $newPassword);
                if($saved) {
                    AuthData::deleteResetTokenForUser($userId);
                    $app->render(200, self::setResponse->success('Your password has been updated.'));
                    return;
                }
            }
        }

        $app->render(400, self::setResponse->fail('Error saving password. Please check your parameters and try again.'));
    }
    */
}
