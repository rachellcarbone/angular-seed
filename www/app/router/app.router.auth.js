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
    'rachels.auth.constants',
    'app.auth'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Auth Route */
        $stateProvider.state('auth', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@auth': {
                    templateUrl: 'app/views/auth/authHeader/authHeader.html',
                    controller: 'AuthHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/auth/authLayout/authLayout.html',
                    controller: 'AuthLayoutCtrl'
                },
                'footer@auth': {
                    templateUrl: 'app/views/auth/authFooter/authFooter.html',
                    controller: 'AuthFooterCtrl'
                }
            }
        });

        /* Login / Authentication Related States */

        $stateProvider.state('auth.signup', {
            title: 'Sign Up',
            url: '/signup',
            views: {
                'content@auth': {
                }
            }
        });

        $stateProvider.state('auth.signup.success', {
            title: 'Please Confirm Your Email',
            url: '/please-confirm-email',
            views: {
                'content@auth': {
                }
            }
        });
        
        $stateProvider.state('auth.signup.confirmEmail', {
            title: 'Success! Your Email is Confirmed',
            url: '/success',
            views: {
                'content@auth': {
                }
            }
        });
        
        $stateProvider.state('auth.login', {
            title: 'Login',
            url: '/login',
            views: {
                'content@auth': {
                    templateUrl: 'app/views/auth/login/login.html',
                    controller: 'AuthLoginCtrl'
                }
            }
        });
        
        $stateProvider.state('auth.login.forgotPassword', {
            title: 'Sign Up',
            url: '/forgot-password',
            views: {
                'content@auth': {
                }
            }
        });
        
        $stateProvider.state('auth.login.forgotPassword.resetEmailSent', {
            title: 'Reset Instructions Have Been Sent',
            url: '/instructions-sent',
            views: {
                'content@auth': {
                }
            }
        });
        
        $stateProvider.state('auth.login.changePassword', {
            title: 'Change Your Password',
            url: '/change-password',
            views: {
                'content@auth': {
                }
            }
        });

        // This should catch incoming requests, 
        // Trigger the logout method and then redirect
        // to public.
        $stateProvider.state('auth.logout', {
            url: '/logout'
        });

    }]);