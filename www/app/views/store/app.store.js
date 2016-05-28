'use strict';

/* 
 * Store Pages Module
 * 
 * Include controllers and other modules required on store and cart pages.
 */

angular.module('app.store', [
    'app.store.cart',
    'app.store.category',
    'app.store.item',
    'app.store.storeHome',
    'app.store.subheader'
]);