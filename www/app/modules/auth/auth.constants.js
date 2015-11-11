'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

var app = angular.module('auth.constants', []);

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