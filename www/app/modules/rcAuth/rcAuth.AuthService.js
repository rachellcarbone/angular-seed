'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth.AuthService', [])
    .factory('AuthService', ['$rootScope', '$q', '$log', 'UserSession', 'AUTH_EVENTS', 'VisibilityService', 'ApiRoutesAuth', 
    function($rootScope, $q, $log, UserSession, AUTH_EVENTS, VisibilityService, API) {
        
        var factory = {};
        
        factory.login = function(credentials) {
            return $q(function (resolve, reject) {
                    API.postLogin(credentials)
                    .then(function (data) {
                        if (UserSession.create(data.user)) {
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                            resolve(UserSession.get());
                        } else {
                            $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                            $log.error(data);
                            reject("Error: Could not log in user. Please try again later.");
                        }
                    }, function (error) {
                        $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                        reject(AUTH_EVENTS.loginFailed);
                    });
            });
        };

        factory.logout = function() {                
            return $q(function (resolve, reject) {
                    API.postLogout()
                    .then(function (data) {
                        UserSession.destroy();
                        $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
                        resolve(AUTH_EVENTS.logoutSuccess);
                    }, function (error) {
                        $rootScope.$broadcast(AUTH_EVENTS.logoutFailed);
                        reject(AUTH_EVENTS.logoutFailed);
                    });
            });
        };

        factory.forgotPasswordEmail = function(email) {
            return API.postForgotPasswordEmail(email);
        };
        
        factory.validatePasswordResetToken = function(token) {
            return API.postValidatePasswordResetToken(token);
        };

        factory.changePassword = function(user) {
            return API.updatePassword(user);
        };

        factory.isAuthenticated = function() {
            return $q(function (resolve, reject) {
                var user = UserSession.get();
                if (!user) {
                        API.getAuthenticatedUser()
                        .then(function (data) {
                            if (UserSession.create(data.user)) {
                                resolve(UserSession.get());
                            } else {
                                $log.error('[isAuthenticated] Session Couldn\'t be Created', data);
                                reject(AUTH_EVENTS.notAuthenticated);
                            }
                        }, function (error) {
                            reject(AUTH_EVENTS.notAuthenticated);
                        });
                } else {
                    resolve(user);
                }
            });
        };

        factory.isAuthorized = function(authorizedRole) {
            return $q(function (resolve, reject) {
                
                // Checks a role (presumably for $state access or to see if a user can view
                // a page or menu item etc) to see if it is publicly accessable to everyone
                // including unautorized (not logged in) users.
                if (VisibilityService.isAccessUnauthenticated(authorizedRole)) {
                    // User doesn't have to be logged in
                    resolve(true);
                } else {
                    // Confirm that the user is logged in
                    factory.isAuthenticated().then(function(results) {
                        
                        if (VisibilityService.isVisibleToUser(authorizedRole, UserSession.roles())) {
                            resolve(true);
                        } else {
                            reject(AUTH_EVENTS.notAuthorized);
                        }
                        
                    }, function(results) {
                        // Reject because the user was not logged in
                        reject(AUTH_EVENTS.notAuthenticated);
                    });
                }
            });
        };
        
        return factory;
        
    }]);
