<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';
require_once dirname(__FILE__) . '/auth.additionalInfo.data.php';
require_once dirname(dirname(__FILE__)) . '/system-variables/config.data.php';
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
           !v::key('apiToken', v::stringType())->validate($post)) 
        {
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
            return array('registered' => false, 'msg' => $valid, 'post' => $post);
        }
        // Look for user with that email
        $existing = AuthData::selectUserAndPasswordByEmail($post['email']);
        if($existing) { 
            /// FAIL - If a user with that email already exists
            return array('registered' => false, 'msg' => 'Signup failed. A user with that email already exists.');        
        }
        // Create and insert a new user
        $validUser = array(
            ':email' => $post['email'],
            ':name_first' => (v::key('nameFirst', v::stringType())->validate($post)) ? $post['nameFirst'] : '',
            ':name_last' => (v::key('nameLast', v::stringType())->validate($post)) ? $post['nameLast'] : '',
            ':phone' => (v::key('phone', v::stringType())->validate($post)) ? $post['phone'] : NULL,
            ':password' => password_hash($post['password'], PASSWORD_DEFAULT)
            );
        $userId = AuthData::insertUser($validUser);
        if(!$userId) {
            /// FAIL - If Inserting the user failed
            return array('registered' => false, 'msg' => 'Signup failed. Could not save user.');
        }
        
        // If a team ID was sent, add them to the team
        if(v::key('teamId', v::intVal())->validate($post)) {
            $addTeam = TeamController::addPlayerById($post['teamId'], "Team ID #{$post['teamId']}", $userId);
            if(!isset($addTeam['error']) || $addTeam['error'] === true) {
                /// FAIL - If Inserting the user failed (hopefully this is redundant)
                return array('registered' => false, 'msg' => 'Could not add user to team.');  
            }
        }
        
        // Select our new user
        $user = AuthData::selectUserById($userId);
        if(!$user) { 
            /// FAIL - If Inserting the user failed (hopefully this is redundant)
            return array('registered' => false, 'msg' => 'Signup failed. Could not select user.');    
        }
        
        // If a token was sent, update token status
        if(v::key('token', v::stringType())->validate($post)) {
            $inviteTeamId = AuthData::selectSignupInvite($post['token']);
            if($inviteTeamId) {
                AuthData::updateAcceptSignupTeamInvite(array(':user_id' => $userId, ':token' => $post['token'], ':team_id' => $inviteTeamId));
            } else {
                AuthData::updateAcceptSignupPlayerInvite(array(':user_id' => $userId, ':token' => $post['token']));
            }
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
            return array('registered' => false, 'msg' => 'Signup failed to create auth token.');    
        }
    }
    
    static function validatePasswordRequirements($post, $key = 'password') {
        return (v::key($key, v::stringType()->length(8,255)->noWhitespace()->alnum('!@#$%^&*_+=-')->regex('/^(?=.*[a-zA-Z])(?=.*[0-9])/'))->validate($post));
    }
    
    /*
     * return String|bool Failed message or true 
     */
    private static function signup_validateSentParameters($post) {
        if (!v::key('email', v::email())->validate($post) ||
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
    static function forgotpassword($app){
        $post = $app->request->post();
        // Validate input parameters
        if(!v::key('email', v::email())->validate($post)) {
            return array('frgtauthenticated' => false, 'msg' => 'Forgot password failed. Check your parameters and try again.');
        }
        // Validate the user email and password
        $found = self::forgotpassword_validateFoundUser($post,$app);
        return $found;
    }
    
    static function resetpassword($app){
        $post = $app->request->post();
        $found = self::getresetpassword_validateFoundUser($post);
        return $found;
    }
    
    private static function getresetpassword_validateFoundUser($post) {
        $result_array=array();
        $user = AuthData::selectUsertokenExpiry($post['email']);
        if(!$user) {
            // Validate existing user
            return array('resetpasswordauthenticated' => false, 'msg' => 'User failed. A user with that email id could not be found.');
        } else{
            $userDetail = AuthData::selectUserAndPasswordByEmail($post['email']);
            $strtotime1 =  strtotime($user->fortgotpassword_duration);
            $date = date("d M Y H:i:s");
            $strtotime =  strtotime($date);
            $diff = $strtotime - $strtotime1;
            $diff_in_hrs = round($diff/3600);
            if($diff_in_hrs > 1){
                $result_array=array('resetpasswordauthenticated' => false, 'msg' => 'Your reset password token is expired. 
                  Please try again to request forgot password.');
            }else{
                $userUpdate = AuthData::resetUserPassword(array(':email' => $post['email'], ':password' => password_hash($post['password'], PASSWORD_DEFAULT),':usertoken' => '', ':fortgotpassword_duration' => ''));
                if(!$userUpdate) {
                    $result_array=array('resetpasswordauthenticated' => false, 'msg' => 'Some error occured while updating password. 
                       please try again.');
                }
                else{
                    $result_array=array('resetpasswordauthenticated' => true,  'msg' => 'Your password has been reset successfully. Please login');
                }
            }
            if($result_array["resetpasswordauthenticated"]==false){
                $mail_variables=array(
                    "SMTP_SERVER_HOST"=>"Host",
                    "SMTP_SERVER_PORT"=>"Port",
                    "SMTP_SERVER_USERNAME"=>"Username",
                    "SMTP_SERVER_PASSWORD"=>"Password",
                    "SMTP_SMTP_DEBUG"=>'SMTPDebug',
                    "SMTP_DEBUGOUTPUT" => 'Debugoutput',
                    "SMTP_SECURE"=>"SMTPSecure",
                    "SMTP_AUTH"=>"SMTPAuth",
                    "PASSWORD_RESET_EMAIL_FROM"=>"From",
                    "PASSWORD_RESET_FAILED_EMAIL_SUBJECT"=>"Subject",
                    "PASSWORD_RESET_FAILED_EMAIL_BODY"=>"Body"
                    );
            }
            else{
                $mail_variables=array(
                    "SMTP_SERVER_HOST"=>"Host",
                    "SMTP_SERVER_PORT"=>"Port",
                    "SMTP_SERVER_USERNAME"=>"Username",
                    "SMTP_SERVER_PASSWORD"=>"Password",
                    "SMTP_SMTP_DEBUG"=>'SMTPDebug',
                    "SMTP_DEBUGOUTPUT" => 'Debugoutput',
                    "SMTP_SECURE"=>"SMTPSecure",
                    "SMTP_AUTH"=>"SMTPAuth",
                    "PASSWORD_RESET_EMAIL_FROM"=>"From",
                    "PASSWORD_RESET_SUCCESS_EMAIL_SUBJECT"=>"Subject",
                    "PASSWORD_RESET_SUCCESS_EMAIL_BODY"=>"Body"
                    );
            }
            $mail = new \PHPMailer;
            $mail->IsSMTP(); 
            foreach($mail_variables as $name=>$value){
                $config_data = ConfigData::getVariableByName($name);
                if($config_data && $config_data->disabled!=1){
                    $mail->{$value}=$config_data->value;
                }
            }
            $config_data = ConfigData::getVariableByName("PASSWORD_RESET_ROOT_URL");
            $root_url =($config_data->value=='')?APIConfig::get('WEBSITE_URL'):$config_data->value;
            $mail->setFrom($mail->From, 'Triviajoint');
            $mail->addAddress($userDetail->email, $userDetail->nameFirst." ".$userDetail->nameLast);
            $mail->isHTML(true);
            $fields=array("!@FIRSTNAME@!","!@LASTNAME@!",'!@WEBSITEURL@!');
            $values=array($userDetail->nameFirst,$userDetail->nameLast,$root_url);
            $mail->Body=str_replace($fields, $values, $mail->Body);
            $mail->send();
            return $result_array; 
        }
    }
    
    static function getforgotpasswordemail($app){
        $post = $app->request->post();
        $found = self::getforgotpasswordemail_validateFoundUser($post);
        return $found;
    }
    
    private static function getforgotpasswordemail_validateFoundUser($post) {
        $user = AuthData::selectUserByUsertoken($post['usertoken']);
        if(!$user) {
            // Validate existing user
            return array('frgtauthenticatedemail' => false, 'msg' => 'Usertoken failed. A user with that usertoken could not be found.');
        } 
        return array('frgtauthenticatedemail' => true,  'user' => $user);
    }
    
    private static function forgotpassword_validateFoundUser($post,$app) {
        $user = AuthData::selectUserAndPasswordByEmail($post['email']);
        if(!$user) {
            // Validate existing user
            return array('frgtauthenticated' => false, 'msg' => 'Forgotpassword failed. A user with that email could not be found.');
        } 
        $usertoken = md5(date('Y-m-d H:i:s')*rand(9,99999));
        $fortgotpassword_duration = date('Y-m-d H:i:s');
        $userforgotupdate = AuthData::updateforgotpassworddata(array(':email' => $post['email'], ':usertoken' => $usertoken, ':fortgotpassword_duration' => $fortgotpassword_duration));
        $mail = new \PHPMailer;
        $mail->IsSMTP(); 
        $mail_variables=array(
            "SMTP_SERVER_HOST"=>"Host",
            "SMTP_SERVER_PORT"=>"Port",
            "SMTP_SERVER_USERNAME"=>"Username",
            "SMTP_SERVER_PASSWORD"=>"Password",
            "SMTP_SMTP_DEBUG"=>'SMTPDebug',
            "SMTP_DEBUGOUTPUT" => 'Debugoutput',
            "SMTP_SECURE"=>"SMTPSecure",
            "SMTP_AUTH"=>"SMTPAuth",
            "PASSWORD_RESET_EMAIL_FROM"=>"From",
            "PASSWORD_RESET_EMAIL_SUBJECT"=>"Subject",
            "PASSWORD_RESET_EMAIL_BODY"=>"Body"
            );
        foreach($mail_variables as $name=>$value){
            $config_data = ConfigData::getVariableByName($name);
            if($config_data && $config_data->disabled!=1){
                $mail->{$value}=$config_data->value;
            }
        }
        $config_data = ConfigData::getVariableByName("PASSWORD_RESET_ROOT_URL");
        $root_url =($config_data->value=='')?APIConfig::get('WEBSITE_URL'):$config_data->value;
        $mail->setFrom($mail->From, 'Triviajoint');
        $mail->addAddress($user->email, $user->nameFirst." ".$user->nameLast);
        $mail->isHTML(true);
        $fields=array("!@FIRSTNAME@!","!@LASTNAME@!",'!@WEBSITEURL@!','!@LINKID@!');
        $values=array($user->nameFirst,$user->nameLast,$root_url,$usertoken);
        $mail->Body=str_replace($fields, $values, $mail->Body);
        if(!$mail->send()){
            return array('frgtauthenticated' => false,  'msg' => 'Email could not be sent for reset password. Please try again later.');
        } else {
            return array('frgtauthenticated' => true,  'msg' => 'Email has been sent to your email address for reset password.');
        }
    }
    
    static function login($app) {
        $post = $app->request->post();
        // If anone is logged in currently, log them out
        self::login_logoutCurrentAccount($post);
        // Validate input parameters
        if(!v::key('email', v::email())->validate($post) || 
           !v::key('password', v::stringType())->validate($post)) 
        {
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
        $user = AuthData::selectUserAndPasswordByEmail($post['email']);
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
        $remember = false;
        if (v::key('remember')->validate($post)) {
            // TODO: Implement cusitom boolean Respect\Validator
            // Converting to boolean did not work well, 
            // This allows a wider range of true false values
            $remember = ($post['remember'] === 1 || 
                $post['remember'] === '1' || 
                $post['remember'] === true || 
                $post['remember'] === 'true');
        }
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
            !v::key('new', v::stringType())->validate($post)) 
        {
            return false;
        }
        return self::login_logoutCurrentAccount($app->request->post());
    }
}
