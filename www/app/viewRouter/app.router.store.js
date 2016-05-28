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
    'rcAuth.constants',
    'app.public',
    'app.store'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {
        
        /* Store Pages */

        /*  Abstract Store Route */
        $stateProvider.state('app.store', {
            url: '/store',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@app': {
                    templateUrl: 'app/views/public/publicHeader/publicHeader.html',
                    controller: 'PublicHeaderCtrl'
                },
                'subheader@app': {
                    templateUrl: 'app/views/store/storeSubHeader/storeSubHeader.html',
                    controller: 'StoreSubHeaderCtrl'
                },
                'footer@app': {
                    templateUrl: 'app/views/public/publicFooter/publicFooter.html',
                    controller: 'PublicFooterCtrl'
                }
            }
        });
        
        $stateProvider.state('app.store.home', {
            title: 'Store Home',
            url: '',
            views: {
                'content@app': {
                    templateUrl: 'app/views/store/storeHome/storeHome.html',
                    controller: 'StoreHomeCtrl'
                }
            }
        });
        
        $stateProvider.state('app.store.cart', {
            title: 'Shopping Cart',
            url: '/cart',
            views: {
                'content@app': {
                    templateUrl: 'app/views/store/cart/cart.html',
                    controller: 'CartCtrl'
                }
            }
        });
        
        $stateProvider.state('app.store.category', {
            title: 'Store Category',
            url: '/:category',
            views: {
                'content@app': {
                    templateUrl: 'app/views/store/category/category.html',
                    controller: 'StoreCategoryCtrl'
                }
            }
        });
        
        $stateProvider.state('app.store.item', {
            title: 'Store Item Details',
            url: '/:category/:itemId',
            views: {
                'content@app': {
                    templateUrl: 'app/views/store/item/item.html',
                    controller: 'ItemDetailCtrl'
                }
            }
        });
        
    }]);