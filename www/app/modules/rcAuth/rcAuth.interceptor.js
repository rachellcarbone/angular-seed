'use strict';

/* 
 * Authentication Interceptor
 * 
 * Add an $http interceptor (aka request pre processing) to check
 * server response http headers for authentication statuses.
 * https://docs.angularjs.org/api/ng/service/$http
 */

var app = angular.module('rcAuth.interceptor', []);

app.config(['$httpProvider', function($httpProvider){
    // Push the Auth Interceptor onto the $httpProvider.interceptors array
    $httpProvider.interceptors.push('AuthInterceptor');
}]);

app.factory('AuthInterceptor',
    function($rootScope, $q, AUTH_EVENTS, UserSession) {
        
        var apiRequests = this;
          
        // Interceptor gets called when a previous interceptor 
        // threw an error or resolved with a rejection.
        apiRequests.responseError = function(response) {
            // If the http request returns a 401, 403 or 419 
            // then broadcast the apropriate auth event. 
            $rootScope.$broadcast({
                401: AUTH_EVENTS.notAuthenticated,
                403: AUTH_EVENTS.notAuthorized,
                419: AUTH_EVENTS.sessionTimeout
            }[response.status], response);

                // May need this here, still testing
                // Redirect ohhh.... but I dont have the state.... hmmm...
                // Maybe I dont want this interceptor...
                // , { 'state' : toState.name, 'params' : toParams }

            // Reject the response as normal
            return $q.reject(response);
        };
            
        return apiRequests;
        
    });