'use strict';

/* 
 * Auth Constants
 * 
 * Contstants used by the auth module.
 */

var app = angular.module('rc.auth.constants', []);

// Events that are triggered by different auth states
app.constant('AUTH_EVENTS',  {
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized'
});

// Roles used for authorization and to determin
// page access when navigating the website.
app.constant('USER_ROLES', {
    guest: 1,
    expired: 2,
    user: 3,
    admin: 4,
    super: 5
});