'use strict';

/* 
 * Auth Constants
 * 
 * Contstants used by the auth module.
 */

var app = angular.module('rcAuth.constants', []);

// Events that are triggered by different auth states
app.constant('AUTH_EVENTS', {
    loginSuccess: 'auth-login-success',
    loginFailed: 'auth-login-failed',
    forgotpasswordFailed: 'auth-forgotpassword-failed',
    logoutSuccess: 'auth-logout-success',
    sessionTimeout: 'auth-session-timeout',
    notAuthenticated: 'auth-not-authenticated',
    notAuthorized: 'auth-not-authorized'
});

// Events that are triggered by different auth states
app.constant('AUTH_COOKIES', {
    userEmail: '_as_ue_2180',
    apiKey: '_as_uk_2332',
    apiToken: '_as_ut_7942'
});

// Roles used for authorization and to determin
// page access when navigating the website.
app.constant('USER_ROLES', {
    guest: "1",
    admin: "2",
    user: "3"
});

app.constant('FACEBOOK_CONFIG', {
    appId: '892044264250192', // Dev
    comma: 'placeholder'
});