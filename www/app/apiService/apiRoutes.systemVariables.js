'use strict';

/* 
 * API Routes for System Variables
 * 
 * API calls related to system config variables.
 */

angular.module('apiRoutes.systemVariables', [])
.factory('ApiRoutesSystemVariables', ['ApiService', function (API) {
        
    var api = {};

    api.getSystemVariableById = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }
        return API.get('config/get/' + id, 'Could not find a system config variable with that id.');
    };

    api.getSystemVariableByName = function(name) {
        if(angular.isUndefined(name)) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }
        return API.get('config/get/', {'variableName': name}, 'Could not find a system config variable by that name.');
    };
    
    api.newSystemVariable = function(variable) {
        if(angular.isUndefined(variable.name) || angular.isUndefined(variable.value) || angular.isUndefined(variable.disabled)) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }
        return API.post('config/insert/', variable, 'System unable to create new config variable.');
    };

    api.saveSystemVariable = function(variable) {
        if(angular.isUndefined(variable.id) || angular.isUndefined(variable.name) || angular.isUndefined(variable.value) || 
                angular.isUndefined(variable.disabled) || angular.isUndefined(variable.indestructible) || angular.isUndefined(variable.locked)) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }
        return API.post('config/update/' + variable.id, variable, 'System unable to save config variable.');
    };

    api.deleteSystemVariable = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid system config variable please check your parameters and try again.');
        }
        return API.delete('config/delete/' + id, 'System unable to delete config variable.');
    };
    
    return api;
}]);