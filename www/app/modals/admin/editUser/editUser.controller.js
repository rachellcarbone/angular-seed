'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editUser', [])
    .controller('EditUserModalCtrl', ['$scope', '$uibModalInstance', '$log', 'AlertConfirmService', 'editing', 'ApiRoutesUsers',
    function($scope, $uibModalInstance, $log, AlertConfirmService, editing, ApiRoutesUsers) {
        
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Is the modal in edit mode? Shows / Hides form */
    $scope.editMode = (!angular.isDefined(editing.id));
    $scope.newMode = (!angular.isDefined(editing.id));
    
    /* Save for resetting purposes */
    $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : {
        'nameFirst' : '',
        'nameLast' : '',
        'email' : '',
        'disabled' : 'false'
    };
    
    /* Item to display and edit */
    $scope.editing = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesUsers.addUser($scope.editing).then(
            function (result) {
                $scope.editMode = false;
            }, function (error) {
                $log.info(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to manually override this user?')
            .result.then(function () {
                ApiRoutesUsers.saveUser($scope.editing).then(
                    function (result) {
                        $scope.editMode = false;
                    }, function (error) {
                        $log.info(error);
                    });
            }, function () {
                $log.info(error);
            });
    };
    
    /* Click event for the Delete button */
    $scope.buttonDelete = function() {
        AlertConfirmService.confirm('Are you sure you want to disable this user? They will no longer be able to log in.')
            .result.then(function () {
                ApiRoutesUsers.deleteUser($scope.editing.id).then(
                    function (result) {
                        $scope.editMode = false;
                    }, function (error) {
                        $log.info(error);
                    });
            });
    };
        
    /* Click event for the Cancel button */
    $scope.buttonCancel = function() {
        $uibModalInstance.dismiss(false);
    };
}]);
