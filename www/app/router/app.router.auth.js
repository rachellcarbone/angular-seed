'use strict';

/*
 * State Declarations: Authorization Pages
 * 
 * Set up the states for auth routes, such as the 
 * login page, forgot password and other auth states.
 * Uses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.auth', [
    'rcAuth.constants',
    'app.auth'
]);
app.config(['$stateProvider', 'USER_ROLES', 
    function ($stateProvider, USER_ROLES) {

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
            bodyClass: 'auth signup',
            title: 'Sign Up',
            url: '/signup',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/signup/signup.html',
                    controller: 'AuthSignupCtrl'
                },
                'signupform@app.auth.signup': {
                    templateUrl: 'app/views/auth/signup/signupForm.html'
                }
            }
        });

        $stateProvider.state('app.auth.signup.invite', {
            bodyClass: 'auth signup',
            title: 'You have been invited!',
            url: '/:token',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/playerInvite/playerInvite.html',
                    controller: 'AuthPlayerInviteCtrl'
                },
                'signupform@app.auth.signup.invite': {
                    templateUrl: 'app/views/auth/signup/signupForm.html'
                }
            },
            resolve: {
                $q: '$q',
                ApiRoutesEmails: 'ApiRoutesEmails',
                $stateParams: '$stateParams',
                InvitationData: function($q, $stateParams, ApiRoutesEmails) {
                    return $q(function(resolve, reject) {  
                        ApiRoutesEmails.validateInviteToken($stateParams.token).then(function (result) {
                            resolve(result.invite);
                            console.log(result);
                        }, function(error) {
                            console.log(error);
                            resolve(error);
                        });
                    });
                }
            }
        });

        $stateProvider.state('app.auth.signup.iframe', {
            bodyClass: 'auth signup iframe-compatible',
            title: 'Sign Up',
            url: '/iframe',
            views: {
                'header@app.auth': {},
                'content@app.auth': {
                    templateUrl: 'app/views/auth/signup/signupiFrame.html',
                    controller: 'AuthSignupCtrl'
                },
                'signupform@app.auth.signup.iframe': {
                    templateUrl: 'app/views/auth/signup/signupForm.html'
                },
                'footer@app.auth': {}
            }
        });

        $stateProvider.state('app.auth.signupVenue', {
            bodyClass: 'auth signup',
            title: 'Venue Sign Up',
            url: '/venue/signup',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/signupVenue/signupVenue.html',
                    controller: 'AuthSignupVenueCtrl'
                },
                'signupform@app.auth.signupVenue': {
                    templateUrl: 'app/views/auth/signupVenue/signupVenueForm.html'
                }
            }/*,
            resolve: {
                $q: '$q',
                $rootScope: '$rootScope',
                AUTH_EVENTS: 'AUTH_EVENTS',
                alreadyLoggedIn: function(initUser, $rootScope, AUTH_EVENTS, $q, AuthService) {
                    return $q(function(resolve, reject) {  
                        if(AuthService.getUser()) {
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                    });
                }
            }*/
        });

        $stateProvider.state('app.auth.signupVenue.iframe', {
            bodyClass: 'auth signup iframe-compatible',
            title: 'Venue Sign Up',
            url: '/iframe',
            views: {
                'header@app.auth': {},
                'content@app.auth': {
                    templateUrl: 'app/views/auth/signupVenue/signupVenueiFrame.html',
                    controller: 'AuthSignupVenueCtrl'
                },
                'signupform@app.auth.signupVenue.iframe': {
                    templateUrl: 'app/views/auth/signupVenue/signupVenueForm.html'
                },
                'footer@app.auth': {}
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
            bodyClass: 'auth login',
            title: 'Login',
            url: '/login',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/login/login.html'
                },
                'loginform@app.auth.login': {
                    templateUrl: 'app/views/auth/login/loginForm.html',
                    controller: 'AuthLoginCtrl'
                }
            }/*,
            resolve: {
                $q: '$q',
                $rootScope: '$rootScope',
                AUTH_EVENTS: 'AUTH_EVENTS',
                alreadyLoggedIn: function(initUser, $rootScope, AUTH_EVENTS, $q, AuthService) {
                    return $q(function(resolve, reject) {  
                        if(AuthService.getUser()) {
                            $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                    });
                }
            }*/
        });
        
        $stateProvider.state('app.auth.login.iframe', {
            bodyClass: 'auth login iframe-compatible',
            title: 'Login',
            url: '/iframe',
            views: {
                'header@app.auth': {},
                'content@app.auth': {
                    templateUrl: 'app/views/auth/login/loginiFrame.html'
                },
                'loginform@app.auth.login.iframe': {
                    templateUrl: 'app/views/auth/login/loginForm.html',
                    controller: 'AuthLoginCtrl'
                },
                'footer@app.auth': {}
            },
            resolve: {
                $q: '$q',
                $rootScope: '$rootScope',
                $state: '$state'
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
            bodyClass: 'auth logout',
            url: '/logout'
        });

        $stateProvider.state('app.auth.resetPassword', {
            title: 'Reset Password',
            url: '/reset_password/:usertoken',
            views: {
                'content@app.auth': {
                    templateUrl: 'app/views/auth/resetPassword/resetPassword.html',
                    controller: 'ResetPasswordCtrl'
                },
                'signupform@app.auth.resetPassword': {
                    templateUrl: 'app/views/auth/resetPassword/resetPasswordForm.html'
                }
            },
            resolve: {
                $q: '$q',
                $rootScope: '$rootScope',
                $state: '$state'
            }
        });

    }]);