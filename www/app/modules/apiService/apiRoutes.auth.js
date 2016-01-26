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
        credentials.remember = credentials.remember|| false;

        return API.post('auth/login/', credentials, 'System unable to login.');
    };
    
    api.postSignup = function(newUser) {
        if(!newUser.password || 
                !newUser.nameLast || 
                !newUser.nameLast || 
                !newUser.email) {
            return API.reject('Invalid user please verify your information and try again.');
        }
        return API.post('auth/signup/', newUser, 'System unable to register new user.');
    };
    
    api.postFacebookSignup = function(newUser) {
        if(!newUser.facebookId || 
                !newUser.nameFirst || 
                !newUser.nameLast || 
                !newUser.email || 
                !newUser.link || 
                !newUser.locale || 
                !newUser.timezone || 
                !newUser.ageRange) {
            return API.reject('Invalid user please verify your information and try again.');
        }
        return API.post('auth/signup/facebook/', newUser, 'System unable to register new facebook user.');
    };

    api.postLogout = function(logout) {
        var data = {};
        data.logout = logout;
        
        return API.post('auth/logout/', data, 'System unable to logout.');
    };

    api.getAuthenticatedUser = function(credentials) {
        if(!credentials.apiKey || !credentials.apiToken) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('auth/authenticate/', credentials, 'Error, User Not Authenticated.');
    };
    
    
    



    api.postForgotPasswordEmail = function(email) {
        var data = { 'email' : email };

        return API.post('auth/password-reset/', data, 'Error sending password reset email.');
    };

    api.postValidatePasswordResetToken = function(token) {
        if(!token) {
            return API.reject('Invalid password reset token.');
        }

        var data = { 'token' : token };

        return API.post('auth/validate-token/', data, 'Error validating token.');
    };

    api.updatePassword = function(user) {
        if(!user.id || !user.email || !user.token || !user.password || user.password <= 0) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('auth/change-password/', user, 'Error changing password.');
    };

    return api;
}]);