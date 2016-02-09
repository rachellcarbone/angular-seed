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
    $scope.editMode = (!angular.isDefined(editing.id));
    $scope.newMode = (!angular.isDefined(editing.id));
    
    /* Save for resetting purposes */
    $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : 
    { 
        'name' : '',
        'value' : '',
        'disabled' : '0',
        'locked' : '0',
        'indestructible' : '0'
    };
    
    /* Item to display and edit */
    $scope.editing = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesSystemVariables.newSystemVariable($scope.editing).then(
            function (result) {
                $uibModalInstance.close(result);
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    $scope.checkboxLockedWarning = function($event, IsAccepted) {
        if ($event !== undefined) {

            var checkbox = $event.target;

            if (checkbox.checked) {

                AlertConfirmService.confirm('Are you sure you want to lock this variable? I can only be changed by admin with the correct permissions.', 'Restricting Access')
                    .result.then(function () {
                    }, function (declined) {
                        $scope.editing.locked = '0';
                    });

            }

        }

    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to change this variable? It may effect system settings.', 'System Wide Setting')
            .result.then(function () {
                ApiRoutesSystemVariables.saveSystemVariable($scope.editing).then(
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
                ApiRoutesSystemVariables.deleteSystemVariable($scope.editing.id).then(
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