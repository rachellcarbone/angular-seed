'use strict';

/* 
 * Shopping Cart Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcCart.store', [])
    .factory('rcCartStore', ['$log', function($log) {
        var self = this;
        var store = {};
        
        return store;
    }]);