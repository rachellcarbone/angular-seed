'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('auth.service', [])
    .factory('AuthService', ['$rootScope', function($rootScope) {
            
/*
app.factory('AuthService', ['$rootScope', '$q', '$log', 'Session', 'AUTH_EVENTS', 'USER_ROLES', 'APIV2Service', 
    function($rootScope, $q, $log, Session, AUTH_EVENTS, USER_ROLES, API) {
        var self = this;
        self.login = function(credentials) {
            return $q(function (resolve, reject) {
                var fd = new FormData();
                fd.append('email', credentials.email);
                fd.append('password', credentials.password);

                API.post('auth/login/', fd, 'System unable to login.')
                    .then(function (data) {
                        if (Session.create(data)) {
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                            resolve(Session.get());
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

        self.logout = function() {                
            return $q(function (resolve, reject) {
                API.post('auth/logout/', new FormData(), 'System unable to logout.')
                    .then(function (data) {
                        Session.destroy();
                        $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
                        resolve(AUTH_EVENTS.logoutSuccess);
                    }, function (error) {
                        $rootScope.$broadcast(AUTH_EVENTS.logoutFailed);
                        reject(AUTH_EVENTS.logoutFailed);
                    });
            });
        };

        self.forgotPasswordEmail = function(email) {
            var fd = new FormData();
            fd.append('email', email);

            return API.post('auth/password-reset/', fd, 'Error sending password reset email.');
        };
        
        self.validatePasswordResetToken = function(token) {
            if(!token) {
                return API.reject('Invalid password reset token.');
            }

            var fd = new FormData();
            fd.append('token', token);

            return API.post('auth/validate-token/', fd, 'Error validating token.');
        };

        self.changePassword = function(user) {
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

        self.isAuthenticated = function() {
            return $q(function (resolve, reject) {
                var user = Session.get();
                if (!user) {
                    API.get('auth/authenticated/', '[isAuthenticated] Error, User Not Authenticated.')
                        .then(function (data) {
                            if (Session.create(data)) {
                                resolve(Session.get());
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

        self.isAuthorized = function(authorizedRole) {
            return $q(function (resolve, reject) {
                
                if (authorizedRole === USER_ROLES.guest) {
                    // User doesn't have to be logged in
                    resolve(true);
                } else {
                    self.isAuthenticated().then(function(results) {
                        var role = Session.get().analystRole;
                        var analystRole = USER_ROLES[role];
                        
                        if (authorizedRole <= analystRole) {
                            resolve(true);
                        } else {
                            reject(AUTH_EVENTS.notAuthorized);
                        }
                    }, function(results) {
                        reject(AUTH_EVENTS.notAuthenticated);
                    });
                }
            });
        };
        
        self.visibleToRole = function(authorizedRole) {
            var user = Session.get();
            return (authorizedRole === USER_ROLES.guest ||
                    (user && authorizedRole <= USER_ROLES[user.analystRole]));
        };

        return {
            getUser: Session.get,
            login: self.login,
            logout: self.logout,
            forgotPasswordEmail: self.forgotPasswordEmail,
            validatePasswordReset: self.validatePasswordResetToken,
            changePassword: self.changePassword,
            isAuthenticated: self.isAuthenticated,
            isAuthorized: self.isAuthorized,
            visibleToRole: self.visibleToRole
        };
        */
        return {};
    }]);
