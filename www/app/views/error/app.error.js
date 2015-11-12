'use strict';

/* 
 * Error Pages Module
 * 
 * Include controllers and other modules required on the error pages.
 */

angular.module('app.error', [
    'app.error.layout',
    'app.error.notAuthorized',
    'app.error.notFound'
]);