<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/user/user.data.php';
require_once dirname(__FILE__) . '/auth.session.php';
require_once dirname(__FILE__) . '/auth.data.php';

use \Respect\Validation\Validator as v;

class AuthController {
    public static $passwordRules = "Passwords must be at least 8 characters long, contain no whitespace, have at least one letter and one number and any of the following !@#$%^&*_+=-.";
    
    public static function testValidatePassword($app) {
        return $app->render(200, array( 
            'password' => $app->request->post('password'),
            'valid' => (self::validatePassword($app->request->post()))
        ));
    }
    
    public static function validatePassword($post, $key = 'password') {
        return (v::key($key, 
                v::string()->length(8,255)->noWhitespace()->alnum('!@#$%^&*_+=-')->regex('/^(?=.*[a-zA-Z])(?=.*[0-9])/')
                )->validate($post));
    }
    
    public static function login($app) {
        $session = new AuthSession();
        $session->clearLoginAttempts();
        $attempts = $session->getFailedLoginAttempts($app->request->post('email'));
        
        if(!v::key('email', v::email())->validate($app->request->post()) || !self::validatePassword($app->request->post())) {
            return $app->render(401, array( 'msg' => 'Login failed. Check your parameters and try again.' ));
        } else if($attempts >= 3) {
            $attempts = $session->loginAttemptFailed($app->request->post('email'));
            return $app->render(401, array('attempts' => $attempts, 'maxattempts' => 3, 'msg' => 'You have attempted to login too many times with that email in a short period of time. Please try again later.' ));
        }
        
        $user = UserData::selectUserByEmail($app->request->post('email'));
        
        if(!$user) {
            return $app->render(401, array( 'msg' => 'Login failed. A user with that email could not be found.' ));
        } else if (!password_verify($app->request->post('password'), $user->password)) {
            $attempts = $session->loginAttemptFailed($app->request->post('email'));
            return $app->render(401, array('attempts' => $attempts, 'maxattempts' => 3, 'msg' => 'Login failed. Username and password combination did not match.' ));
        }
        
        unset($user->password);
        
        return $user;
    }
    
    
    
    
    
    
    /*
    
    
    private static $logger;
    private static $routeChangePassword;
    private static $routeConfirmEmail;
    
    public static function hmmm() {
        parent::__construct();
        $this->logger = new \API\Data\Logging();
        $this->routeChangePassword = '#/change-password/';
        $this->routeConfirmEmail = '#/confirm-email/';
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

    
    public static function forgotPassword($app) {
        if(!v::email()->validate($app->request->post('email'))) {
            $app->render(400, $this->setResponse->fail('Invalid email address.'));
            return;
        }
        
        $user = AuthData::selectUserByEmail($app->request->post('email'));
        
        if (!$user) {
            $app->render(400, $this->setResponse->fail('A user for that email could not be found. Password reset instructions were not sent.'));
        }
        
        $timestamp = date('Y-m-d H:i:s', strtotime('+6 hours'));
        $token = $this->hashForgotLinkToken();

        if(!AuthData::updatePasswordResetToken($user['id'], $token, $timestamp)) {
            $app->render(500, $this->setResponse->fail('Password reset failed. Please try again later.'));
        }
        
        $mail = new \API\Data\EmailManager();
        $route = $this->routeChangePassword . urlencode($token);
        $sent = $mail->emailForgotPassword($user, $route, '6 hours');

        if ($sent) {
            $app->render(200, $this->setResponse->success('Password reset instructions have been sent. Please check your email.'));
        } else {
            $app->render(500, $this->setResponse->fail('Sending password reset email failed. Please try again later.'));
        }
    }

    public static function validateResetToken($app) {
        $token = urldecode($app->request->post('token'));
        
        if(v::string()->length(32)->validate($token)) {
            $user = $this->isTokenValid($token);
            $app->render(200, $this->setResponse->success(array('user' => $user)));
        } else {
            $app->render(400, $this->setResponse->fail('Token Invalid'));
        }        
    }

    public static function changePassword($app) {
        $token = ($app->request->post('token')) ? urldecode($app->request->post('token')) : false;
        
        if(!$this->validatePassword($app->request->post('password'))) {
            $app->render(400, $this->setResponse->fail("Error saving password. Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number."));
                    return;
        } 
        
        if(!v::string()->length(32)->validate($token)) {
            $app->render(400, $this->setResponse->fail('Invalid token. Request another forgot password link from the login page.'));
        }
        
        if(v::int()->length(1,11)->validate($app->request->post('id')) &&
           v::email()->validate($app->request->post('email'))) {
            
            $user = $this->isTokenValid($token);
            $id = $app->request->post('id');
            
            if ($user && $user['id'] == $id && $user['email'] === $app->request->post('email')) {
                $saved = AuthData::updateUserPassword($id, $app->request->post('password'));
                
                if($saved) {
                    AuthData::deleteResetTokenForUser($id);
                    $app->render(200, $this->setResponse->success('Your password has been updated. You may now login using your new credentials.'));
                    return;
                }
            }
        }
        
        $app->render(500, $this->setResponse->fail('Error saving password. Please refresh the page and try again.'));
    }

    public static function userChagePassword($app, $userId) {
        $newPassword = ($app->request->post('newPassword')) ? trim($app->request->post('newPassword')) : false;
        if(!$this->validatePassword($newPassword)) {
            $app->render(400, $this->setResponse->fail("Error saving password. Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number, and include only letters, numbers and "
                    . "the following special characters \'!@#$%^&*_+=-\'."));
            
            return;
        } 
        
        if(v::int()->length(1,11)->validate($userId) &&
           v::string()->notEmpty()->validate($app->request->post('password'))) {
            
            $user = AuthData::selectUserPasswordById($userId);

            if (!$user) {
                $app->render(400, $this->setResponse->fail('Error saving password. Please check your parameters and try again.'));
                return;
            } else if (!password_verify($app->request->post('password'), $user['password'])) {
                $app->render(400, $this->setResponse->fail('Incorrect current password. Please check your Caps Lock and try again.'));
                return;
            } else {
                $saved = AuthData::updateUserPassword($userId, $newPassword);
                if($saved) {
                    AuthData::deleteResetTokenForUser($userId);
                    $app->render(200, $this->setResponse->success('Your password has been updated.'));
                    return;
                }
            }
        }

        $app->render(400, $this->setResponse->fail('Error saving password. Please check your parameters and try again.'));
    }
    */
}
