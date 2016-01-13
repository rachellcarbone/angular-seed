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

        $scope.credentials = {
            'email' : '',
            'password' : '',
            'remember' : true
        };

        $scope.login = function() {
            
            AuthService.login($scope.credentials).then(function(results) {
                $log.debug(results);
            }, function(error) {
                $log.debug(error);
            });
            
        };
        
    }]);