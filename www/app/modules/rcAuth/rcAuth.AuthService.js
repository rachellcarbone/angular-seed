'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth.AuthService', [])
    .factory('AuthService', ['$rootScope', '$cookies', '$q', '$log', '$filter', 'UserSession', 'AUTH_EVENTS', 'AUTH_COOKIES', 'VisibilityService', 'ApiRoutesAuth', 
    function($rootScope, $cookies, $q, $log, $filter, UserSession, AUTH_EVENTS, AUTH_COOKIES, VisibilityService, API) {
        
        var factory = {};
        
        factory.init = function() {
            /* Returns promise that always resolves true */ 
            return $q(function (resolve, reject) {
                if (UserSession.get()) {
                    return resolve(true);
                }
                
                var credentials = {
                    'key' : $cookies.get(AUTH_COOKIES.userKey),
                    'token' : $cookies.get(AUTH_COOKIES.userToken)
                };
            
                if (credentials.key && credentials.token) {
                        API.getAuthenticatedUser(credentials)
                        .then(function (data) {
                            if (!UserSession.create(data.user)) {
                                $log.error('[authInit] Credentials found but session Couldn\'t be Created', data);
                            }
                            return resolve(true);
                        }, function (error) {
                            $log.info('[authInit] No Credentials Found', error);
                            return resolve(true);
                        });
                        
                }
                return resolve(true);
            });
        };
        
        factory.login = function(credentials) {
            if($cookies.get(AUTH_COOKIES.userKey)) { 
                credentials.logout = $cookies.get(AUTH_COOKIES.userKey); 
            }
            
            $cookies.remove(AUTH_COOKIES.userEmail);
            $cookies.remove(AUTH_COOKIES.userKey);
            $cookies.remove(AUTH_COOKIES.userToken);
            UserSession.destroy();
            
            return $q(function (resolve, reject) {
                    API.postLogin(credentials)
                    .then(function (data) {
                        
                        if (UserSession.create(data.user)) {
                            var date = new Date();
                            var time = date.getTime();
                            var hours = parseInt(data.sessionLifeHours) || 1;
                            date.setTime(time + (hours * 60 * 60 * 1000));

                            console.log("Cookie Expires at: " + $filter('date')(date, 'medium') + ", currently: " + $filter('date')(new Date(), 'medium'));

                            //put valid login creds in a cookie
                            $cookies.put(AUTH_COOKIES.userEmail, data.user.email, {expires: date});
                            $cookies.put(AUTH_COOKIES.userKey, data.user.apiKey, {expires: date});
                            $cookies.put(AUTH_COOKIES.userToken, data.user.apiToken, {expires: date});
                        
                            resolve(UserSession.get());
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                        } else {
                            reject("Error: Could not log in user. Please try again later.");
                            $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                            $log.error(data);
                        }
                    }, function (error) {
                        reject(AUTH_EVENTS.loginFailed);
                        $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                    });
            });
        };

        factory.logout = function() {
            var logout = ($cookies.get(AUTH_COOKIES.userKey)) ? 
                $cookies.get(AUTH_COOKIES.userKey) : false;
            
            return $q(function (resolve, reject) {
                if (logout) {
                    API.postLogout(logout);
                }
                $cookies.remove(AUTH_COOKIES.userEmail);
                $cookies.remove(AUTH_COOKIES.userKey);
                $cookies.remove(AUTH_COOKIES.userToken);
                UserSession.destroy();

                /* Logout success event */
                $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
                
                return resolve(AUTH_EVENTS.logoutSuccess);
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
                if (user) {
                    return resolve(user);
                }
                
                var credentials = {
                    'key' : $cookies.get(AUTH_COOKIES.userKey),
                    'token' : $cookies.get(AUTH_COOKIES.userToken)
                };
            
                if (credentials.key && credentials.token) {
                        API.getAuthenticatedUser(credentials)
                        .then(function (data) {
                            if (UserSession.create(data.user)) {
                                return resolve(UserSession.get());
                            } else {
                                $log.error('[isAuthenticated] Session Couldn\'t be Created', data);
                                return reject(AUTH_EVENTS.notAuthenticated);
                            }
                        }, function (error) {
                            return reject(AUTH_EVENTS.notAuthenticated);
                        });
                } else {
                    return reject(AUTH_EVENTS.notAuthenticated);
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
