'use strict';

/* 
 * Player Invite Signup Page
 * 
 * Controller for the sinup page accessed by a player invite.
 */

angular.module('app.auth.signupInvite', [])
        .controller('AuthSignupInviteCtrl', ['$scope', '$state', '$log', '$window', '$timeout', '$stateParams', 'AuthService', 'AlertConfirmService', 'InvitationData',
        function ($scope, $state, $log, $window, $timeout, $stateParams, AuthService, AlertConfirmService, InvitationData) {
        
        $scope.$state = $state;
        $scope.form = {};
        $scope.facebookAlerts = {};
        $scope.signupAlerts = {};
        
        $scope.showPasswordRules = false;
        $scope.showPasswordMissmatch = false;

        $scope.newUser = {
            'userGroup' : 'player',
            'nameFirst' : InvitationData.nameFirst || '',
            'nameLast' : InvitationData.nameFirst || '',
            'email' : InvitationData.email,
            'phone' : InvitationData.phone || '',
            'password' : '',
            'passwordB' : '',
            'referrer' : "Invited by user - " + InvitationData.invitedBy,
            'acceptTerms' : false
        };

        $scope.signup = function() {
            $scope.newUser.token = $stateParams.token;
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
            var userToken = { 'token' : $stateParams.token };
            if(!$scope.newUser.acceptTerms) {
                AlertConfirmService.confirm('Do you agree to our <a href="http://www.angularseed.com/terms-and-conditions/" target="_blank">Terms of Service</a>?', 'Terms of Service Agreement').result.then(function (resp) {
                    $scope.newUser.acceptTerms = true;
                    AuthService.facebookSignup(userToken).then(function (resp) {
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