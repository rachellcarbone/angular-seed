'use strict';

/* 
 * rcMessages
 * 
 * @author  Rachel L Carbone
 * 
 * @param formInput string the name of a valid state
 * @param timeoutSeconds  int number of seconds to wait before changing state
 * 
 * Insert an inline <strong>#</strong> tag contianing the number of seconds
 * remaining until a state redirect.
 * 
 * <span 
 *      data-state-redirect-timeout 
 *      data-to-state="app.state" 
 *      data-timeout-seconds="10"></span>
 */

angular.module('rcMessages', [])
    .directive('rcMessages', function(DIRECTIVES_URL) {
        
    return {
        require: '^form',
        restrict: 'A',          // Must be a attributeon a html tag
        templateUrl: DIRECTIVES_URL + 'rcMessages/rcMessages.html',
        scope: true,
        link: function($scope, element, attrs, ctrl) {
            $scope.formInput = ctrl[attrs.rcMessages];
            console.log('formInput', $scope.formInput);
        }
    };
    
});