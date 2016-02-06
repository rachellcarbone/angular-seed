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
        return API.get('field/' + id, 'Could not get user.');
    };
    
    api.newVisibilityField = function(user) {
        if(!user.first || !user.last || !user.email || !user.password) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('user/', user, 'System unable to create new user.');
    };

    api.saveVisibilityField = function(user) {
        if(!user.first || !user.last || !user.email) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('auth/login/', user, 'System unable to save user.');
    };

    api.deleteVisibilityField = function(userId) {
        return API.delete('delete/user/' + userId, 'System unable to delete user.');
    };

    api.disableVisibilityField = function(userId) {
        return API.post('user/disable/' + userId, 'System unable to disable user.');
    };

    api.enableVisibilityField = function(userId) {
        return API.post('user/enable/' + userId, 'System unable to enable user.');
    };

    api.addVisibilityFieldGroup = function(userGroupPair) {
        if(!userGroupPair.userId || !userGroupPair.groupIdl) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        
        return API.post('user/add/group/', userGroupPair, 'System unable to add group to user.');
    };

    api.removeVisibilityFieldGroup = function(userGroupPair) {
        if(!userGroupPair.userId || !userGroupPair.groupIdl) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        
        return API.post('user/remove/group/', userGroupPair, 'System unable to remove group from user.');
    };

    return api;
}]);