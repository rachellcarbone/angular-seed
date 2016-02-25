'use strict';

/* 
 * Member Profile Page
 * 
 * Controller for the member profile page used to view and edit a users profile.
 */

angular.module('app.member.profile', [])
    .controller('MemberProfileCtrl', ['$scope', '$log', 'UserSession', 'ApiRoutesUsers', 
    function($scope, $log, UserSession, ApiRoutesUsers) {
        
        $scope.editGeneralMode = false;
        $scope.editPasswordMode = false;
        $scope.showPasswordRules = false;
        $scope.showPasswordMissmatch = false;
                    
        /* Form Alert Proxy */
        $scope.generalFormAlerts = {};
        $scope.passwordFormAlerts = {};

        /* Holds the add / edit form on the modal */
        $scope.form = {};
        $scope.changePassword = {
            'current' : '',
            'new' : '',
            'confirm' : ''
        };

        /* User to display and edit */
        $scope.user = UserSession.get();
        $scope.editingUser = angular.copy($scope.user);

        $scope.buttonShowGeneralEdit = function() {
            $scope.editGeneralMode = true;
        };
        /* Click event for the Save button */
        $scope.buttonSave = function() {
            if(!$scope.form.general.$valid) {
                $scope.form.general.$setDirty();
                $scope.generalFormAlerts.error('Please fill in all form fields.');
            } else {
                ApiRoutesUsers.saveUser($scope.editingUser).then(
                    function (result) {
                        $scope.user = UserSession.updateUser(result.user);
                        $scope.editGeneralMode = false;
                    }, function (error) {
                        $log.info(error);
                    });
            }
        };

        $scope.buttonShowChangePassword = function() {
            $scope.editPasswordMode = true;
        };

        /* Click event for the Add / New button */
        $scope.buttonChangePassword = function() {
            if(!$scope.form.password.$valid) {
                $scope.form.password.$setDirty();
                $scope.passwordFormAlerts.error('Please fill in all form fields.');
            } else if($scope.changePassword.new !== $scope.changePassword.confirm) {
                $scope.form.password.$setDirty();
                $scope.passwordFormAlerts.error('Passwords must match.');
            } else {
                $scope.changePassword.userId = $scope.user.id;
                ApiRoutesUsers.changePassword($scope.changePassword).then(
                    function (result) {
                        $scope.editPasswordMode = false;
                    }, function (error) {
                        $log.info(error);
                    });
            }
        };
        
        var passwordValidator = /^(?=.*\d)(?=.*[A-Za-z])[A-Za-z0-9_!@#$%^&*+=-]{8,100}$/;
        $scope.onChangeValidatePassword = function() {
            $scope.showPasswordRules = (!passwordValidator.test($scope.changePassword.new));
        };
        
        $scope.onChangeValidateConfirmPassword = function() {
            $scope.showPasswordMissmatch = ($scope.changePassword.new !== $scope.changePassword.confirm);
        };
        
        $scope.buttonCancel = function() {
            $scope.editGeneralMode = false;
            $scope.editPasswordMode = false;
            $scope.showPasswordRules = false;
            $scope.showPasswordMissmatch = false;
            $scope.changePassword = {
                'current' : '',
                'new' : '',
                'confirm' : ''
            };
            $scope.editingUser = angular.copy($scope.user);
        };
    }]);