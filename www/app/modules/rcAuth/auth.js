'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rc.auth', [
    'rc.auth.constants',
    'rc.auth.session',
    'rc.auth.service',
    'rc.auth.listeners',
    'rc.auth.interceptor'
]);