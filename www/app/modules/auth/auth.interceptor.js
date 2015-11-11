'use strict';

/* Authentication Interceptor
 * If the http request returns an error, 
 * and it's 401, 403 or 419 then broadcast the not logged in event. */

angular.module('auth.interceptor', ['auth.constants'])
    .factory('AuthInterceptor',
    
        function($rootScope, $q, AUTH_EVENTS) {
            return {
                responseError: function(response) {
                    $rootScope.$broadcast({
                        401: AUTH_EVENTS.notAuthenticated,
                        403: AUTH_EVENTS.notAuthorized,
                        419: AUTH_EVENTS.sessionTimeout,
                        440: AUTH_EVENTS.sessionTimeout
                    }[response.status], response);
                    
                    return $q.reject(response);
                }
            };
            
        });