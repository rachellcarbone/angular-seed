'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('auth', [
    'auth.constants',
    'auth.session',
    'auth.service',
    //'auth.resolver',
    'auth.listeners',
    'auth.interceptor'
]);