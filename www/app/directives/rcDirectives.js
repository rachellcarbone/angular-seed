'use strict';

/* 
 * Rachels Directives
 * 
 * A parent module for my custom directives.
 */

angular.module('rcDirectives', [
    'rc.bootstrapAlerts',
    'rc.placeholdBrokenImg',
    'rc.stateRedirectTimeout',
    'rcMessages'
])
.constant('DIRECTIVES_URL', (function () {
    var scripts = document.getElementsByTagName("script");
    var scriptPath = scripts[scripts.length - 1].src;
    return scriptPath.substring(0, scriptPath.lastIndexOf('/') + 1);
})())
.directive('stringToNumber', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$parsers.push(function (value) {
                return '' + value;
            });
            ngModel.$formatters.push(function (value) {
                return parseFloat(value, 10);
            });
        }
    };
});