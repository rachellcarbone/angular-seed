'use strict';

/* 
 * API Routes for User Groups
 * 
 * API calls related to user groups.
 */

angular.module('apiRoutes.groups', [])
.factory('ApiRoutesGroups', ['ApiService', function (API) {
        
    var api = {};

    api.getGroup = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid user group please check your parameters and try again.');
        }
        return API.get('group/get/' + id, 'Could not get user group.');
    };
    
    api.newGroup = function(group) {
        if(angular.isUndefined(group.group) || angular.isUndefined(group.desc)) {
            return API.reject('Invalid user group please check your parameters and try again.');
        }
        return API.post('group/insert/', group, 'System unable to create new user group.');
    };

    api.saveGroup = function(group) {
        if(angular.isUndefined(group.id) || angular.isUndefined(group.group) || angular.isUndefined(group.desc)) {
            return API.reject('Invalid user group please check your parameters and try again.');
        }

        return API.post('group/update/' + group.id, group, 'System unable to save user group.');
    };

    api.deleteGroup = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid user group please check your parameters and try again.');
        }
        return API.delete('group/delete/' + id, 'System unable to delete user group.');
    };
    
    api.unassignRoleFromGroup = function(pair) {
        if(angular.isUndefined(pair.roleId) || angular.isUndefined(pair.groupId)) {
            return API.reject('Invalid role / group pair please check your parameters and try again.');
        }
        return API.post('group/unassign-role', pair, 'System unable to unassign role to group.');
    };
    
    api.assignRoleToGroup = function(pair) {
        if(angular.isUndefined(pair.roleId) || angular.isUndefined(pair.groupId)) {
            return API.reject('Invalid role / group pair please check your parameters and try again.');
        }
        return API.post('group/assign-role', pair, 'System unable to assign role from group.');
    };

    return api;
}]);