'use strict';

/* @author  Rachel Carbone */

var app = angular.module('editor.controllers', []);
app.controller('ErrorPagesCtrl', ['$rootScope', '$scope', '$state', '$timeout',
    function($rootScope, $scope, $state, $timeout) {
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
            $state.go('app.dashboard');
        };
        
        $scope.isActiveState = function(state) {
            return (state === $state.current.name);
        };
    }]);