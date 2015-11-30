'use strict';

/*
 * State Declarations: Store
 * 
 * Set up the states for public routes, such as the landing 
 * page and other un authenticated states.
 * Ueses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.store', [
    'rachels.auth.constants',
    'app.public',
    'app.store'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {
        
        /* Store Pages */

        /*  Abstract Store Route */
        $stateProvider.state('store', {
            url: '/store',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@store': {
                    templateUrl: 'app/views/public/publicHeader/publicHeader.html',
                    controller: 'PublicHeaderCtrl'
                },
                'subheader@store': {
                    templateUrl: 'app/views/store/storeSubHeader/storeSubHeader.html',
                    controller: 'StoreSubHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/store/storeLayout/storeLayout.html',
                    controller: 'StoreLayoutCtrl'
                },
                'footer@store': {
                    templateUrl: 'app/views/public/publicFooter/publicFooter.html',
                    controller: 'PublicFooterCtrl'
                }
            }
        });
        
        $stateProvider.state('store.home', {
            title: 'Store Home',
            url: '',
            views: {
                'content@store': {
                    templateUrl: 'app/views/store/storeHome/storeHome.html',
                    controller: 'StoreHomeCtrl'
                }
            }
        });
        
        $stateProvider.state('store.cart', {
            title: 'Shopping Cart',
            url: '/cart',
            views: {
                'content@store': {
                    templateUrl: 'app/views/store/cart/cart.html',
                    controller: 'CartCtrl'
                }
            }
        });
        
        $stateProvider.state('store.category', {
            title: 'Store Category',
            url: '/:category',
            views: {
                'content@store': {
                    templateUrl: 'app/views/store/item/item.html',
                    controller: 'StoreCategoryCtrl'
                }
            }
        });
        
        $stateProvider.state('store.item', {
            title: 'Store Item Details',
            url: '/:category/:itemId',
            views: {
                'content@store': {
                    templateUrl: 'app/views/store/item/item.html',
                    controller: 'ItemDetailCtrl'
                }
            }
        });
        
    }]);