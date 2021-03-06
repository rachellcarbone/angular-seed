'use strict';

/* 
 * Login Page
 * 
 * Controller for the login page.
 */

angular.module('app.auth.signup', [])
        .controller('AuthSignupCtrl', ['$scope', '$state', '$log', '$window', '$timeout', 'AuthService', 'AlertConfirmService',
        function ($scope, $state, $log, $window, $timeout, AuthService, AlertConfirmService) {
        
        $scope.$state = $state;
        $scope.form = {};
        $scope.facebookAlerts = {};
        $scope.signupAlerts = {};
        
        $scope.showPasswordRules = false;
        $scope.showPasswordMissmatch = false;

        $scope.newUser = {
            'userGroup' : 'player',
            'nameFirst' : '',
            'nameLast' : '',
            'email' : '',
            'phone' : '',
            'password' : '',
            'passwordB' : '',
            'referrer' : '',
            'acceptTerms' : false
        };

        $scope.signup = function() {
            if(!$scope.form.signup.$valid) {
                $scope.form.signup.$setDirty();
                $scope.signupAlerts.error('Please agree to our terms of service and fill in the Email, and Password fields.');
            } else if($scope.newUser.password !== $scope.newUser.passwordB) {
                $scope.form.signup.$setDirty();
                $scope.signupAlerts.error('Passwords do not match.');
            } else if(!$scope.newUser.acceptTerms) {
                AlertConfirmService.confirm('Do you agree to our <a href="http://www.angularseed.com/terms-and-conditions/" target="_blank">Terms of Service</a>?', 'Terms of Service Agreement').result.then(function (resp) {
                    $scope.newUser.acceptTerms = true;
                    AuthService.signup($scope.newUser).then(function (results) {
                        $log.debug(results);

                        $scope.signupAlerts.success("Signup successful!  Please wait for confirmation page", 'signup');

                        $timeout(function () {
                            $window.top.location.href = 'http://www.angularseed.com/registration-thank-you-page/';
                        }, 1000);
                    }, function (error) {
                        $scope.signupAlerts.error(error);
                    });
                }, function (err) {
                    $scope.facebookAlerts.error('Please accept the Terms of Service to signup.');
                });
            } else {
                AuthService.signup($scope.newUser).then(function (results) {
                    $log.debug(results);
                    
                    $scope.signupAlerts.success("Signup successful!  Please wait for confirmation page", 'signup');

                    $timeout(function () {
                        $window.top.location.href = 'http://www.angularseed.com/registration-thank-you-page/';
                    }, 1000);
                }, function (error) {
                    $scope.signupAlerts.error(error);
                });
            }
        };

        $scope.facebookSignup = function() {
            if(!$scope.newUser.acceptTerms) {
                AlertConfirmService.confirm('Do you agree to our <a href="http://www.angularseed.com/terms-and-conditions/" target="_blank">Terms of Service</a>?', 'Terms of Service Agreement').result.then(function (resp) {
                    $scope.newUser.acceptTerms = true;
                    AuthService.facebookSignup().then(function (resp) {
                        $log.debug(resp);
                        $scope.newUser = resp;
                        
                        $scope.facebookAlerts.success("Facebook signup Successful!  Please wait for confirmation page", 'terms');

                        $timeout(function () {
                            $window.top.location.href = 'http://www.angularseed.com/registration-thank-you-page/';
                        }, 1000);
                    }, function (err) {
                        $scope.facebookAlerts.error(err);
                    });
                }, function (err) {
                    $scope.facebookAlerts.error('Please accept the Terms of Service to signup.');
                });
            } else {
                AuthService.facebookSignup().then(function (resp) {
                    $log.debug(resp);
                    $scope.newUser = resp;

                    $scope.facebookAlerts.success("Facebook signup Successful!  Please wait for confirmation page", 'terms');

                    $timeout(function () {
                        $window.top.location.href = 'http://www.angularseed.com/registration-thank-you-page/';
                    }, 3000);
                }, function (err) {
                    $scope.facebookAlerts.error(err);
                });
            }
        };
        
        var passwordValidator = /^(?=.*\d)(?=.*[A-Za-z])[A-Za-z0-9_!@#$%^&*+=-]{8,55}$/;
        $scope.onChangeValidatePassword = function() {
            $scope.showPasswordRules = (!passwordValidator.test($scope.newUser.password));
            $scope.onChangeValidateConfirmPassword();
        };
        
        $scope.onChangeValidateConfirmPassword = function() {
            $scope.showPasswordMissmatch = ($scope.newUser.password !== $scope.newUser.passwordB);
        };
    }]);