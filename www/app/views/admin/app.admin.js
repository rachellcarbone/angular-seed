'use strict';

/* 
 * Admin Pages Module
 * 
 * Include controllers and other modules required on the admin pages.
 */

angular.module('app.admin', [
    'app.admin.layout',
    'app.admin.header',
    'app.admin.footer',
    'app.admin.systemVariables',
    'app.admin.dashboard',
    'app.admin.groups',
    'app.admin.roles',
    'app.admin.tagVisibility',
    'app.admin.users'
]);