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
}
