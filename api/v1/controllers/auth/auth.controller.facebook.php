<?php namespace API;

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
    static function signup($post) {
        if(!v::key('accessToken', v::stringType())->validate($post) || 
           !v::key('facebookId', v::stringType())->validate($post) || 
           !v::key('nameFirst', v::stringType())->validate($post) || 
           !v::key('nameLast', v::stringType())->validate($post) || 
           !v::key('email', v::email())->validate($post)) {
            // Validate input parameters
            return array('registered' => false, 'msg' => 'Facebook signup failed. Check your parameters and try again.');
        }
        /*
        $token = self::getActiveAccessToken();
        $profile = self::getProfile($post['accessToken']);
        if(true || !$token) {
            return array('registered' => false, 'msg' => 'Facebook signup failed. You are not logged into Facebook.', 'token' => $token, 'profile' => $profile, 'post' => $post, 'cookie' => $_COOKIE);
        }
         */
        $existing = AuthData::selectUserByEmail($post['email']);
        if($existing) { 
            return array('registered' => false, 'msg' => 'Facebook signup failed. A user with that email already exists.');      
        }
        
        $validUser = array(
            ':email' => $post['email'],
            ':name_first' => $post['nameFirst'],
            ':name_last' => $post['nameLast'],
            ':facebook_id' => $post['facebookId']
        );
        $userId = AuthData::insertFacebookUser($validUser);
        if($userId) {
            $user = AuthData::selectUserById($userId);
            if(!$user) { 
                return array('registered' => false, 'msg' => 'Facebook signup failed. Could not select user.');    
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
            
            // Send the session life back (in hours) for the cookies
            return array('registered' => true, 'user' => $user, 'sessionLifeHours' => $hours);
        }
        return array('registered' => false, 'msg' => 'Facebook signup failed. Could not save user.');
        
    }
    
    private static function login_getSessionExpirationInHours($post) {
        $remember = (v::key('remember', v::stringType())->validate($post)) ? 
                boolval($post['remember']) : false;
        
        // TODO: Change this to use config var
        return (!$remember) ? 1 : 3 * 24; // 1 Hours or 3 days if remember was checked
    }
}
