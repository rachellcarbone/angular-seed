'use strict';

/* 
 * Authentication Through Facebook
 * 
 * http://jberta93.github.io/ng-facebook-api/
 * https://developers.facebook.com/docs/javascript/howto/angularjs
 */

var app = angular.module('rcAuth.facebook', ['ng-facebook-api']);

app.config(['facebookProvider', 'FACEBOOK_CONFIG', function (facebookProvider, FACEBOOK_CONFIG) {
    /**
     * Here the list of params used to configure the provider
     * @param appId
     * @param status
     * @param xfbml
     * @param cookie
     * @param api-version
     */
    facebookProvider.setInitParams(FACEBOOK_CONFIG.appId, true, true, true, 'v2.5', 'en_US');
    facebookProvider.setPermissions(['public_profile','email']);
}]);

app.factory('FacebookAuthService', ['facebook', function(facebook) {
        
    var api = {};

    /*
     * Success: {
     *      accessToken: "CAAMrxxxxxwZDZD",
     *      expiresIn: 7028,
     *      signedRequest: "6ylgvxxxxxzNCJ9",
     *      userID: "129319070782734",
     * }
     * https://github.com/jberta93/ng-facebook-api/wiki/login
     * https://developers.facebook.com/docs/reference/javascript/FB.login/v2.5
     */
    api.login = function() {
        return facebook.login();
    };

    api.getUser = function(id) {
        var fields = 'id,first_name,last_name,age_range,link,gender,locale,timezone,email';
        return facebook.getUser(id, { 'fields' : fields });
    };

    /*
     * https://developers.facebook.com/docs/reference/javascript/FB.logout
     */
    api.logout = function() {
        return facebook.logout();
    };
    
    /*
    {
        status: 'connected',
        authResponse: {
            accessToken: '...',
            expiresIn:'...',
            signedRequest:'...',
            userID:'...'
        }
    }
     * https://developers.facebook.com/docs/reference/javascript/FB.getLoginStatus
     */
    api.isAuthenticated = function() {
        return facebook.checkLoginStatus();
    };

    return api;

}]);