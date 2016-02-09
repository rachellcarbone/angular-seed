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
     * Open Edit Config Variable Modal
     * 
     * @return uibModalInstance
     */
    api.openSystemVariable = function(variable) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editSystemVariable/editSystemVariable.html',
            controller: 'EditSystemVariableModalCtrl',
            resolve: {
                ApiRoutesSystemVariables: 'ApiRoutesSystemVariables',
                editing: function(ApiRoutesSystemVariables) {
                    if(angular.isDefined(variable)) {
                        return (angular.isObject(variable)) ? variable : 
                                ApiRoutesSystemVariables.getVariable(variable);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    /*
     * Open Edit Group Modal
     * 
     * @return uibModalInstance
     */
    api.openEditGroup = function(group) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editGroup/editGroup.html',
            controller: 'EditGroupModalCtrl',
            resolve: {
                ApiRoutesGroups: 'ApiRoutesGroups',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                roleList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleRolesList();
                },
                editing: function(ApiRoutesGroups) {
                    if(angular.isDefined(group)) {
                        return (angular.isObject(group)) ? group : 
                                ApiRoutesGroups.getGroup(group);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    /*
     * Open Edit Role Modal
     * 
     * @return uibModalInstance
     */
    api.openEditRole = function(role) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editRole/editRole.html',
            controller: 'EditRoleModalCtrl',
            resolve: {
                ApiRoutesRoles: 'ApiRoutesRoles',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                groupList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleGroupsList();
                },
                fieldList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleVisibilityFieldList();
                },
                editing: function(ApiRoutesRoles) {
                    if(angular.isDefined(role)) {
                        return (angular.isObject(role)) ? role : 
                                ApiRoutesRoles.getRole(role);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    /*
     * Open Edit User Modal
     * 
     * @return uibModalInstance
     */
    api.openEditUser = function(user) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editUser/editUser.html',
            controller: 'EditUserModalCtrl',
            resolve: {
                ApiRoutesUsers: 'ApiRoutesUsers',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                roleList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleRolesList();
                },
                editing: function(ApiRoutesUsers) {
                    if(angular.isDefined(user)) {
                        return (angular.isObject(user)) ? user : 
                                ApiRoutesUsers.getUser(user);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    /*
     * Open Edit Edit Visibility Element (Tag) Modal
     * 
     * @return uibModalInstance
     */
    api.openEditVisibilityField = function(field) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editVisibilityField/editVisibilityField.html',
            controller: 'EditVisibilityFieldModalCtrl',
            resolve: {
                ApiRoutesSystemVisibility: 'ApiRoutesSystemVisibility',
                editing: function(ApiRoutesSystemVisibility) {
                    if(angular.isDefined(field)) {
                        return (angular.isObject(field)) ? field : 
                                ApiRoutesSystemVisibility.getField(field);
                    } else {
                        return {};
                    }
                }
            }
        });
    };

    return api;
}]);