<?php namespace API;

use \Respect\Validation\Validator as v;

class AuthControllerFacebook {
    
    static $appId = "892044264250192"; // Dev
    static $appSecret = "680ed93062e7d97347a593cab43533f2"; // Dev
    //static $appId = "1538616896450172"; //Triv
    //static $appSecret = "f42275546311eb9094e9a2767a051086"; // Triv
    
    static function checkLoginStatus() {
        $profile = false;
        $accessToken = self::getActiveAccessToken();
        if($accessToken) {
            $profile = self::getProfile($accessToken);
        }
        return $profile;
    }
    
    private static function getFBConn() {
        return new \Facebook\Facebook([
            'app_id' => self::appId,
            'app_secret' => self::appSecret,
            'default_graph_version' => 'v2.5'
        ]);
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
    
    static function signup($app) {
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
}
