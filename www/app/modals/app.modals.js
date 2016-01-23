'use strict';

/* 
 * UI Bootstrap Modals
 * 
 * Includes modal controllers and provides and api to launch the modal.
 * https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('app.modals', [
    'app.modal.assignElementRoles',
    'app.modal.assignGroupRoles',
    'app.modal.assignUserGroups',
    'app.modal.editConfigVariable',
    'app.modal.editGroup',
    'app.modal.editRole',
    'app.modal.editRole',
    'app.modal.editUser',
    'app.modal.editVisibilityElement'
])
.factory('ModalService', ['$uibModal', function($uibModal) {
        
    var templatePath = 'app/modals/';
    
    var api = {};
    
    var defaultOptions = {
        size: 'md',
        backdrop: 'static'
    };
    
    /*
     * Open Edit Assign Element Roles Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignElementRolesUser = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/assignElementRoles/assignElementRoles.html',
            controller: 'EditAssignElementRolesModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Assign Group Roles Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignGroupRolesUser = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/assignGroupRoles/assignGroupRoles.html',
            controller: 'EditAssignGroupRolesModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Assign User Groups Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignUserGroups = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/assignUserGroups/assignUserGroups.html',
            controller: 'EditAssignUserGroupsModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Config Variable Modal
     * 
     * @return uibModalInstance
     */
    api.openConfigVariableUser = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/editConfigVariable/editConfigVariable.html',
            controller: 'EditConfigVariableModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Group Modal
     * 
     * @return uibModalInstance
     */
    api.openEditGroup = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/editGroup/editGroup.html',
            controller: 'EditGroupModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Role Modal
     * 
     * @return uibModalInstance
     */
    api.openEditRole = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/editRole/editRole.html',
            controller: 'EditRoleModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit User Modal
     * 
     * @return uibModalInstance
     */
    api.openEditUser = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/editUser/editUser.html',
            controller: 'EditUserModalCtrl'
        })
        return $uibModal.open(config);
    };
    
    /*
     * Open Edit Edit Visibility Element (Tag) Modal
     * 
     * @return uibModalInstance
     */
    api.openEditVisibilityElement = function (options) {
        options = options || {};
        var config = angular.extend({}, defaultOptions, options, {
            templateUrl: templatePath + 'admin/editVisibilityElement/editVisibilityElement.html',
            controller: 'EditVisibilityElementModalCtrl'
        })
        return $uibModal.open(config);
    };

    return api;
}]);