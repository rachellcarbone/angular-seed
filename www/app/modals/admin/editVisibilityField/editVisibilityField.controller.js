'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editVisibilityField', [])
    .controller('EditVisibilityFieldModalCtrl', ['$scope', '$uibModalInstance', '$filter', 'AlertConfirmService', 'editing', 'ApiRoutesSystemVisibility', 'roleList',
    function($scope, $uibModalInstance, $filter, AlertConfirmService, editing, ApiRoutesSystemVisibility, roleList) {
    $scope.roleList = roleList;
    
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Modal Mode */    
    $scope.setMode = function(type) {
        $scope.viewMode = false;
        $scope.newMode = false;
        $scope.editMode = false;
        $scope.manageRolesMode = false;
        
        switch(type) {
            case 'new':
                $scope.newMode = true;
                break;
            case 'edit':
                $scope.editMode = true;
                break;
            case 'roles':
                $scope.manageRolesMode = true;
                break;
            case 'view':
            default:
                $scope.viewMode = true;
                break;
        }
    };
    
    if(angular.isDefined(editing.id)) {
        $scope.setMode('view');
    } else {
        $scope.setMode('new');
    }
    
    $scope.getMode = function() {
        if($scope.newMode) {
            return 'new';
        } else if($scope.editMode) {
            return 'edit';
        } else if($scope.manageRolesMode) {
            return 'roles';
        } else {
            return 'view';
        }
    };
    
    
    /* Save for resetting purposes */
    $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : {
        'identifier' : '',
        'type' : 'element',
        'desc' : ''
    };
    
    /* Item to display and edit */
    $scope.editing = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesSystemVisibility.newVisibilityField($scope.editing).then(
            function (result) {
                $uibModalInstance.close(result);
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to change this visibility field? It may effect system settings.', 'System Wide Setting')
            .result.then(function () {
                ApiRoutesSystemVisibility.saveVisibilityField($scope.editing).then(
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
        AlertConfirmService.confirm('Are you sure you want to delete this visibility field? Any elements using this tag will no longer be controlled by auth visibility..', 'Delete Warning')
            .result.then(function () {
                ApiRoutesSystemVisibility.deleteVisibilityField($scope.editing.id).then(
                    function (result) {
                        $uibModalInstance.close(result);
                    }, function (error) {
                        $scope.alertProxy.error(error);
                    });
            }, function (declined) {
                $scope.alertProxy.info('No changes were saved.');
            });
    };
    
    /* Click event for the Manage Roles button */
    $scope.buttonManageRoles = function() {
        $scope.setMode('roles');
    };
        
    /* Click event for the Cancel button */
    $scope.buttonCancel = function() {
        var mode = $scope.getMode();
        
        switch(mode) {
            case 'edit':
            case 'roles':
                $scope.setMode('view');
                break;
            case 'new':
            case 'view':
            default:
                $uibModalInstance.dismiss(false);
                break;
        };
    };
    
    /* Click event for the Edit button*/
    $scope.buttonEdit = function() {
        $scope.setMode('edit');
    };
    
    /* Return BOOL if Role is assigned to this field */
    $scope.isRoleAssignedToField = function(roleId) {
        var found = $filter('filter')($scope.saved.roles, {id: roleId}, true);
        return (angular.isDefined(found[0]));
    };
    
    $scope.buttonRemoveRoleFromField = function(roleId) {
        ApiRoutesSystemVisibility.unassignRoleFromField({ 'fieldId':$scope.saved.id, 'roleId': roleId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                angular.forEach($scope.saved.roles, function (obj, index) {
                    if (obj.id == roleId) {
                        $scope.saved.roles.splice(index, 1);
                        return;
                    }
                });
        
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    $scope.buttonAddRoleToField = function(roleId) {
        ApiRoutesSystemVisibility.assignRoleToField({ 'fieldId':$scope.saved.id, 'roleId': roleId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                var found = $filter('filter')($scope.roleList, {id: roleId}, true);
                if (angular.isDefined(found[0])) {
                    $scope.saved.roles.push(found[0]);
                }
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
}]);