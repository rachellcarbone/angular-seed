'use strict';

/* 
 * Service to Load UI Bootstrap Modals
 * 
 * Includes modal controllers and provides and api to launch the modal.
 * https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('ModalService', [
    'app.modal.assignElementRoles',
    'app.modal.assignGroupRoles',
    'app.modal.assignUserGroups',
    'app.modal.editSystemVariable',
    'app.modal.editGroup',
    'app.modal.editRole',
    'app.modal.editRole',
    'app.modal.editUser',
    'app.modal.editVisibilityField'
])
.factory('ModalService', ['$uibModal', function($uibModal) {
        
    var templatePath = 'app/modals/';
    
    var api = {};
    
    var defaultOptions = {
        size: 'md',
        backdrop: 'static'
    };
    
    api.openModal = function(apiOptions, passedOptions, passedResolve) {
        /* Get value of resolve */
        var apiResolve = apiOptions.resolve || {};
        var defaultResolve = defaultOptions.resolve || {};
        passedResolve = passedResolve || {};
        var combineResolve = angular.extend({}, defaultResolve, apiResolve, passedResolve);
        
        /* Combine options */
        var config = angular.extend({}, defaultOptions, apiOptions, passedOptions);
        
        /* Set resolve to the combine resolve */
        config.resolve = combineResolve;
        
        /* Return the uibModalInstance */
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Assign Element Roles Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignElementRole = function (elementId, roleId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/assignElementRoles/assignElementRoles.html',
            controller: 'EditAssignElementRolesModalCtrl',
            resolve: {
                ApiRoutesUsers: 'ApiRoutesUsers',
                ApiRoutesGroups: 'ApiRoutesGroups',
                editing: function(ApiRoutesUsers) {
                    ApiRoutesUsers.getUserGroups(data);
                },
                list: function(ApiRoutesGroups) {
                    ApiRoutesGroups.getGroupList(data);
                }
            }
        });
        
    };
    api.openAssignRoleElement = function(roleId, elementId) {
        api.openAssignElementRole(elementId, roleId);
    };
    
    /*
     * Open Edit Assign Group Roles Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignGroupRole = function(groupId, roleId) {
        var data = { roleId: roleId, groupId: groupId };
        return api.openModal({
            templateUrl: templatePath + 'admin/assignGroupRoles/assignGroupRoles.html',
            controller: 'EditAssignGroupRolesModalCtrl'
        });
    };
    api.openAssignRoleGroup = function(roleId, groupId) {
        api.openAssignGroupRole(groupId, roleId);
    };
    
    /*
     * Open Edit Assign User Groups Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignUserGroup = function(userId, groupId) {
        var data = { userId: userId, groupId: groupId };
        return api.openModal({
            templateUrl: templatePath + 'admin/assignUserGroups/assignUserGroups.html',
            controller: 'EditAssignUserGroupsModalCtrl'
        });
    };
    api.openAssignGroupUser = function(groupId, userId) {
        api.openAssignUserGroups(userId, groupId);
    };
    
    /*
     * Open Edit Config Variable Modal
     * 
     * @return uibModalInstance
     */
    api.openSystemVariable = function(variableId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editSystemVariable/editSystemVariable.html',
            controller: 'EditSystemVariableModalCtrl',
            resolve: {
                ApiRoutesSystemVariables: 'ApiRoutesSystemVariables',
                editing: function(ApiRoutesSystemVariables) {
                    return (variableId) ? ApiRoutesSystemVariables.getVariable(variableId) : {};
                }
            }
        });
    };
    
    /*
     * Open Edit Group Modal
     * 
     * @return uibModalInstance
     */
    api.openEditGroup = function(groupId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editGroup/editGroup.html',
            controller: 'EditGroupModalCtrl',
            resolve: {
                ApiRoutesGroups: 'ApiRoutesGroups',
                editing: function(ApiRoutesGroups) {
                    return (groupId) ? ApiRoutesGroups.getGroup(groupId) : {};
                }
            }
        });
    };
    
    /*
     * Open Edit Role Modal
     * 
     * @return uibModalInstance
     */
    api.openEditRole = function(roleId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editRole/editRole.html',
            controller: 'EditRoleModalCtrl',
            resolve: {
                ApiRoutesRoles: 'ApiRoutesRoles',
                editing: function(ApiRoutesRoles) {
                    return (roleId) ? ApiRoutesRoles.getRole(roleId) : {};
                }
            }
        });
    };
    
    /*
     * Open Edit User Modal
     * 
     * @return uibModalInstance
     */
    api.openEditUser = function(userId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editUser/editUser.html',
            controller: 'EditUserModalCtrl',
            resolve: {
                ApiRoutesUsers: 'ApiRoutesUsers',
                editing: function(ApiRoutesUsers) {
                    return (userId) ? ApiRoutesUsers.getUser(userId) : {};
                }
            }
        });
    };
    
    /*
     * Open Edit Edit Visibility Element (Tag) Modal
     * 
     * @return uibModalInstance
     */
    api.openEditVisibilityField = function(fieldId) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editVisibilityElement/editVisibilityField.html',
            controller: 'EditVisibilityFieldModalCtrl',
            resolve: {
                ApiRoutesFieldVisibility: 'ApiRoutesFieldVisibility',
                editing: function(ApiRoutesFieldVisibility) {
                    return (fieldId) ? ApiRoutesFieldVisibility.getField(fieldId) : {};
                }
            }
        });
    };

    return api;
}]);