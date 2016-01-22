'use strict';

/* 
 * API Routes for Auth
 * 
 * API calls related to authentication.
 */

angular.module('apiRoutes.auth', [])
.factory('ApiRoutesAuth', ['ApiService', function (API) {
        
    var api = {};

    api.postLogin = function(credentials) {
        if(!credentials.password || !credentials.email) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        
        var fd = new FormData();
        fd.append('email', credentials.email);
        fd.append('password', credentials.password);
        fd.append('remember', credentials.remember || false);
        if(credentials.logout) { fd.append('logout', credentials.logout); };

        return API.post('auth/login/', fd, 'System unable to login.');
    };

    api.postLogout = function(logout) {
        var fd = new FormData();
        fd.append('logout', logout);
        
        return API.post('auth/logout/', fd, 'System unable to logout.');
    };

    api.postForgotPasswordEmail = function(email) {
        var fd = new FormData();
        fd.append('email', email);

        return API.post('auth/password-reset/', fd, 'Error sending password reset email.');
    };

    api.postValidatePasswordResetToken = function(token) {
        if(!token) {
            return API.reject('Invalid password reset token.');
        }

        var fd = new FormData();
        fd.append('token', token);

        return API.post('auth/validate-token/', fd, 'Error validating token.');
    };

    api.updatePassword = function(user) {
        if(!user.id || !user.email || !user.token || !user.password || user.password <= 0) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        var fd = new FormData();
        fd.append('id', user.id);
        fd.append('email', user.email);
        fd.append('password', user.password);
        fd.append('token', user.token);

        return API.post('auth/change-password/', fd, 'Error changing password.');
    };

    api.getAuthenticatedUser = function(credentials) {
        if(!credentials.key || !credentials.token) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        
        var fd = new FormData();
        fd.append('key', credentials.key);
        fd.append('token', credentials.token);

        return API.post('auth/authenticate/', fd, 'Error, User Not Authenticated.');
    };

    return api;
}]);