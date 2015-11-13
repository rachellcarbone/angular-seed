'use strict';

/* 
 * API V1: User Roles 
 * 
 * API calls related to the user role routes.
 */

angular.module('api.v1.userRoles', [])
    .factory('UserRoleService', ['APIV2Service', function(API) {
            
    var api = {};
    
    api.getRoles = function() {
        return API.get('auth/roles', 'Error getting user roles.');
    };
    
    return api;
    
}]);