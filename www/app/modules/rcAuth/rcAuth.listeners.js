'use strict';

/* 
 * Auth Broadcast Listeners 
 * 
 * Set up the variious event listeners for the auth module.
 * 
 * The module only listens to one Angular event, $stateChangeStart,
 * all other events are in the rcAuth.constants AUTH_EVENTS constant.
 */

angular.module('rcAuth.listeners', [])
    .run(['$rootScope', '$state', 'AUTH_EVENTS', 'AuthService',

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
                    $state.go('member.dashboard');
                }
            });
        });

        // On: Login Failure
        $rootScope.$on(AUTH_EVENTS.loginFailed, function(event, args) {
            $rootScope.$evalAsync(function () {
                // Go to the login state
                $state.go('auth.login');
            });
        });

        // On: Logout Success
        $rootScope.$on(AUTH_EVENTS.logoutSuccess, function(event, args) {
            $rootScope.$evalAsync(function () {
                // Go to the login state
                $state.go('auth.login');
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
                $state.go('auth.login');
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
                $state.go('auth.login');
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

