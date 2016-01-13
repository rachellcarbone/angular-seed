'use strict';

/* 
 * Rachels Directives
 * 
 * A parent module for my custom directives.
 */

angular.module('rcDirectives', [
    'rc.placeholdBrokenImg',
    'rc.stateRedirectTimeout'
])
.constant('DIRECTIVES_URL', (function () {
    var scripts = document.getElementsByTagName("script");
    var scriptPath = scripts[scripts.length - 1].src;
    return scriptPath.substring(0, scriptPath.lastIndexOf('/') + 1);
})());