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
            API.post(path, {}).then(
                function(success) {
                    return resolve(success.table);
                }, function(error) {
                    console.log(error);
                    return resolve([]);
                });
        });
    };
    
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
        return getPromise('/datatable/admin/system-variables');
    };
    
    api.adminVisibilityFieldList = function() { 
        return getPromise('/datatable/admin/visibility-fields');
    };
    
    
    // Games
    
    api.publicGamesList = function() { 
        return getPromise('/datatable/games/');
    };
    
    api.publicHostGamesList = function(hostId) { 
        return getPromise('/datatable/games/host/' + hostId);
    };
    
    api.publicVenueGamesList = function(venueId) { 
        return getPromise('/datatable/games/venue/' + venueId);
    };
    
    api.publicTeamGamesList = function(teamId) { 
        return getPromise('/datatable/games/team/' + teamId);
    };
    
    // Game Scoreboard
    
    api.publicGameScoreboardList = function(gameId, roundNumber) { 
        return getPromise('/datatable/game/simple-scoreboard/' + gameId + '/' + roundNumber);
    };
    
    return api;
}]);