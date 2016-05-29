'use strict';

/* 
 * Service to Load UI Bootstrap Modals
 * 
 * Includes modal controllers and provides and api to launch the modal.
 * https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('ModalService', [
    'app.modal.editGroup',
    'app.modal.editProductCategory',
    'app.modal.editProductTag',
    'app.modal.editRole',
    'app.modal.editSystemVariable',
    'app.modal.editUser',
    'app.modal.editVisibilityField',
    'app.modal.signup',
    'app.modal.signupInvite',
    'app.modal.forgotPassword'
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
                $q: '$q',
                ApiRoutesUsers: 'ApiRoutesUsers',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                groupList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleGroupsList();
                },
                editing: function($q, ApiRoutesUsers) {
                    return $q(function (resolve, reject) {
                            if (angular.isObject(user)) {
                                return resolve(user);
                            } else if (angular.isNumber(+user)) {
                                ApiRoutesUsers.getUser(user).then(function (result) {
                                    console.log(result);
                                    return resolve(result.user);
                                }, function (error) {
                                    console.log(error);
                                    return reject(error);
                                });
                            } else {
                                return resolve({});
                            }
                    });
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
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                roleList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleRolesList();
                },
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

    /*
    * Open Signup Modal
    * 
    * @return uibModalInstance
    */
    api.openSignup = function (currentTeam) {
        return api.openModal({
            templateUrl: templatePath + 'auth/signup/signup.html',
            controller: 'SignupModalCtrl',
            resolve: {
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                currentTeam: function() {
                    return currentTeam || false;
                },
                teamsList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleTeamsList();
                }
            }
        });
    };

    /*
    * Open Forgot Password Modal
    * 
    * @return uibModalInstance
    */
    api.openForgotPassword = function (emailAddress) {
        return api.openModal({
            templateUrl: templatePath + 'auth/forgotPassword/forgotPassword.html',
            controller: 'ForgotPasswordCtrl',
            resolve: {
                ForgotEmailAddress: function () {
                    if (angular.isDefined(emailAddress)) {
                        return emailAddress;
                    } else {
                        return '';
                    }
                }
            }
        });
    };
    
    /*
     * Open Invite New User Modal
     * 
     * @return uibModalInstance
     */
    api.openInviteSiteSignup = function() {
        return api.openModal({
            templateUrl: templatePath + 'auth/invitePlayer/invitePlayer.html',
            controller: 'SignupInviteModalCtrl',
            resolve: {
                InvitingPlayer: function() {
                    return false;
                }
            }
        });
    };
    
    /*
     * Open Edit Product Category Modal
     * 
     * @return uibModalInstance
     */
    api.openEditProductCategory = function(category) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editProductCategory/editProductCategory.html',
            controller: 'EditProductCategoryModalCtrl',
            resolve: {
                ApiRoutesSystemVisibility: 'ApiRoutesSystemVisibility',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                roleList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleRolesList();
                },
                editing: function(ApiRoutesSystemVisibility) {
                    if(angular.isDefined(category)) {
                        return (angular.isObject(category)) ? category : 
                                ApiRoutesSystemVisibility.getField(category);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    /*
     * Open Edit Product Tag Modal
     * 
     * @return uibModalInstance
     */
    api.openEditProductTag = function(tag) {
        return api.openModal({
            templateUrl: templatePath + 'admin/editProductTag/editProductTag.html',
            controller: 'EditProductTagModalCtrl',
            resolve: {
                ApiRoutesSystemVisibility: 'ApiRoutesSystemVisibility',
                ApiRoutesSimpleLists: 'ApiRoutesSimpleLists',
                roleList: function(ApiRoutesSimpleLists) {
                    return ApiRoutesSimpleLists.simpleRolesList();
                },
                editing: function(ApiRoutesSystemVisibility) {
                    if(angular.isDefined(tag)) {
                        return (angular.isObject(tag)) ? tag : 
                                ApiRoutesSystemVisibility.getField(tag);
                    } else {
                        return {};
                    }
                }
            }
        });
    };
    
    return api;
}]);