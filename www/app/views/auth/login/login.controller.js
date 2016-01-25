'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.login', [])
    .controller('AuthLoginCtrl', ['$rootScope', '$scope', '$state', '$log', 'AuthService',
    function ($rootScope, $scope, $state, $log, AuthService) {

    $scope.results = [];
    $rootScope.$on("fb.init", function () {
        console.log("SDK Ready");
    });

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

    $scope.FacebookLogin = function() {
        AuthService.facebookLogin().then(function (resp) {
            console.log("Auth response");
            console.log(resp);

        }, function (err) {
            console.log(err);
        });
    };

}]);