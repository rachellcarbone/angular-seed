'use strict';

/* 
 * Member Pages Module
 * 
 * Include controllers and other modules required on authenticated pages.
 */

angular.module('app.member', [
    'app.member.header',
    'app.member.footer',
    'app.member.dashboard',
    'app.member.profile',
    'app.member.settings'
]);