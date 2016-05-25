'use strict';

/* 
 * API Routes for Auth
 * 
 * API calls related to authentication.
 */

angular.module('apiRoutes.auth', [])
.factory('ApiRoutesAuth', ['ApiService', function (API) {

    var api = {};

    api.postLogin = function (credentials) {
        if (!credentials.password || !credentials.email) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        credentials.remember = credentials.remember || false;

        return API.post('auth/login/', credentials, 'System unable to login.');
    };

    api.postForgotpassword = function (credentials) {
        if (!credentials.email) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('/auth/forgotpassword/', credentials, 'System unable to request for forgotpassword.');
    };

    api.getforgotemailaddress = function (credentials) {
        if (!credentials.usertoken) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('/auth/getforgotpasswordemail/', credentials, 'System unable to request for forgotpassword.');
    };
    api.postResetpassword = function (credentials) {
        if (!credentials.password || !credentials.email) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('/auth/resetpassword/', credentials, 'System unable to request for resetpassword.');
    };

    api.postFacebookLogin = function (user) {
        if (!user.accessToken ||
                !user.facebookId ||
                !user.nameFirst ||
                !user.nameLast ||
                !user.email ||
                !user.link ||
                !user.locale ||
                !user.timezone ||
                !user.ageRange) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }
        user.remember = user.remember || false;

        return API.post('auth/login/facebook/', user, 'System unable to login.');
    };

    api.postSignup = function (newUser) {
        if (angular.isUndefined(newUser.password)||
            angular.isUndefined(newUser.email)||
            angular.isUndefined(newUser.nameFirst)||
            angular.isUndefined(newUser.nameLast)||
            angular.isUndefined(newUser.phone)) {
            return API.reject('Invalid user please verify your information and try again.');
        }
        return API.post('auth/signup/', newUser, 'System unable to register new user.');
    };

    api.postFacebookSignup = function (newUser) {
        if (!newUser.accessToken ||
                !newUser.facebookId ||
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

    api.postVenueSignup = function (newUser) {
        if (!newUser.password ||
                !newUser.nameLast ||
                !newUser.nameLast ||
                !newUser.email) {
            return API.reject('Invalid user please verify your information and try again.');
        } else if (!newUser.venue ||
                !newUser.address ||
                !newUser.city ||
                !newUser.state ||
                !newUser.zip) {
            return API.reject('Invalid venue please verify your information and try again.');
        }
        return API.post('auth/venue/signup/', newUser, 'System unable to register new user user or venue.');
    };

    api.postVenueFacebookSignup = function (newUser) {
        if (!newUser.accessToken ||
                !newUser.facebookId ||
                !newUser.nameFirst ||
                !newUser.nameLast ||
                !newUser.email ||
                !newUser.link ||
                !newUser.locale ||
                !newUser.timezone ||
                !newUser.ageRange) {
            return API.reject('Invalid user please verify your information and try again.');
        } else if (!newUser.venue ||
                !newUser.address ||
                !newUser.city ||
                !newUser.state ||
                !newUser.zip) {
            return API.reject('Invalid venue please verify your information and try again.');
        }
        return API.post('auth/venue/signup/facebook/', newUser, 'System unable to register new facebook user or venue.');
    };

    api.postLogout = function (logout) {
        var data = {};
        data.logout = logout;

        return API.post('auth/logout/', data, 'System unable to logout.');
    };

    api.getAuthenticatedUser = function (credentials) {
        if (!credentials.apiKey || !credentials.apiToken) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('auth/authenticate/', credentials, 'Error, User Not Authenticated.');
    };

    api.postAdditionalInfo = function (user) {
        if (!user.userId ||
                !(user.triviaLove || user.referrer || user.terms)) {
            return API.reject('Invalid user please verify your information and try again.');
        }
        return API.post('auth/signup/additional/', user, 'System unable to save additional user questions.');
    };





    api.postForgotPasswordEmail = function (email) {
        var data = { 'email': email };

        return API.post('auth/password-reset/', data, 'Error sending password reset email.');
    };

    api.postValidatePasswordResetToken = function (token) {
        if (!token) {
            return API.reject('Invalid password reset token.');
        }

        var data = { 'token': token };

        return API.post('auth/validate-token/', data, 'Error validating token.');
    };

    api.updatePassword = function (user) {
        if (!user.id || !user.email || !user.token || !user.password || user.password <= 0) {
            return API.reject('Invalid credentials please verify your information and try again.');
        }

        return API.post('auth/change-password/', user, 'Error changing password.');
    };

    return api;
}]);