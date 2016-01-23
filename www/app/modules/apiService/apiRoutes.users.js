'use strict';

/* 
 * API Routes for Users
 * 
 * API calls related to user data.
 */

angular.module('apiRoutes.users', [])
.factory('ApiRoutesUsers', ['ApiService', function (API) {
        
    var api = {};

    api.getUser = function(id) {
        return API.get('user/' + id, 'Could not get user.');
    };
    
    api.addUser = function(user) {
        if(!user.nameFirst || !user.nameLast || !user.email || !user.password) {
            return API.reject('Invalid user please verify your information and try again.');
        }

        return API.post('user/add/', user, 'System unable to create new user.');
    };

    api.saveUser = function(user) {
        if(!user.id || !user.nameFirst || !user.nameLast || !user.email) {
            return API.reject('Invalid user please verify your information and try again.');
        }

        return API.post('user/save/' + user.id, user, 'System unable to save user.');
    };

    api.deleteUser = function(userId) {
        return API.delete('user/delete/' + userId, 'System unable to delete user.');
    };

    api.disableUser = function(userId) {
        return API.post('user/disable/' + userId, 'System unable to disable user.');
    };

    api.enableUser = function(userId) {
        return API.post('user/enable/' + userId, 'System unable to enable user.');
    };

    api.addUserGroup = function(userGroupPair) {
        if(!userGroupPair.userId || !userGroupPair.groupIdl) {
            return API.reject('Invalid user and group pair please verify your information and try again.');
        }
        
        return API.post('user/add/group/', userGroupPair, 'System unable to add group to user.');
    };

    api.removeUserGroup = function(userGroupPair) {
        if(!userGroupPair.userId || !userGroupPair.groupIdl) {
            return API.reject('Invalid user and group pair please verify your information and try again.');
        }
        
        return API.post('user/remove/group/', userGroupPair, 'System unable to remove group from user.');
    };

    return api;
}]);