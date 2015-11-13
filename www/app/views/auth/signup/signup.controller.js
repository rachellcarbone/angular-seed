'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.signup', [])
        .controller('AuthSignupCtrl', ['$scope', '$state', function ($scope, $state) {
        
        $scope.$state = $state;
        
    }]);