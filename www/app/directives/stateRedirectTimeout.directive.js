'use strict';

/* 
 * State Redirect Timeout
 * 
 * @author  Rachel L Carbone
 * 
 * @param toState string the name of a valid state
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

angular.module('rachels.stateRedirectTimeout', [])
    .directive('stateRedirectTimeout', function($http) {
        
    return {
        restrict: 'A',          // Must be a attributeon a html tag
        template: '<strong class="redirect-timeout-ticker">{{timer}}</strong>',
        scope: {
            toState: '@',           // Valid application state
            timeoutSeconds: '@'     // Must be int, defaults to 10
        },
        controller: ['$scope', '$timeout', '$state', function($scope, $timeout, $state) {
                
            /*
             * Set Timeout Until State Redirect
             * 
             * Create the timeout ticker that will update the timer in the template
             * each second until 0 at which time the state will be redirected.
             */
            $scope.setTimeout = function(toState, timeInSeconds) {
                // Helper function to validate an integer
                var isInteger = function(x) {
                    return (parseInt(x) && parseInt(x) % 1 === 0);
                };

                // Validate timeInSeconds as a valid integer, or default 10
                $scope.timer = (timeInSeconds && isInteger(timeInSeconds)) ? parseInt(timeInSeconds) : 10;                
                
                // Start ticking automatically
                ($scope.tick = function() {
                    // Timeout is triggered every second
                    $timeout(function () {
                        // Subtract a second
                        $scope.timer--;
                        // If we still have time left
                        if($scope.timer > 0) {
                            // Tick again in one second
                            $scope.tick();
                        } else {
                            // When the timer hits zero, redirect
                            $state.go(toState);
                        }
                    }, 1000);
                })();
            };
        }],
        link: function(scope, element, attrs) {
            // Trigger the timeout to begin
            scope.setTimeout(attrs.toState, attrs.timeoutSeconds);
        }
    };
    
});