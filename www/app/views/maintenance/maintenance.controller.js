'use strict';

/* 
 * Maintenance Page
 * 
 * This is the maintenance page that visitors will be redirected to when 
 * "maintenance mode" is turned on. It doesnt use any other views and is 
 * loaded from the index.html.
 */

angular.module('app.maintenance', ['ui.bootstrap'])
    .controller('MaintenanceCtrl', ['$scope', '$timeout', function($scope, $timeout) {
        
        $scope.currentYear = moment().year();

        $scope.progressValue = 5;
        
        ($scope.tick = function () {
            
            $timeout(function () {
                $scope.progressValue = ($scope.progressValue >= 95) ? 5 : $scope.progressValue + 5;
                $scope.tick();
            }, 3000);
            
        })();
        
    }]);