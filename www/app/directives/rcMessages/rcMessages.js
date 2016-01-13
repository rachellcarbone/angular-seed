'use strict';

/* 
 * rcMessages
 * 
 * @author  Rachel L Carbone
 * 
 */

var scripts = document.getElementsByTagName("script")
var currentScriptPath = scripts[scripts.length-1].src;

angular.module('rcMessages', [])
    .directive('rcMessages', function(DIRECTIVES_URL) {
        
    return {
        restrict: 'A',          // Must be a attributeon a html tag
        templateUrl: DIRECTIVES_URL + 'rcMessages/rcMessages.html',
        controller: ['$scope', function($scope) {
                console.log(DIRECTIVES_URL + 'rcMessages/rcMessages.html');
        }],
        link: function(scope, element, attrs) {
        }
    };
    
});