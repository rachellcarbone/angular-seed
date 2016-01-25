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

app.factory('AuthInterceptor', function($rootScope, $q, AUTH_EVENTS) {

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

        // Reject the response as normal
        return $q.reject(response);
    };

    return apiRequests;

});

app.run(['$rootScope', '$state', 'AUTH_EVENTS', 'AuthService',
    function($rootScope, $state, AUTH_EVENTS, AuthService) {

        // On state change start, check that the user is authorized
        // https://docs.angularjs.org/api/ng/type/$rootScope.Scope#$on
        $rootScope.$on('$stateChangeStart',
                function(event, toState, toParams, fromState, fromParams) {
                    AuthService.isAuthorized(toState.data.authorizedRoles).then(function(results) {
                        // Do nothing - they are authorized 
                    }, function(results) {
                        event.preventDefault();
                        // Broadcast reason for failure
                        // https://docs.angularjs.org/api/ng/type/$rootScope.Scope#$broadcast
                        $rootScope.$broadcast(results, { 'state' : toState.name, 'params' : toParams });
                    });
                });

        /* 
         * Auth Broadcast Listeners 
         * 
         * Set up the variious event listeners for the auth module.
         */
        
        // On: Login Success
        $rootScope.$on(AUTH_EVENTS.loginSuccess, function(event, args) {
            // Evaluate asynchronously
            // https://docs.angularjs.org/api/ng/type/$rootScope.Scope#$evalAsync
            $rootScope.$evalAsync(function () {
                // If the state was saved during a previous auth event
                if(angular.isDefined($rootScope.redirectPlaceholder)) {
                    // Go to that state after login
                    $state.go($rootScope.redirectPlaceholder.state, $rootScope.redirectPlaceholder.params);
                    // And get rid of the evidence 
                    delete $rootScope.redirectPlaceholder;
                } else {
                    // Go to the loged in user dashboard
                    $state.go('app.member.dashboard');
                }
            });
        });

        // On: Login Failure
        $rootScope.$on(AUTH_EVENTS.loginFailed, function(event, args) {
            $rootScope.$evalAsync(function () {
                // Go to the login state
                $state.go('app.auth.login');
            });
        });

        // On: Logout Success
        $rootScope.$on(AUTH_EVENTS.logoutSuccess, function(event, args) {
            $rootScope.$evalAsync(function () {
                // Go to the login state
                $state.go('app.public.landing');
            });
        });

        // On: Session Timeout
        $rootScope.$on(AUTH_EVENTS.sessionTimeout, function(event, args) {
            $rootScope.$evalAsync(function () {
                // If the sent the state we were going to
                if(angular.isDefined(args.state)) {
                    // Save it for rediredt after login
                    $rootScope.redirectPlaceholder = args;
                }
                // Go to the login state
                $state.go('app.auth.login');
            });
        });

        // On: User Not Authenticated
        $rootScope.$on(AUTH_EVENTS.notAuthenticated, function(event, args) {
            $rootScope.$evalAsync(function () {
                // If the sent the state we were going to
                if(angular.isDefined(args.state)) {
                    // Save it for rediredt after login
                    $rootScope.redirectPlaceholder = args;
                }
                // Go to the login state
                $state.go('app.auth.login');
            });
        });

        // On: User Not Authorized
        $rootScope.$on(AUTH_EVENTS.notAuthorized, function(event, args) {
            $rootScope.$evalAsync(function () {
                // Go to the user not authorized error page
                $state.go('app.error.notauthorized');
            });
        });
        
    }]);