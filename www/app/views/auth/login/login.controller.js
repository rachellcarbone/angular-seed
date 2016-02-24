'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.login', [])
    .controller('AuthLoginCtrl', ['$scope', '$state', 'AuthService',
    function ($scope, $state, AuthService) {
        
    /* Used to restrict alert bars */
    $scope.alertProxy = {};

    $scope.results = [];

    $scope.$state = $state;
    $scope.form = {};

    $scope.credentials = {
        'email' : 'rachellcarbone@gmail.com',
        'password' : 'password1',
        'remember' : false
    };

    $scope.buttonLogin = function() {
        $scope.$broadcast('show-errors-check-validity');

        if($scope.form.login.$valid) {
            AuthService.login($scope.credentials).then(function(results) {
            }, function(error) {
                $scope.alertProxy.error(error);
            });
        } else {
            $scope.form.login.$setDirty();
            $scope.alertProxy.error('Please fill in both fields.');
        }
    };

    $scope.buttonFacebookLogin = function() {
        AuthService.facebookLogin($scope.credentials.remember).then(function (results) {
        }, function (error) {
            $scope.alertProxy.error(error);
        });
    };

}]);