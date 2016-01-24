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
    'rcAuth.constants',
    'app.auth'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Auth Route */
        $stateProvider.state('app.auth', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@app.auth': {
                    templateUrl: 'app/views/auth/authHeader/authHeader.html',
                    controller: 'AuthHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/auth/authLayout/authLayout.html',
                    controller: 'AuthLayoutCtrl'
                },
                'footer@app.auth': {
                    templateUrl: 'app/views/auth/authFooter/authFooter.html',
                    controller: 'AuthFooterCtrl'
                }
            }
        });

        /* Login / Authentication Related States */

        $stateProvider.state('app.auth.signup', {
            title: 'Sign Up',
            url: '/signup',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/signup/signup.html',
                    controller: 'AuthSignupCtrl'
                }
            }
        });

        $stateProvider.state('app.auth.signup.confirmEmail', {
            title: 'Please Confirm Your Email',
            url: '/please-confirm-email'
        });
        
        $stateProvider.state('app.auth.signup.success', {
            title: 'Success! Your Email is Confirmed',
            url: '/success'
        });
        
        $stateProvider.state('app.auth.login', {
            title: 'Login',
            url: '/login',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/login/login.html',
                    controller: 'AuthLoginCtrl'
                }
            }
        });
        
        $stateProvider.state('app.auth.login.locked', {
            title: 'Account Locked',
            url: '/account-locked'
        });
        
        $stateProvider.state('app.auth.login.forgotPassword', {
            title: 'Forgot Password',
            url: '/forgot-password'
        });
        
        $stateProvider.state('app.auth.login.forgotPassword.resetEmailSent', {
            title: 'Reset Instructions Have Been Sent',
            url: '/reset-instructions-sent'
        });
        
        $stateProvider.state('app.auth.login.forgotPassword.changePassword', {
            title: 'Change Your Password',
            url: '/change-password'
        });

        // This should catch incoming requests, 
        // Trigger the logout method and then redirect
        // to public.
        $stateProvider.state('app.auth.logout', {
            url: '/logout'
        });

    }]);