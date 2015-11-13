'use strict';

/* 
 * API V1: Auth Roles 
 * 
 * API calls related to the user role routes.
 */

angular.module('api.v1.auth', [])
    .factory('UserService', ['APIV2Service', function(API) {
            
    var api = {};
    
    api.updateUserPassword = function(data, id) {
        if(typeof data === 'undefined' ||
                typeof data.password === 'undefined' ||
                typeof data.newPassword === 'undefined') {
            return API.reject('Invalid password change request. Please check your form and try again.');
        }
        
        var fd = new FormData();
        fd.append('password', data.password);
        fd.append('newPassword', data.newPassword);
        
        return API.post('user/update/password/' + id, fd, 'Error saving user password.');
    };
    
    api.deactivateUser = function (id) {
        var fd = new FormData();
        fd.append('status', 'deactivate');
        
        return API.post('user/update/status/' + id, fd, 'Error deactivating user.');
    };
    
    api.reactivateUser = function (id) {
        var fd = new FormData();
        fd.append('status', 'reactivate');
        
        return API.post('user/update/status/' + id, fd, 'Error reactivating user.');
    };
    
    return api;
}]);