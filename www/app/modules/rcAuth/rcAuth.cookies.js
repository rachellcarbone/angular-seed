'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth.cookies', [])
    .factory('CookieService', ['$cookies', 'AUTH_COOKIES', function($cookies, AUTH_COOKIES) {
        
        var factory = {};
        
        factory.getAuthCookie = function() {
            var credentials = {
                'apiKey' : $cookies.get(AUTH_COOKIES.apiKey),
                'apiToken' : $cookies.get(AUTH_COOKIES.apiToken)
            };

            return (credentials.apiKey && credentials.apiToken) ? credentials : false;
        };
        
        factory.setAuthCookie = function(apiKey, apiToken, sessionLifeHours) {
            var date = new Date();
            var time = date.getTime();
            var hours = parseInt(sessionLifeHours) || 1;
            date.setTime(time + (hours * 60 * 60 * 1000));

            var now = moment(new Date()).format('M/D/YYYY h:mm a');
            var exp = moment(date).format('M/D/YYYY h:mm a');

            console.log("Cookie Expires at: " + exp + ", currently: " + now);

            //put valid login creds in a cookie
            $cookies.put(AUTH_COOKIES.apiKey, apiKey, {expires: date});
            $cookies.put(AUTH_COOKIES.apiToken, apiToken, {expires: date});
        };

        factory.destroyAuthCookie = function() {
            $cookies.remove(AUTH_COOKIES.apiKey);
            $cookies.remove(AUTH_COOKIES.apiToken);
            return true;
        };
        
        return factory;
        
    }]);
