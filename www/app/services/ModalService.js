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
    api.openAssignElementRolesUser = function (options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/assignElementRoles/assignElementRoles.html',
            controller: 'EditAssignElementRolesModalCtrl'
        }, options, resolve);
        
    };
    
    /*
     * Open Edit Assign Group Roles Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignGroupRolesUser = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/assignGroupRoles/assignGroupRoles.html',
            controller: 'EditAssignGroupRolesModalCtrl'
        }, options, resolve);
    };
    
    /*
     * Open Edit Assign User Groups Modal
     * 
     * @return uibModalInstance
     */
    api.openAssignUserGroups = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/assignUserGroups/assignUserGroups.html',
            controller: 'EditAssignUserGroupsModalCtrl'
        }, options, resolve);
    };
    
    /*
     * Open Edit Config Variable Modal
     * 
     * @return uibModalInstance
     */
    api.openConfigVariable = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editConfigVariable/editConfigVariable.html',
            controller: 'EditConfigVariableModalCtrl'
        }, options, resolve);
    };
    
    /*
     * Open Edit Group Modal
     * 
     * @return uibModalInstance
     */
    api.openEditGroup = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editGroup/editGroup.html',
            controller: 'EditGroupModalCtrl'
        }, options, resolve);
    };
    
    /*
     * Open Edit Role Modal
     * 
     * @return uibModalInstance
     */
    api.openEditRole = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editRole/editRole.html',
            controller: 'EditRoleModalCtrl'
        }, options, resolve);
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
    api.openEditVisibilityElement = function(options, resolve) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editVisibilityElement/editVisibilityElement.html',
            controller: 'EditVisibilityElementModalCtrl'
        }, options, resolve);
    };

    return api;
}]);