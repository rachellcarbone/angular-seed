'use strict';

/*
 * State Declarations: Authorization Pages
 * 
 * Set up the states for auth routes, such as the 
 * login page, forgot password and other auth states.
 * Ueses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.auth', [
    'rachels.auth',
    'layout.auth'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Auth Route */
        $stateProvider.state('auth', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'layout@': {
                    templateUrl: 'app/layouts/auth/auth.html',
                    controller: 'AuthLayoutCtrl'
                }
            }
        });

        $stateProvider.state('auth.login', {
            title: 'Login',
            url: '/login',
            views: {
                'content@': {}
            }
        });

        /* 
         * Autherization Roles
         

        $stateProvider.state('auth', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            resolve: {}
        });

        $stateProvider.state('auth.login', {
            title: 'Login',
            url: '/login',
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/login.html',
                    controller: 'LoginCtrl'
                }
            }
        }).state('auth.logout', {
            title: 'Logout',
            url: '/logout',
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/login.html',
                    controller: 'LoginCtrl'
                }
            }
        }).state('auth.sendResetEmail', {
            title: 'Forgot Password',
            url: '/password-reset',
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/resetPassword.html',
                    controller: 'ResetPasswordCtrl'
                }
            }
        }).state('auth.changePassword', {
            title: 'Change Your Password',
            url: '/change-password/:token',
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/changePassword.html',
                    controller: 'ChangePasswordCtrl'
                }
            },
            resolve: {
                AuthService: 'AuthService',
                passwordResetTokenRequest: function (AuthService, $stateParams) {
                    return AuthService.validatePasswordReset($stateParams.token);
                }
            }
        }).state('auth.confirmEmail', {
            title: 'Confirm Your Email',
            url: '/confirm-email',
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/confirmEmail.html',
                    controller: 'ConfirmEmailCtrl'
                }
            }
        });
    */

    }]);