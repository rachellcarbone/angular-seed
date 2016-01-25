'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('AuthService', [
    'rcAuth.constants',
    'rcAuth.cookies',
    'rcAuth.facebook',
    'rcAuth.interceptors',
    'rcAuth.user',
    'rcAuth.visibility'
]).factory('AuthService', ['$rootScope', '$q', '$log', 'UserSession', 'AUTH_EVENTS', 'VisibilityService', 'ApiRoutesAuth', 'FacebookAuthService', 'CookieService',
    function($rootScope, $q, $log, UserSession, AUTH_EVENTS, VisibilityService, API, FacebookAuthService, CookieService) {
        
        var factory = {};
        
        factory.init = function() {
            /* Returns promise that always resolves true */ 
            return $q(function (resolve, reject) {
                if (UserSession.get()) {
                    return resolve(true);
                }
                
                var credentials = CookieService.getAuthCookie();
            
                if (credentials) {
                        API.getAuthenticatedUser(credentials)
                        .then(function (data) {
                            data.user.apiKey = credentials.apiKey;
                            data.user.apiToken = credentials.apiToken;
                            if (!UserSession.create(data.user)) {
                                $log.error('[authInit] Credentials found but session Couldn\'t be Created', data);
                            }
                            return resolve(true);
                        }, function (error) {
                            $log.info('[authInit] No Credentials Found', error);
                            return resolve(true);
                        });
                } else {
                    return resolve(true);
                }
            });
        };
        
        factory.signup = function() {
            
        };
        
        factory.login = function(credentials) {
            var credentials = CookieService.getAuthCookie();
            if(credentials) { 
                credentials.logout = credentials.apiKey; 
            }
            
            CookieService.destroyAuthCookie();
            UserSession.destroy();
            
            return $q(function (resolve, reject) {
                    API.postLogin(credentials)
                    .then(function (data) {
                        
                        if (UserSession.create(data.user)) {
                            //put valid login creds in a cookie
                            CookieService.setAuthCookie(data.user.apiKey, data.user.apiToken, data.sessionLifeHours);
                        
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
            
            var credentials = CookieService.getAuthCookie();
            var logout = (credentials) ? credentials.apiKey : false;
            
            return $q(function (resolve, reject) {
                if (logout) {
                    API.postLogout(logout);
                }
                CookieService.destroyAuthCookie();
                UserSession.destroy();

                /* Logout success event */
                $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
                
                return resolve(AUTH_EVENTS.logoutSuccess);
            });
        };

        factory.isAuthenticated = function() {
            return $q(function (resolve, reject) {
                var user = UserSession.get();
                if (user) {
                    return resolve(user);
                }
                
                var credentials = CookieService.getAuthCookie();
                if (credentials) {
                        API.getAuthenticatedUser(credentials)
                        .then(function (data) {
                            data.user.apiKey = credentials.apiKey;
                            data.user.apiToken = credentials.apiToken;
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
        
        factory.facebookLogin = function() {
            return FacebookAuthService.login().then(function(data) {
                console.log('Auth Service ', data);
            }, function(error) {
                
            });
        };
        
        factory.facebookSignup = function() {
            return FacebookAuthService.login().then(function(data) {
                console.log('Auth Service ', data);
            }, function(error) {
                
            });
        };
        
        return factory;
        
    }]);
