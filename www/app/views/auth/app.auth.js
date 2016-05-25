'use strict';

/* 
 * Auth Pages Module
 * 
 * Include controllers and other modules required on auth pages.
 */

angular.module('app.auth', [
    'app.auth.layout',
    'app.auth.header',
    'app.auth.footer',
    'app.auth.login',
    'app.auth.playerInvite',
    'app.auth.signup',
    'app.auth.signupVenue',
    'app.auth.resetPassword'
]);