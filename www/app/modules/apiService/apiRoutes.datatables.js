'use strict';

/* 
 * API Routes for Datatable Content
 * 
 * API calls related to datatables.
 */

angular.module('apiRoutes.datatables', [])
.factory('ApiRoutesDatatables', ['ApiService', '$q', function (API, $q) {
        
    var api = {};
    
    var getPromise = function(path) {
        return $q(function (resolve, reject) {
            API.post('/datatable/admin/users').then(
                function(success) {
                    return resolve(success.table);
                }, function(error) {
                    console.log(error);
                    return resolve([]);
                });
        });
    }
    
    api.adminUsersList = function() {
        return getPromise('/datatable/admin/users');
    };
    
    api.adminGroupsList = function() { 
        return getPromise('/datatable/admin/user-groups');
    };
    
    api.adminRolesList = function() { 
        return getPromise('/datatable/admin/group-roles');
    };
    
    api.adminConfigList = function() { 
        return getPromise('/datatable/admin/config-variables');
    };
    
    return api;
}]);