'use strict';

/* 
 * UI Bootstrap Modals
 * 
 * Includes modal controllers and provides and api to launch the modal.
 * https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('app.modals', [
    'app.modals.assignElementRoles',
    'app.modals.assignGroupRoles',
    'app.modals.assignUserGroups',
    'app.modals.editConfigVariable',
    'app.modals.editGroup',
    'app.modals.editRole',
    'app.modals.editRole',
    'app.modals.editUser',
    'app.modals.editVisibilityElement'
])
.module('DataTableHelper', [])
.factory('DataTableHelper', ['$uibModal', function($uibModal) {
        
    var templatePath = '';
    var api = {};
    
    var defaultOptions = {
        size: 'md',
        backdrop: 'static'
    };
    
    /*
     * @return modalInstance
     */
    api.openEditUser = function (resolveCB, options) {
        return $uibModal.open({
            templateUrl: 'js/app/modals/templates/editCategoryModal.html',
            controller: 'EditCategoryModalCtrl',
            resolve: {
                reslovedData: resolveCB
            }
        });
    };

    return api;
}]);