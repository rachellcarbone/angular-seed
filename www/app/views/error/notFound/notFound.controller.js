'use strict';

/* 
 * Page: Error 404 Page Not Found
 * 
 * Used to display a 404 page not found error page.
 */

angular.module('app.error.notFound', [])
        .controller('ErrorNotFoundCtrl', ['$scope', '$state', '$timeout',
    function($scope, $state, $timeout) {
        $scope.timer = 5;
        
        $timeout(function () {
            $scope.goToDashboard();
        }, 6000);
        
        ($scope.tick = function() {
            $timeout(function () {
                $scope.timer--;
                if($scope.timer > 0) {
                    $scope.tick();
                }
            }, 1000);
        })();
        
        $scope.goToDashboard = function() {
            $state.go('member.dashboard');
        };
        
        $scope.isActiveState = function(state) {
            return (state === $state.current.name);
        };
    }]);