'use strict';

/* 
 * API V1: Users 
 * 
 * API calls related to the user routes.
 */

angular.module('api.v1.users', [])
    .factory('UserService', ['APIV2Service', function(API) {
        
    var api = {};
    
    api.validateUser = function(data) {
        return (typeof data !== 'undefined' &&
                typeof data.fname !== 'undefined' &&
                typeof data.lname !== 'undefined' &&
                typeof data.email !== 'undefined' &&
                typeof data.roleId !== 'undefined');
    };
    
    api.getUser = function(id) {
        return API.get('user/' + id, 'Error getting user.');
    };
    
    api.getUsers = function() {
        return API.get('users', 'Error getting active users.');
    };
    
    api.getAllUsers = function() {
        return API.get('users/all', 'Error getting all users.');
    };
    
    api.insertUser = function(data) {
        if(!api.validateUser(data)) {
            return API.reject('Invalid user. Please check your form and try again.');
        }
        
        var fd = new FormData();
        fd.append('fname', data.fname);
        fd.append('lname', data.lname);
        fd.append('email', data.email);
        fd.append('role', data.roleId);
        
        return API.post('user/new/', fd, 'Error adding user.');
    };
    
    api.saveUser = function(data, id) {
        if(!api.validateUser(data)) {
            return API.reject('Invalid user. Please check your form and try again.');
        }
        
        var fd = new FormData();
        fd.append('fname', data.fname);
        fd.append('lname', data.lname);
        fd.append('email', data.email);
        fd.append('role', data.roleId);
        
        return API.post('user/update/' + id, fd, 'Error saving user.');
    };
    
    api.deleteUser = function (id) {
        return API.delete('user/delete/' + id, 'Error deleting user.');
    };
    
    return api;
}]);