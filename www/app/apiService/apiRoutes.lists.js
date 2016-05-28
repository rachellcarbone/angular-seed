'use strict';

/* 
 * API Routes for List Content
 * 
 * API calls related to simple lists.
 */

angular.module('apiRoutes.lists', [])
.factory('ApiRoutesSimpleLists', ['ApiService', '$q', function (API, $q) {
        
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
    
    api.simpleVenuesList = function() { 
        return getList('/simple-list/venues');
    };
    
    api.simpleTeamsList = function() { 
        return getList('/simple-list/teams');
    };
    
    api.simpleActiveGamesList = function() { 
        return getList('/simple-list/games');
    };
    
    return api;
}]);