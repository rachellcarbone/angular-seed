'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.signup', [])
        .controller('AuthSignupCtrl', ['$scope', '$state', '$log', 'AuthService', 
        function ($scope, $log, $state, AuthService) {
        
        $scope.$state = $state;
        $scope.form = {};

        $scope.newUser = {
            'nameFirst' : '',
            'nameLast' : '',
            'email' : '',
            'password' : '',
            'passwordB' : '',
            'referer' : ''
        };

        $scope.signup = function() {
            $scope.$broadcast('show-errors-check-validity');

            if($scope.form.login.$valid) {
                AuthService.signup($scope.newUser).then(function(results) {
                    $log.debug(results);
                }, function(error) {
                    $log.debug(error);
                });
            } else {
                $scope.form.signup.$setDirty();
                $log.debug("Nope");
            }
        };

        $scope.FacebookSignup = function() {
            AuthService.facebookSignup().then(function (resp) {
                $log.debug(resp);
                $scope.newUser = resp;
            }, function (err) {
                $log.debug(err);
            });
        };
        
    }]);