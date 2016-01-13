'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rc.auth.service', ['rc.auth.visibility'])
    .factory('AuthService', ['$rootScope', '$q', '$log', 'UserSession', 'AUTH_EVENTS', 'VisibilityService', 'APIV2Service', 
    function($rootScope, $q, $log, UserSession, AUTH_EVENTS, VisibilityService, API) {
        
        var api = {};
        
        api.login = function(credentials) {
            return $q(function (resolve, reject) {
                var fd = new FormData();
                fd.append('email', credentials.email);
                fd.append('password', credentials.password);

                API.post('auth/login/', fd, 'System unable to login.')
                    .then(function (data) {
                        if (UserSession.create(data)) {
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                            resolve(UserSession.get());
                        } else {
                            $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                            $log.error(data);
                            reject(AUTH_EVENTS.loginFailed);
                        }
                    }, function (error) {
                        $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                        reject(AUTH_EVENTS.loginFailed);
                    });
            });
        };

        api.logout = function() {                
            return $q(function (resolve, reject) {
                API.post('auth/logout/', new FormData(), 'System unable to logout.')
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

        api.forgotPasswordEmail = function(email) {
            var fd = new FormData();
            fd.append('email', email);

            return API.post('auth/password-reset/', fd, 'Error sending password reset email.');
        };
        
        api.validatePasswordResetToken = function(token) {
            if(!token) {
                return API.reject('Invalid password reset token.');
            }

            var fd = new FormData();
            fd.append('token', token);

            return API.post('auth/validate-token/', fd, 'Error validating token.');
        };

        api.changePassword = function(user) {
            if(!user.id || !user.email || !user.token || 
                    !user.password || user.password <= 0) {
                return API.reject('Invalid credentials please verify your information and try again.');
            }

            var fd = new FormData();
            fd.append('id', user.id);
            fd.append('email', user.email);
            fd.append('password', user.password);
            fd.append('token', user.token);

            return API.post('auth/change-password/', fd, 'Error changing password.');
        };

        api.isAuthenticated = function() {
            return $q(function (resolve, reject) {
                var user = UserSession.get();
                if (!user) {
                    API.get('auth/authenticated/', '[isAuthenticated] Error, User Not Authenticated.')
                        .then(function (data) {
                            if (UserSession.create(data)) {
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

        api.isAuthorized = function(authorizedRole) {
            return $q(function (resolve, reject) {
                
                // Checks a role (presumably for $state access or to see if a user can view
                // a page or menu item etc) to see if it is publicly accessable to everyone
                // including unautorized (not logged in) users.
                if (VisibilityService.isAccessUnauthenticated(authorizedRole)) {
                    // User doesn't have to be logged in
                    resolve(true);
                } else {
                    // Confirm that the user is logged in
                    api.isAuthenticated().then(function(results) {
                        
                        if (VisibilityService.isVisibleToUser(authorizedRole, UserSession.role())) {
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
        
        return api;
        
    }]);
