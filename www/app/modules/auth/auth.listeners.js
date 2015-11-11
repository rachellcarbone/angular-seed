'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */


/* Auth Broadcast Listeners */
angular.module('auth.listeners', ['auth.constants', 'auth.service'])
    .run(['$rootScope', '$state', 'AUTH_EVENTS', 'AuthService', '$log',

    function($rootScope, $state, AUTH_EVENTS, AuthService, $log) {

        /* On Start of Route Change
         * Checks if user has permissions to access that route,
         * if not broadcast a not authorized event.
        $rootScope.$on('$stateChangeStart',
                function(event, toState, toParams, fromState, fromParams) {
                    AuthService.isAuthorized(toState.data.authorizedRoles).then(function(results) {
                        // Do nothing - they are authorized 
                    }, function(results) {
                        // Broadcast reason for failure
                        $rootScope.$broadcast(results);
                    });
                }); */

        $rootScope.$on(AUTH_EVENTS.loginSuccess, function() {
            $rootScope.$evalAsync(function () {
                $state.go('app.dashboard');
            });
        });

        $rootScope.$on(AUTH_EVENTS.loginFailed, function() {
            $rootScope.$evalAsync(function () {
                $state.go('auth.login');
            });
        });

        $rootScope.$on(AUTH_EVENTS.logoutSuccess, function() {
            $rootScope.$evalAsync(function () {
                $state.go('auth.login');
            });
        });

        $rootScope.$on(AUTH_EVENTS.sessionTimeout, function() {
            $rootScope.$evalAsync(function () {
                $state.go('auth.login');
            });
        });

        $rootScope.$on(AUTH_EVENTS.notAuthenticated, function() {
            $rootScope.$evalAsync(function () {
                $state.go('auth.login');
            });
        });

        $rootScope.$on(AUTH_EVENTS.notAuthorized, function() {
            $rootScope.$evalAsync(function () {
                $state.go('app.error.notauthorized');
            });
        });
        
    }]);

