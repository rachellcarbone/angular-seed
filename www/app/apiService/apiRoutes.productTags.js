'use strict';

/* 
 * API Routes for Product Tags
 * 
 * API calls related to store product tags.
 */

angular.module('apiRoutes.productTags', [])
.factory('ApiRoutesProductTags', ['ApiService', '$q', function (API, $q) {
        
    var api = {};

    api.getTag= function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product tag please check your parameters and try again.');
        }
        return API.get('store/tag/get/' + id, 'Could not get product tag.');
    };
    
    api.newTag= function(tag) {
        if(angular.isUndefined(tag.tag) || angular.isUndefined(tag.desc)) {
            return API.reject('Invalid product tag please check your parameters and try again.');
        }
        return API.post('store/tag/insert/', tag, 'System unable to create new product tag.');
    };

    api.saveTag= function(tag) {
        if(angular.isUndefined(tag.id) || angular.isUndefined(tag.tag) || angular.isUndefined(tag.desc)) {
            return API.reject('Invalid product tag please check your parameters and try again.');
        }

        return API.post('store/tag/update/' + tag.id, tag, 'System unable to save product tag.');
    };

    api.deleteTag= function(id) {
        if(angular.isUndefined(id)) {
            return API.reject('Invalid product tag please check your parameters and try again.');
        }
        return API.delete('store/tag/delete/' + id, 'System unable to delete product tag.');
    };
    
    return api;
}]);