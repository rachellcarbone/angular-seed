'use strict';

/* 
 * Authentication Through Facebook
 * 
 * http://jberta93.github.io/ng-facebook-api/
 * https://developers.facebook.com/docs/javascript/howto/angularjs
 */

var app = angular.module('rcAuth.facebook', ['ng-facebook-api']);

app.config(['FacebookProvider', 'FACEBOOK_CONFIG', function (facebookProvider, FACEBOOK_CONFIG) {
    /**
     * Here the list of params used to configure the provider
     * @param appId
     * @param status
     * @param xfbml
     * @param cookie
     * @param api-version
     */
    facebookProvider.setInitParams(FACEBOOK_CONFIG.appId, true, true, true, 'v2.5'); 
    
    //if your app use extra permissions set it
    facebookProvider.setPermissions(['email', 'public_profile']);
}]);

app.factory('FacebookAuthService', ['facebook', 
    function(facebook) {
        
        var api = {};
        
        api.login = function() {
            facebook.login().then(function (resp) {
                console.log("Auth response");
                console.log(resp);

            }, function (err) {
                console.log(err);
            });
        };

        api.logout = function() {
        };

        api.isAuthenticated = function() {
        };
        
        return api;
        
    }]);