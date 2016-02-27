'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.signup', [])
        .controller('AuthSignupCtrl', ['$scope', '$state', '$log', 'AuthService', 'AlertConfirmService',
        function ($scope, $state, $log, AuthService, AlertConfirmService) {
        
        $scope.$state = $state;
        $scope.form = {};
        $scope.formAlerts = {};
        
        $scope.showPasswordRules = false;
        $scope.showPasswordMissmatch = false;

        $scope.newUser = {
            'nameFirst' : '',
            'nameLast' : '',
            'email' : '',
            'password' : '',
            'passwordB' : '',
            'referer' : '',
            'acceptTerms' : false
        };

        $scope.signup = function() {
            if(!$scope.form.signup.$valid) {
                $scope.form.signup.$setDirty();
                $scope.formAlerts.error('Please fill in all form fields.');
            } else if($scope.newUser.password !== $scope.newUser.passwordB) {
                $scope.form.signup.$setDirty();
                $scope.formAlerts.error('Passwords must match.');
            } else {
                AuthService.signup($scope.newUser).then(function (results) {
                    $log.debug(results);
                }, function (error) {
                    $log.debug(error);
                });
            }
        };

        $scope.facebookSignup = function() {
            if(!$scope.newUser.acceptTerms) {
                AlertConfirmService.confirm('Do you agree to our <a ui-sref="app.public.terms" target="_blank">Terms of Use</a>?', 'Terms of Use Agreement').result.then(function (resp) {
                    $scope.newUser.acceptTerms = true;
                    AuthService.facebookSignup().then(function (resp) {
                        $log.debug(resp);
                        $scope.newUser = resp;
                    }, function (err) {
                        $scope.formAlerts.error(err);
                    });
                }, function (err) {
                    $scope.formAlerts.error('Please accept the Terms of Use to signup.');
                });
            } else {
                AuthService.facebookSignup().then(function (resp) {
                    $log.debug(resp);
                    $scope.newUser = resp;
                }, function (err) {
                    $scope.formAlerts.error(err);
                });
            }
        };
        
        var passwordValidator = /^(?=.*\d)(?=.*[A-Za-z])[A-Za-z0-9_!@#$%^&*+=-]{8,100}$/;
        $scope.onChangeValidatePassword = function() {
            $scope.showPasswordRules = (!passwordValidator.test($scope.newUser.password));
            $scope.onChangeValidateConfirmPassword();
        };
        
        $scope.onChangeValidateConfirmPassword = function() {
            $scope.showPasswordMissmatch = ($scope.newUser.password !== $scope.newUser.passwordB);
        };
    }]);