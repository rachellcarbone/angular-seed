'use strict';

/* 
 * API Routes for List Content
 * 
 * API calls related to simple lists.
 */

angular.module('apiRoutes.lists', [])
.factory('ApiRoutesSimpleLists', ['ApiService', function (API) {
        
    var api = {};
    
    var getList = function(path) {
        return $q(function (resolve, reject) {
            API.get(path, {}).then(
                function(success) {
                    return resolve(success.list);
                }, function(error) {
                    console.log(error);
                    return resolve([]);
                });
        });
    };
    
    api.simpleUsersList = function() { 
        return getList('/simple-list/users');
    };
    
    api.simpleGroupsList = function() { 
        return getList('/simple-list/user-groups');
    };
    
    api.simpleRolesList = function() { 
        return getList('/simple-list/group-roles');
    };
    
    api.simpleVisibilityFieldList = function() { 
        return getList('/simple-list/visibility-fields');
    };
    
    return api;
}]);