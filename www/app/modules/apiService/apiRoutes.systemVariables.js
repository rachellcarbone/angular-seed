'use strict';

/* 
 * API Routes for System Variables
 * 
 * API calls related to system config variables.
 */

angular.module('apiRoutes.systemVariables', [])
.factory('ApiRoutesSystemVariables', ['ApiService', function (API) {
        
    var api = {};

    api.getSystemVariable = function(id) {
        return API.get('config/get/' + id, 'Could not find a system config variable with that id.');
    };

    api.getSystemVariable = function(name) {
        return API.get('config/get/', {'variableName': name}, 'Could not find a system config variable by that name.');
    };
    
    api.newSystemVariable = function(variable) {
        if(!variable.name || !variable.value || !variable.disabled) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }

        return API.post('config/insert/', variable, 'System unable to create new config variable.');
    };

    api.saveSystemVariable = function(variable) {
        if(!variable.id || !variable.name || !variable.value || !variable.disabled || !variable.indestructible || !variable.locked) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }

        return API.post('config/update/' + variable.id, variable, 'System unable to save config variable.');
    };

    api.deleteSystemVariable = function(id) {
        return API.delete('config/delete/' + id, 'System unable to delete config variable.');
    };
    
    return api;
}]);