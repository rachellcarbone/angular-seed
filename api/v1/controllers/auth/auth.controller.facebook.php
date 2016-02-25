<?php namespace API;
require_once dirname(__FILE__) . '/auth.controller.native.php';

use \Respect\Validation\Validator as v;

class AuthControllerFacebook {
    
    static function checkLoginStatus() {
        $profile = false;
        $accessToken = self::getActiveAccessToken();
        if($accessToken) {
            $profile = self::getProfile($accessToken);
        }
        return $profile;
    }
    
    private static function getFBConn() {
        // TODO: Connect this to the config
        return new \Facebook\Facebook([
            'app_id' => "892044264250192",
            'app_secret' => "680ed93062e7d97347a593cab43533f2",
            'default_graph_version' => 'v2.5'
        ]); // Dev
        /*
        return new \Facebook\Facebook([
            'app_id' => "1538616896450172",
            'app_secret' => "f42275546311eb9094e9a2767a051086",
            'default_graph_version' => 'v2.5'
        ]); // Triv
         */
    }
    
    /*
     * Facebook JavaScript Login Helper
     * 
     * Is a user authenticated via facebook (aka, currently logged in).
     * 
     * https://developers.facebook.com/docs/php/howto/example_access_token_from_javascript
     */
    private static function getActiveAccessToken() {
        $fb = self::getFBConn();

        $helper = $fb->getJavaScriptHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $logger = new Logging('facebook_api_exception');
            $logger->logException($e);
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $logger = new Logging('facebook_api_exception');
            $logger->logException($e);
        }

        if (isset($accessToken)) {
            $_SESSION['FACEBOOK_ACCESS_TOKEN'] = (string) $accessToken;
            return (string) $accessToken;
        }
        return false;
    }
    
    /*
     * Get Profile for Active Access Token
     * 
     * https://developers.facebook.com/docs/php/howto/example_retrieve_user_profile
     * https://developers.facebook.com/docs/php/GraphNode/5.0.0#user-instance-methods
     * https://developers.facebook.com/docs/facebook-login/permissions
     * 
     * id
     * name
     * first_name
     * last_name
     * age_range
     * link
     * gender
     * locale
     * timezone
     * updated_time
     * verified
     * email
     */
    static function getProfile($accessToken) {
        $fb = self::getFBConn();

        try {
            // Returns a `\Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,first_name,last_name,age_range,link,gender,locale,timezone,email', $accessToken);
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            $logger = new Logging('facebook_api_exception');
            $logger->logException($e);
            return false;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            $logger = new Logging('facebook_api_exception');
            $logger->logException($e);
            return false;
        }

        return $response->getGraphUser();
    }
    
    static function login($app) {
        // Get Post Data
        $post = $app->request->post();
        
        // Validate Sent Input
        $valid = self::validateFacebookProfile($post);
        if($valid !== true) {
            return array('authenticated' => false, 'msg' => 'Facebook login failed. Check your parameters and try again.');
        }
        
        // Look for user with that facebook id
        $existing = AuthData::selectUserByFacebookId($post['facebookId']);
        if(!$existing) {         
            // Look for user with that email
            $emailExists = AuthData::selectUserByEmail($post['email']);
            if(!$emailExists) { 
                /// FAIL - If a user with that email already exists
                return array('authenticated' => false, 'msg' => 'Login failed. No user with that Facebook account exists.');  
            }
                
            $facebookAdded = AuthData::updateUserFacebookId(array(':id' => $emailExists->id, ':facebook_id' => $post['facebookId']));
            if(!$facebookAdded) {
                /// FAIL - If a user with that email already exists
                return array('authenticated' => false, 'msg' => 'Login failed. No user with that Facebook account exists.');  
            }
            unset($emailExists->password);
            $existing = $emailExists;
        }
        
        // Create logged in token
        $token = AuthControllerNative::createAuthToken($app, $existing->id);
        if($token) {
            $found = array('user' => $existing);
            $found['user']->apiKey = $token['apiKey'];
            $found['user']->apiToken = $token['apiToken'];
            $found['sessionLifeHours'] = $token['sessionLifeHours'];
            $found['authenticated'] = true;

            // Send the session life back (in hours) for the cookies
            return $found;
        } else {
            return array('authenticated' => false, 'msg' => 'Facebook login failed to create token.');   
        }
    }
    
    /*
     * Incoming
     * ageRange:{"min":21}
        email:rachel.dotey@gmail.com
        facebookId:55555555555555555
        link:https://www.facebook.com/app_scoped_user_id/55555555555555555/
        locale:en_US
        nameFirst:Rachel
        nameLast:Cantyoutell
        timezone:-5
     */
    static function signup($app) {
        // Get Post Data
        $post = $app->request->post();
        
        // Validate Sent Input
        $valid = self::validateFacebookProfile($post);
        if($valid !== true) {
            return array('registered' => false, 'msg' => 'Facebook signup failed. Check your parameters and try again.');
        }
        
        /*
        $token = self::getActiveAccessToken();
        $profile = self::getProfile($post['accessToken']);
        if(true || !$token) {
            return array('registered' => false, 'msg' => 'Facebook signup failed. You are not logged into Facebook.', 'token' => $token, 'profile' => $profile, 'post' => $post, 'cookie' => $_COOKIE);
        }
         */
        
        // Look for user with that email
        $existing = AuthData::selectUserByEmail($post['email']);
        if($existing) { 
            /// FAIL - If a user with that email already exists
            return array('registered' => false, 'msg' => 'Facebook signup failed. A user with that email already exists.');   
        }
        
        $validUser = array(
            ':email' => $post['email'],
            ':name_first' => $post['nameFirst'],
            ':name_last' => $post['nameLast'],
            ':facebook_id' => $post['facebookId']
        );
        $userId = AuthData::insertFacebookUser($validUser);
        if(!$userId) {
            /// FAIL - If Inserting the user failed
            return array('registered' => false, 'msg' => 'Facebook signup failed. Could not save user.');
        }
        
        // Select our new user
        $user = AuthData::selectUserById($userId);
        if(!$user) { 
            /// FAIL - If Inserting the user failed (hopefully this is redundant)
            return array('registered' => false, 'msg' => 'Facebook signup failed. Could not select user.');    
        }

        // Save "Where did you hear about us" and any other additional questions
        // This is "quiet" in that it may not execute if no paramters match
        // And it doesnt set the response for the api call
        InfoController::quietlySaveAdditional($post, $user->id);

        // Create an authorization
        $token = AuthControllerNative::createAuthToken($app, $user->id);
        if($token) {
            // Create the return object
            $found = array('user' => $user);
            $found['user']->apiKey = $token['apiKey'];
            $found['user']->apiToken = $token['apiToken'];
            $found['sessionLifeHours'] = $token['sessionLifeHours'];
            $found['registered'] = true;

            return $found;
        } else {
            return array('registered' => false, 'msg' => 'Facebook Signup failed to creat auth token.');
        }
    }
    
    /*
     * return String|bool Failed message or true 
     */
    private static function validateFacebookProfile($post) {
        return (v::key('accessToken', v::stringType())->validate($post) || 
           v::key('facebookId', v::stringType())->validate($post) || 
           v::key('nameFirst', v::stringType())->validate($post) || 
           v::key('nameLast', v::stringType())->validate($post) || 
           v::key('email', v::email())->validate($post));
    }
}
