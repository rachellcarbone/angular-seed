'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.login', [])
        .controller('AuthLoginCtrl', ['$scope', '$state', function ($scope, $state) {
        
        $scope.$state = $state;
        
    }]);