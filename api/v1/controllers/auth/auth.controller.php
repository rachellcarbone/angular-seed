<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/user/user.data.php';
require_once dirname(__FILE__) . '/auth.session.php';
require_once dirname(__FILE__) . '/auth.data.php';

use \Respect\Validation\Validator as v;

class AuthController {
    
    static $maxattempts = 6;
    
    static $passwordRules = "Passwords must be at least 8 characters long, contain no whitespace, have at least one letter and one number and any of the following !@#$%^&*_+=-.";
    
    // Login Function
    
    static function login($app) {
        $AuthSession = new AuthSession();
        
        // If anone is logged in currently, log them out
        $session = self::login_logoutExistingSession($AuthSession);
        
        // Validate input parameters
        if(!self::login_validateParams($app)) { return; }
        
        // Validate number of attempts for this email
        if(!self::login_validateLoginAttempts($app, $AuthSession)) { return; }
        
        $user = self::login_validateFoundUser($app, $AuthSession);
        
        if(!$user) { return; }
        
        // Remember me
        $remember = (v::key('remember', v::boolType())->validate($app->request->post())) ? boolval($app->request->post('remember')) : false;
        
        // Congrats - you're logged in!
        $newSession = $AuthSession->createLoggedInSession($user, $remember);
        AuthData::insertAuthToken(array(
            ':user_id' => $user->id,
            ':identifier' => $newSession->identifier,
            ':token' => $newSession->token,
            ':expires' => $newSession->expires
        ));
        
        // Go now. Be free little brother.
        return $app->render(200, array('user' => $user, 'session' => $session));
    }
    
    private static function login_logoutExistingSession($AuthSession) {
        // Is user already logged in?
        $session = $AuthSession->getLoggedInSession();
        $result = false;
        if($session && property_exists($session, 'identifier')) {
            // Log them out when someone else attempts to log in
            $AuthSession->clearLoggedInSession();
            $result = AuthData::deleteAuthToken(array(':identifier' => $session->identifier));
        }
        return array('existing' => $session, 'deleted' => $result);
    }
    
    private static function login_validateParams($app) {
        if(!v::key('email', v::email())->validate($app->request->post()) || 
           !v::key('password', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Login failed. Check your parameters and try again.'));
        } 
        return true;
    }
    
    private static function login_validateLoginAttempts($app, $AuthSession) {
        $attempts = $AuthSession->getFailedLoginAttempts($app->request->post('email'));
        if(false && $attempts >= self::$maxattempts) {
            // Validate number of attempts for this email
            $attempts = $AuthSession->loginAttemptFailed($app->request->post('email'));
            return $app->render(401, array('attempts' => $attempts, 'maxattempts' => self::$maxattempts, 'msg' => 'You have attempted to login too many times with that email in a short period of time. Please try again later.' ));
        }
        return true;
    }
    
    private static function login_validateFoundUser($app, $AuthSession) {
        $user = UserData::selectUserByEmail($app->request->post('email'));
        
        if(!$user) {
            // Validate existing user
            return $app->render(401, array('msg' => 'Login failed. A user with that email could not be found.'));
        } else if (!password_verify($app->request->post('password'), $user->password)) {
            // Validate Password
            $attempts = $AuthSession->loginAttemptFailed($app->request->post('email'));
            return $app->render(401, array('attempts' => $attempts, 'maxattempts' => self::$maxattempts, 'msg' => 'Login failed. Username and password combination did not match.' ));
        }
        
        // Safty first
        unset($user->password);
        
        return $user;
    }
    
    // Logout Function
    
    static function logout($app) {
        $AuthSession = new AuthSession();
        
        $session = $AuthSession->getLoggedInSession();
        
        if(!$session) { 
            return $app->render(200, array('msg' => 'Log out successful.'));
        } else if (property_exists($session, 'identifier')) {
            AuthData::deleteAuthToken(array( ':identifier' => $session->identifier ));
        }
        
        $AuthSession->clearLoggedInSession();
        return $app->render(200, array('msg' => 'User successfully logged out.'));
    }
    
    static function getLoggedInUser($app) {
        $AuthSession = new AuthSession();
        $session = $AuthSession->getLoggedInSession();
        
        if($session && property_exists($session, 'user')) {
            return $app->render(200, array('user' => $session->user, '$session' => $session));
        } else if($session && property_exists($session, 'identifier')) {
            $loggedInUser = self::auth_validateFoundUser($app, $AuthSession, $session);
            if($loggedInUser) {
                $AuthSession->server_saveUserSession($loggedInUser);
                return $app->render(200, array('user' => $loggedInUser));
            }
        }
        return $app->render(401, array('msg' => 'Unauthenticated: Nope', '$session' => $session));
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
    
    
    private static function auth_validateFoundUser($app, $AuthSession, $session) {
        $user = UserData::selectUserByIdentifierToken($session->identifier);
        
        if(!$user) {
            // Validate existing user
            return $app->render(401, array('msg' => 'Unauthenticated: No User'));
        } else if (!password_verify($session->token, $user->token)) {
            // Validate Password
            $AuthSession->clearLoggedInSession();
            return $app->render(401, array('msg' => 'Unauthenticated: Invalid Cookie'));
        }
        
        // Safty first
        unset($user->identifier);
        
        return $user;
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
