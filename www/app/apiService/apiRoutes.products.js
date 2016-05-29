'use strict';

/* 
 * API Routes for Products
 * 
 * API calls related to store product products.
 */

angular.module('apiRoutes.products', [])
.factory('ApiRoutesProducts', ['ApiService', '$q', function (API, $q) {
        
    var api = {};

    api.getProduct = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product product please check your parameters and try again.');
        }
        return API.get('store/product get/' + id, 'Could not get product product.');
    };
    
    api.newProduct = function(product) {
        if(angular.isUndefined(product.product) || angular.isUndefined(product.desc)) {
            return API.reject('Invalid product product please check your parameters and try again.');
        }
        return API.post('store/product insert/', product, 'System unable to create new product product.');
    };

    api.saveProduct = function(product) {
        if(angular.isUndefined(product.id) || angular.isUndefined(product.product) || angular.isUndefined(product.desc)) {
            return API.reject('Invalid product product please check your parameters and try again.');
        }

        return API.post('store/product update/' + product.id, product, 'System unable to save product product.');
    };

    api.deleteProduct = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product product please check your parameters and try again.');
        }
        return API.delete('store/product delete/' + id, 'System unable to delete product product.');
    };
    
    return api;
}]);