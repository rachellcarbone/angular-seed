'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth', [
    'rcAuth.constants',
    'rcAuth.session',
    'rcAuth.service',
    'rcAuth.listeners',
    'rcAuth.interceptor'
]);