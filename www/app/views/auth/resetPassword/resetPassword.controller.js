'use strict';

/* 
 * Reset Password Page
 * 
 * Controller for the Reset Password Page.
 */

angular.module('app.auth.resetPassword', [])
       .controller('ResetPasswordCtrl', ['$scope', '$state', '$log', '$window', '$timeout', 'AuthService', 'AlertConfirmService', '$stateParams',
        function ($scope, $state, $log, $window, $timeout, AuthService, AlertConfirmService, $stateParams) {

            $scope.$state = $state;
            $scope.form = {};
            $scope.signupAlerts = {};

            $scope.showPasswordRules = false;
            $scope.showPasswordMissmatch = false;

            if ($stateParams.usertoken) {
                $scope.usertoken = {
                    'usertoken': $stateParams.usertoken
                };


                AuthService.forgotemailaddress($scope.usertoken).then(function (results) {
                    $scope.newUser = {
                        'email': results.user.email,
                        'password': '',
                        'passwordB': ''
                    };
                }, function (error) {
                    $scope.signupAlerts.error(error);

                    $timeout(function () {
                        $state.go('app.auth.login');
                    }, 5000);

                });
            }

            $scope.SubmitResetPasswordForm = function () {
                if (!$scope.form.resetPassword.$valid) {
                    $scope.form.resetPassword.$setDirty();
                    $scope.signupAlerts.error('Please fill in all Password fields.');
                } else if ($scope.newUser.password !== $scope.newUser.passwordB) {
                    $scope.form.resetPassword.$setDirty();
                    $scope.signupAlerts.error('Passwords do not match.');
                } else {
                    //API Call to save the Reset Password
                    AuthService.resetpassword($scope.newUser).then(function (results) {
                        $scope.signupAlerts.error(results.msg);
                    }, function (error) {
                        $scope.signupAlerts.error(error);
                    });


                }
            };

            var passwordValidator = /^(?=.*\d)(?=.*[A-Za-z])[A-Za-z0-9_!@#$%^&*+=-]{8,100}$/;
            $scope.onChangeValidatePassword = function () {
                $scope.showPasswordRules = (!passwordValidator.test($scope.newUser.password));
                $scope.onChangeValidateConfirmPassword();
            };

            $scope.onChangeValidateConfirmPassword = function () {
                $scope.showPasswordMissmatch = ($scope.newUser.password !== $scope.newUser.passwordB);
            };
        }]);