'use strict';

/* 
 * API Routes for Role Roles
 * 
 * API calls related to group roles.
 */

angular.module('apiRoutes.roles', [])
.factory('ApiRoutesRoles', ['ApiService', function (API) {
        
    var api = {};

    api.getRole = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid group role please check your parameters and try again.');
        }
        return API.get('role/get/' + id, 'Could not get group role.');
    };
    
    api.newRole = function(role) {
        if(angular.isUndefined(role.role) || angular.isUndefined(role.desc)) {
            return API.reject('Invalid group role please check your parameters and try again.');
        }
        return API.post('role/insert/', role, 'System unable to create new group role.');
    };

    api.saveRole = function(role) {
        if(angular.isUndefined(role.id) || angular.isUndefined(role.role) || angular.isUndefined(role.desc)) {
            return API.reject('Invalid group role please check your parameters and try again.');
        }

        return API.post('role/update/' + role.id, role, 'System unable to save group role.');
    };

    api.deleteRole = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid group role please check your parameters and try again.');
        }
        return API.delete('role/delete/' + id, 'System unable to delete group role.');
    };

    return api;
}]);