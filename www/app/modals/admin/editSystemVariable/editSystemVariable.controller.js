'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editSystemVariable', [])
    .controller('EditSystemVariableModalCtrl', ['$scope', '$uibModalInstance', '$log', 'AlertConfirmService', 'editing', 'ApiRoutesSystemVariables',
    function($scope, $uibModalInstance, $log, AlertConfirmService, editing, ApiRoutesSystemVariables) {
        
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Is the modal in edit mode? Shows / Hides form */
    $scope.editMode = Boolean(editing.id);
    
    /* Save for resetting purposes */
    $scope.saved = (editing.id) ? angular.copy(editing) : {};
    
    /* Item to display and edit */
    $scope.variable = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesSystemVariables.newSystemVariable($scope.variable).then(
            function (result) {
                $uibModalInstance.close(result);
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to change this variable? It may effect system settings.')
            .result.then(function () {
                ApiRoutesSystemVariables.saveSystemVariable($scope.variable).then(
                    function (result) {
                        $uibModalInstance.close(result);
                    }, function (error) {
                        $scope.alertProxy.error(error);
                    });
            }, function (declined) {
                $scope.alertProxy.info('No changes were saved.');
            });
    };
    
    /* Click event for the Delete button */
    $scope.buttonDelete = function() {
        AlertConfirmService.confirm('Are you sure you want to delete this variable? It may effect system settings.')
            .result.then(function () {
                ApiRoutesSystemVariables.deleteSystemVariable($scope.variable.id).then(
                    function (result) {
                        $uibModalInstance.close(result);
                    }, function (error) {
                        $scope.alertProxy.error(error);
                    });
            }, function (declined) {
                $scope.alertProxy.info('No changes were saved.');
            });
    };
        
    /* Click event for the Cancel button */
    $scope.buttonCancel = function() {
        $uibModalInstance.dismiss(false);
    };
}]);