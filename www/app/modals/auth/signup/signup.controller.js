'use strict';

/* 
 * Modal Signup Form
 * 
 * Controller for the modal version of the signup form.
 */

angular.module('app.modal.signup', [])
        .controller('SignupModalCtrl', ['$scope', '$uibModalInstance', '$state', '$log', 'AuthService', 'currentTeam', 'teamsList',
        function ($scope, $uibModalInstance, $state, $log, AuthService, currentTeam, teamsList) {
        
        $scope.$state = $state;
        $scope.form = {};
        $scope.facebookAlerts = {};
        $scope.signupAlerts = {};
        
        $scope.currentTeam = currentTeam;
        $scope.teamsList = teamsList;

        $scope.joinTeam = {};
        if(currentTeam) {
            for(var t = 0; t < teamsList.length; t++) {
                if(parseInt(teamsList[t].id) === parseInt(currentTeam.id)) {
                    $scope.joinTeam = teamsList[t];
                    t = teamsList.length;
                    break;
                }
            }
        }
        
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
                $scope.signupAlerts.error('Please fill in the Email, and Password fields.');
            } else if($scope.newUser.password !== $scope.newUser.passwordB) {
                $scope.form.signup.$setDirty();
                $scope.signupAlerts.error('Passwords do not match.');
            } else {
                if(angular.isDefined($scope.joinTeam.value.id)) {
                    $scope.newUser.teamId = $scope.joinTeam.value.id;
                }
                
                AuthService.signup($scope.newUser, true).then(function (results) {
                    $log.debug(results);
                    $uibModalInstance.close("Signup successful! A new user has been created..");
                }, function (error) {
                    $scope.signupAlerts.error(error);
                });
            }
        };

        $scope.facebookSignup = function() {
            AuthService.facebookSignup().then(function (resp) {
                $log.debug(resp);
                $scope.newUser = resp;

                $uibModalInstance.close("Facebook signup Successful!");
            }, function (err) {
                $scope.facebookAlerts.error(err);
            });
        };
        
        var passwordValidator = /^(?=.*\d)(?=.*[A-Za-z])[A-Za-z0-9_!@#$%^&*+=-]{8,55}$/;
        $scope.onChangeValidatePassword = function() {
            $scope.showPasswordRules = (!passwordValidator.test($scope.newUser.password));
            $scope.onChangeValidateConfirmPassword();
        };
        
        $scope.onChangeValidateConfirmPassword = function() {
            $scope.showPasswordMissmatch = ($scope.newUser.password !== $scope.newUser.passwordB);
        };

        /* Click event for the Cancel button */
        $scope.buttonCancel = function() {
            $uibModalInstance.dismiss(false);
        };
    }]);