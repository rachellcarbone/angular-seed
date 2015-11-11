'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

var app = angular.module('rachels.auth', []);

app.constant('AUTH_EVENTS',  {
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized'
});

app.constant('USER_ROLES', {
    guest: 1,
    expired: 2,
    user: 3,
    admin: 4,
    super: 5
});

app.service('Session', [function() {
    var self = this;
    self.user = false;
    
    self.validateUser = function(opt) {
        return (typeof(opt) !== 'undefined' &&
                typeof(opt.id) !== 'undefined' &&
                typeof(opt.fname) !== 'undefined' &&
                typeof(opt.fname) !== 'undefined' &&
                typeof(opt.role) !== 'undefined');
    };
    
    self.create = function(opt) {
        if(self.validateUser(opt)) {
            self.user = {
                analystId: opt.id,
                analystName: opt.fname + ' ' + opt.lname,
                analystFName: opt.fname,
                analystLName: opt.lname,
                analystRole: opt.role
            };
            return true;
        } else {
            return false;
        }
    };
    
    self.destroy = function() {
        self.user = false;
        return true;
    };
    
    self.get = function() {
        return angular.copy(self.user);
    };
    
    return {
            create : self.create,
            destroy : self.destroy,
            get : self.get
        };
    }]);

app.factory('AuthService', ['$rootScope', 
    function($rootScope) {
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

/*
app.factory('AuthResolver', function ($q, $rootScope, $state) {
    return {
        resolve: function () {
            var deferred = $q.defer();
            deferred.resolve(true);
            var unwatch = $rootScope.$watch('_userSession', function (currentUser) {
                if (angular.isDefined(currentUser)) {
                    if (currentUser) {
                        deferred.resolve(currentUser);
                    } else {
                        deferred.reject();
                        $state.go('auth.login');
                    }
                    unwatch();
                }
            });
            return deferred.promise;
        }
    };
});*/

/* Authentication Interceptor
 * If the http request returns an error, 
 * and it's 401, 403 or 419 then broadcast the not logged in event. */
app.factory('AuthInterceptor',
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


/* Auth Broadcast Listeners */
app.run(['$rootScope', '$state', 'AUTH_EVENTS', 'AuthService', '$log',
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

