'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editSystemVariable', [])
    .controller('EditSystemVariableModalCtrl', ['$scope', '$uibModalInstance', '$log', 'AlertConfirmService', 'editing', 'ApiRoutesFields',
    function($scope, $uibModalInstance, $log, AlertConfirmService, editing, ApiRoutesFields) {
        
    /* Used to restrict alert bars */
    $scope.restrictTo = "edit-field-elements-modal";
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Is the modal in edit mode? Shows / Hides form */
    $scope.editMode = Boolean(editing.field);
    
    /* Save the field for resetting purposes */
    $scope.savedField = (editing.field) ? angular.copy(editing.field) : {};
    
    /* Field to display and edit */
    $scope.field = (editing.field) ? angular.copy(editing.field) : {};
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesFields.addField($scope.field).then(
            function (result) {
                $scope.editMode = false;
            }, function (error) {
                $log.info(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to manually override this field?')
            .result.then(function () {
                ApiRoutesFields.saveField($scope.field).then(
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
        AlertConfirmService.confirm('Are you sure you want to disable this field? They will no longer be able to log in.')
            .result.then(function () {
                ApiRoutesFields.deleteField($scope.field.id).then(
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