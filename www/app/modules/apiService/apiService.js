'use strict';

/* 
 * API V1 $http Service
 * 
 * Centeral http methods for v1 of the api.
 */

angular.module('api.v1', [
    'apiRoutes.auth',
    'apiRoutes.datatables'
])
.factory('ApiService', ['$http', '$q', '$log', function($http, $q, $log) {

    var api = {};
    
    // API URL Base
    api.apiUrl = 'http://api.seed.dev/';
    
    // Default Error Message 
    // Used when no other message is available.
    api.defaultErr = 'An error occured communicating with the API.';
    
    
    // Trim the backslash "/" from the beginning of the path if it exists
    var getApiPath = function(path) {
        // If the first character is a backslash remove it
        // and return the trimmed path
        var trimmed = (path.charAt(0) === "/") ? path.substr(1) : path;
        return api.apiUrl + trimmed;
    };
    
    var getErrorMessage = function(message, defaultMessage) {
        defaultMessage = defaultMessage | api.defaultErr;
        // If no error message was sent use the default error message
        return (message) ? message : defaultMessage;
    };
    
    /* Shortcut to create a promise that fails with a provided error message.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.reject = function(err) {
        return $q(function (resolve, reject) {
            reject(err); 
        });
    };
    
    /* Make a GET request to the API.
     * @param {string} path Path to api request.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.get = function(path, err) {
        // Return a promise
        return $q(function (resolve, reject) {
            
            // Make the GET request to the api and clean path
            $http.get(getApiPath(path))
            .success(function (data) {
                // If its successful, resolve the promise
                resolve(data.data);
            }).error(function(data) {
                // If there eas an error log it
                $log.error(getErrorMessage(data.data.msg, err));
                // Reject the promise
                reject(getErrorMessage(data.data.msg, err)); 
            });
            
        });
    };
   
    /* Make a POST request to the API.
     * @param {string} path Path to api request.
     * @param {Object} data.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.post = function(path, data, err) {
        // Return a promise
        return $q(function (resolve, reject) {
            $http({
                method: 'POST',
                url: getApiPath(path),
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function (data) {
                    // If its successful, resolve the promise
                    resolve(data.data);
                })
                .error(function(data) {
                    // If there eas an error log it
                    $log.error(getErrorMessage(data.data.msg, err));
                    // Reject the promise
                    reject(getErrorMessage(data.data.msg, err)); 
            });            
        });
    };
    
    /* Make a DELETE request to the API.
     * @param {string} path Path to api request.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.delete = function(path, err) {
        // Return a promise
        return $q(function (resolve, reject) {            
            $http.delete(getApiPath(path))
            .success(function (data) {
                    // If its successful, resolve the promise
                    resolve(data.data);
                })
                .error(function(data) {
                    // If there eas an error log it
                    $log.error(getErrorMessage(data.data.msg, err));
                    // Reject the promise
                    reject(getErrorMessage(data.data.msg, err)); 
            });
        });
    };
    
    return api;
}]);