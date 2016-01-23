'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth', [
    'rcAuth.AuthService',
    'rcAuth.UserSession',
    'rcAuth.VisibilityService',
    'rcAuth.constants',
    'rcAuth.interceptor',
    'rcAuth.listeners'
]);