'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rachels.auth', [
    'rachels.auth.constants',
    'rachels.auth.session',
    'rachels.auth.service',
    'rachels.auth.listeners',
    'rachels.auth.interceptor'
]);