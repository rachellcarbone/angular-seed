'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editRole', [])
    .controller('EditRoleModalCtrl', ['$scope', '$uibModalInstance', '$filter', 'AlertConfirmService', 'editing', 'ApiRoutesRoles', 'groupList', 'fieldList',
    function($scope, $uibModalInstance, $filter, AlertConfirmService, editing, ApiRoutesRoles, groupList, fieldList) {
    $scope.groupList = groupList;
    $scope.fieldList = fieldList;
        
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Modal Mode */    
    $scope.setMode = function(type) {
        $scope.viewMode = false;
        $scope.newMode = false;
        $scope.editMode = false;
        $scope.manageFieldsMode = false;
        $scope.manageGroupsMode = false;
        
        switch(type) {
            case 'new':
                $scope.newMode = true;
                break;
            case 'edit':
                $scope.editMode = true;
                break;
            case 'fields':
                $scope.manageFieldsMode = true;
                break;
            case 'groups':
                $scope.manageGroupsMode = true;
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
        } else if($scope.manageFieldsMode) {
            return 'fields';
        } else if($scope.manageGroupsMode) {
            return 'groups';
        } else {
            return 'view';
        }
    };
    
    
    /* Save for resetting purposes */
    $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : {
        'role' : '',
        'desc' : ''
    };
    
    /* Item to display and edit */
    $scope.editing = angular.copy($scope.saved);
    
    /* Click event for the Add / New button */
    $scope.buttonNew = function() {
        ApiRoutesRoles.newRole($scope.editing).then(
            function (result) {
                $uibModalInstance.close(result);
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    /* Click event for the Save button */
    $scope.buttonSave = function() {
        AlertConfirmService.confirm('Are you sure you want to change this  group role? It may effect system settings.', 'System Wide Setting')
            .result.then(function () {
                ApiRoutesRoles.saveRole($scope.editing).then(
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
        AlertConfirmService.confirm('Are you sure you want to delete this  group role? It may effect system settings.', 'Delete Warning')
            .result.then(function () {
                ApiRoutesRoles.deleteRole($scope.editing.id).then(
                    function (result) {
                        $uibModalInstance.close(result);
                    }, function (error) {
                        $scope.alertProxy.error(error);
                    });
            }, function (declined) {
                $scope.alertProxy.info('No changes were saved.');
            });
    };
    
    /* Click event for the Manage Groups button */
    $scope.buttonManageGroups = function() {
        $scope.setMode('groups');
    };
    
    /* Click event for the Manage Fields button */
    $scope.buttonManageFields = function() {
        $scope.setMode('fields');
    };
        
    /* Click event for the Cancel button */
    $scope.buttonCancel = function() {
        var mode = $scope.getMode();
        
        switch(mode) {
            case 'edit':
            case 'fields':
            case 'groups':
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
    
    /* Return BOOL if Group is assigned to this role */
    $scope.isGroupAssignedToRole = function(groupId) {
        var found = $filter('filter')($scope.saved.groups, {id: groupId}, true);
        return (angular.isDefined(found[0]));
    };
    
    /* Return BOOL if Field is assigned to this role */
    $scope.isFieldAssignedToRole = function(fieldId) {
        var found = $filter('filter')($scope.saved.elements, {id: fieldId}, true);
        return (angular.isDefined(found[0]));
    };
    
    $scope.buttonRemoveGroupFromRole = function(groupId) {
        ApiRoutesRoles.unassignRoleFromGroup({ 'roleId':$scope.saved.id, 'groupId': groupId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                angular.forEach($scope.saved.groups, function (obj, index) {
                    if (obj.id == groupId) {
                        $scope.saved.groups.splice(index, 1);
                        return;
                    }
                });
        
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    $scope.buttonAddGroupToRole = function(groupId) {
        ApiRoutesRoles.assignRoleToGroup({ 'roleId':$scope.saved.id, 'groupId': groupId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                var found = $filter('filter')($scope.groupList, {id: groupId}, true);
                if (angular.isDefined(found[0])) {
                    $scope.saved.groups.push(found[0]);
                }
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    $scope.buttonRemoveFieldFromRole = function(fieldId) {
        ApiRoutesRoles.unassignRoleFromField({ 'roleId':$scope.saved.id, 'fieldId': fieldId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                angular.forEach($scope.saved.elements, function (obj, index) {
                    if (obj.id == fieldId) {
                        $scope.saved.elements.splice(index, 1);
                        return;
                    }
                });
        
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
    $scope.buttonAddFieldToRole = function(fieldId) {
        ApiRoutesRoles.assignRoleToField({ 'roleId':$scope.saved.id, 'fieldId': fieldId }).then(
            function (result) {
                $scope.alertProxy.success(result.msg);
                
                var found = $filter('filter')($scope.fieldList, {id: fieldId}, true);
                if (angular.isDefined(found[0])) {
                    $scope.saved.elements.push(found[0]);
                }
            }, function (error) {
                $scope.alertProxy.error(error);
            });
    };
    
}]);