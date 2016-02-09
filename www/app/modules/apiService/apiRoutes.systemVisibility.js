'use strict';

/* 
 * API Routes for Field Visibility
 * 
 * API calls related to field visibility.
 */

angular.module('apiRoutes.systemVisibility', [])
.factory('ApiRoutesSystemVisibility', ['ApiService', function (API) {
        
    var api = {};

    api.getVisibilityField = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid visibility field please check your parameters and try again.');
        }
        return API.get('field/get/' + id, 'Could not get visibility field.');
    };
    
    api.newVisibilityField = function(field) {
        if(angular.isUndefined(field.identifier) || angular.isUndefined(field.type) || angular.isUndefined(field.desc)) {
            return API.reject('Invalid visibility field please check your parameters and try again.');
        }
        return API.post('field/insert/', field, 'System unable to create new visibility field.');
    };

    api.saveVisibilityField = function(field) {
        if(angular.isUndefined(field.id) || angular.isUndefined(field.identifier) || angular.isUndefined(field.type) || angular.isUndefined(field.desc)) {
            return API.reject('Invalid visibility field please check your parameters and try again.');
        }

        return API.post('field/update/' + field.id, field, 'System unable to save visibility field.');
    };

    api.deleteVisibilityField = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid visibility field please check your parameters and try again.');
        }
        return API.delete('field/delete/' + id, 'System unable to delete visibility field.');
    };
    
    api.unassignRoleFromField = function(pair) {
        if(angular.isUndefined(pair.roleId) || angular.isUndefined(pair.fieldId)) {
            return API.reject('Invalid role / field pair please check your parameters and try again.');
        }
        return API.post('field/unassign-role', pair, 'System unable to unassign role to field.');
    };
    
    api.assignRoleToField = function(pair) {
        if(angular.isUndefined(pair.roleId) || angular.isUndefined(pair.fieldId)) {
            return API.reject('Invalid role / field pair please check your parameters and try again.');
        }
        return API.post('field/assign-role', pair, 'System unable to assign role from field.');
    };

    return api;
}]);