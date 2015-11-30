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
    'app.public',
    'app.store'
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

        $stateProvider.state('public.contact.confirmation', {
            title: 'Message Sent',
            url: '/message-sent'
        });
        
        /* Site Map */
        $stateProvider.state('public.sitemap', {
            title: 'Site Map',
            url: '/sitemap',
            views: {
                'content@public': {
                    templateUrl: 'app/views/public/sitemap/sitemap.html'
                }
            }
        });
        
        /* Store Pages */
        
        $stateProvider.state('public.store', {
            title: 'Store Home',
            url: '/store',
            views: {
                'content@public': {
                    templateUrl: 'app/views/store/storeHome/storeHome.html',
                    controller: 'StoreHomeCtrl'
                }
            }
        });
        
        $stateProvider.state('public.store.cart', {
            title: 'Shopping Cart',
            url: '/store/cart',
            views: {
                'content@public': {
                    templateUrl: 'app/views/store/cart/cart.html',
                    controller: 'CartCtrl'
                }
            }
        });
        
        $stateProvider.state('public.store.category', {
            title: 'Store Category',
            url: '/store/:category',
            views: {
                'content@public': {
                    templateUrl: 'app/views/store/item/item.html',
                    controller: 'StoreCategoryCtrl'
                }
            }
        });
        
        $stateProvider.state('public.store.item', {
            title: 'Store Item Details',
            url: '/store/:category/:itemNumber',
            views: {
                'content@public': {
                    templateUrl: 'app/views/store/item/item.html',
                    controller: 'ItemDetailCtrl'
                }
            }
        });
        
    }]);