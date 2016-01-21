'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.login', [])
        .controller('AuthLoginCtrl', ['$scope', '$state', '$log', 'AuthService', 
        function ($scope, $state, $log, AuthService) {
        
        $scope.$state = $state;
        $scope.form = {};

        $scope.credentials = {
            'email' : 'rachellcarbone@gmail.com',
            'password' : 'password1',
            'remember' : false
        };

        $scope.login = function() {
            $scope.$broadcast('show-errors-check-validity');
  
            if($scope.form.login.$valid) {
                AuthService.login($scope.credentials).then(function(results) {
                    $log.debug(results);
                }, function(error) {
                    $log.debug(error);
                });
            } else {
                $scope.form.login.$setDirty();
                console.log("Nope");
            }
        };
        
    }]);