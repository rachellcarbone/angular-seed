'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editRole', [])
    .controller('EditRoleModalCtrl', ['$scope', '$uibModalInstance', '$log', 'AlertConfirmService', 'editing', 'ApiRoutesRoles',
    function($scope, $uibModalInstance, $log, AlertConfirmService, editing, ApiRoutesRoles) {
        
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Is the modal in edit mode? Shows / Hides form */
    $scope.editMode = (!angular.isDefined(editing.id));
    $scope.newMode = (!angular.isDefined(editing.id));
    
    /* Save for resetting purposes */
    $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : {};
    
    /* Item to display and edit */
    $scope.editing = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesRoles.newSystemVariable($scope.variable).then(
            function (result) {
                $uibModalInstance.close(result);
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to change this variable? It may effect system settings.', 'System Wide Setting')
            .result.then(function () {
                ApiRoutesRoles.saveSystemVariable($scope.variable).then(
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
        AlertConfirmService.confirm('Are you sure you want to delete this variable? It may effect system settings.', 'Delete Warning')
            .result.then(function () {
                ApiRoutesRoles.deleteSystemVariable($scope.variable.id).then(
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
        if($scope.newMode || !$scope.editMode) {
            $uibModalInstance.dismiss(false);
        } else {
            $scope.editMode = false;
        }
    };
    
    /* Click event for the Edit button*/
    $scope.buttonEdit = function() {
        $scope.editMode = true;
    };
    
}]);