'use strict';

/* 
 * API Routes for Product Categories
 * 
 * API calls related to store product categories.
 */

angular.module('apiRoutes.productCategories', [])
.factory('ApiRoutesProductCategories', ['ApiService', '$q', function (API, $q) {
        
    var api = {};

    api.getCategory = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product category please check your parameters and try again.');
        }
        return API.get('store/category/get/' + id, 'Could not get product category.');
    };
    
    api.newCategory = function(category) {
        if(angular.isUndefined(category.category) || angular.isUndefined(category.desc)) {
            return API.reject('Invalid product category please check your parameters and try again.');
        }
        return API.post('store/category/insert/', category, 'System unable to create new product category.');
    };

    api.saveCategory = function(category) {
        if(angular.isUndefined(category.id) || angular.isUndefined(category.category) || angular.isUndefined(category.desc)) {
            return API.reject('Invalid product category please check your parameters and try again.');
        }

        return API.post('store/category/update/' + category.id, category, 'System unable to save product category.');
    };

    api.deleteCategory = function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product category please check your parameters and try again.');
        }
        return API.delete('store/category/delete/' + id, 'System unable to delete product category.');
    };
    
    return api;
}]);