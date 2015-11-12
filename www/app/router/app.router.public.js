'use strict';

/*
 * State Declarations: Public / No Authentication
 * 
 * Set up the states for public routes, such as the landing 
 * page and other un authenticated states.
 * Ueses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.public', [
    'rachels.auth.constants',
    'app.public'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Public (Un Authenticated) Route */
        $stateProvider.state('public', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@public': {
                    templateUrl: 'app/views/public/publicHeader/publicHeader.html',
                    controller: 'PublicHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/public/publicLayout/publicLayout.html',
                    controller: 'PublicLayoutCtrl'
                },
                'footer@public': {
                    templateUrl: 'app/views/public/publicFooter/publicFooter.html',
                    controller: 'PublicFooterCtrl'
                }
            }
        });

        $stateProvider.state('public.landing', {
            title: 'Welcome',
            url: '/',
            views: {
                'content@public': {
                    templateUrl: 'app/views/public/landing/landing.html',
                    controller: 'PublicLandingCtrl'
                }
            }
        });

        $stateProvider.state('public.about', {
            title: 'About Us',
            url: '/about',
            views: {
                'content@public': {
                    templateUrl: 'app/views/public/about/about.html',
                    controller: 'PublicAboutCtrl'
                }
            }
        });

        $stateProvider.state('public.tour', {
            title: 'Tour',
            url: '/tour',
            views: {
                'content@public': {
                    templateUrl: 'app/views/public/tour/tour.html',
                    controller: 'PublicTourCtrl'
                }
            }
        });

        $stateProvider.state('public.contact', {
            title: 'Contact Us',
            url: '/contact',
            views: {
                'content@public': {
                    templateUrl: 'app/views/public/contact/contact.html',
                    controller: 'PublicContactCtrl'
                }
            }
        });
        
    }]);