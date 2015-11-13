'use strict';

/* 
 * API V1 $http Service
 * 
 * Centeral http methods for v1 of the api.
 */

angular.module('api.v1', [])
    .factory('APIV1Service', ['$http', '$q', '$log', function($http, $q, $log) {

    var api = {};
    
    // API URL Base
    api.apiUrl = '../api/v1/';
    
    // Default Error Message 
    // Used when no other message is available.
    api.defaultErr = 'An error occured communicating with the API.';
    
    /* Shortcut to create a promise that fails with a provided error message.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.reject = function(err) {
        return $q(function (resolve, reject) {
            reject(err); 
        });
    };
    
    // Trim the backslash "/" from the beginning of the path if it exists
    api.trimPath = function(path) {
        // If the first character is a backslash remove it
        // and return the trimmed path
        return (path.charAt(0) === "/") ? path.substr(1) : path;
    };
    
    /* Make a GET request to the API.
     * @param {string} path Path to api request.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.get = function(path, err, requested) {
        // If no error message was sent use the default error message
        err = (typeof(err) === 'undefined') ? api.defaultErr : err;
        
        // Return a promise
        return $q(function (resolve, reject) {
            
            // Make the GET request to the api and clean path
            $http.get(api.apiUrl + api.trimPath(path))
            .success(function (data) {
                // If its successful, resolve the promise
                resolve(data.msg);
            }).error(function(data) {
                // If there eas an error log it
                $log.error((data.msg) ? data.msg : err);
                // Reject the promise
                reject((data.msg) ? data.msg : err); 
            });
            
        });
    };
   
    /* Make a POST request to the API.
     * @param {string} path Path to api request.
     * @param {FormData} fd A form data object.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.post = function(path, fd, err) {
        // If no error message was sent use the default error message
        err = (typeof(err) === 'undefined') ? api.defaultErr : err;
        
        // Return a promise
        return $q(function (resolve, reject) {
            
            // Make the POST request to the api and clean path
            $http.post(api.apiUrl + api.trimPath(path), fd, {
                transformRequest: angular.identity,
                headers: {'Content-Type': undefined}
            }).success(function (data) {
                // If its successful, resolve the promise
                resolve(data.msg);
            }).error(function(data) {
                // If there eas an error log it
                $log.error((data.msg) ? data.msg : err);
                // Reject the promise
                reject((data.msg) ? data.msg : err); 
            });
            
        });
    };
   
    /* Make a POST request to the API in a JSON safe mannor.
     * @param {string} path Path to api request.
     * @param {Object} data A plain javascript object.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.postJson = function(path, data, err) {
        // If no error message was sent use the default error message
        err = (typeof(err) === 'undefined') ? api.defaultErr : err;
        
        // Return a promise
        return $q(function (resolve, reject) {
            
            $http({
                method: 'POST',
                url: api.apiUrl + api.trimPath(path),
                data: $.param(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
            .success(function (data) {
                    // If its successful, resolve the promise
                    resolve(data.msg);
                })
                .error(function(data) {
                    // If there eas an error log it
                    $log.error((data.msg) ? data.msg : err);
                    // Reject the promise
                    reject((data.msg) ? data.msg : err); 
            });            
        });
    };
    
    /* Make a DELETE request to the API.
     * @param {string} path Path to api request.
     * @param {string} err Error message.
     * @return {Object} Returns a promise. */
    api.delete = function(path, err) {
        // If no error message was sent use the default error message
        err = (typeof(err) === 'undefined') ? api.defaultErr : err;
        
        // Return a promise
        return $q(function (resolve, reject) {            
            $http.delete(api.apiUrl + api.trimPath(path))
            .success(function (data) {
                    // If its successful, resolve the promise
                    resolve(data.msg);
                })
                .error(function(data) {
                    // If there eas an error log it
                    $log.error((data.msg) ? data.msg : err);
                    // Reject the promise
                    reject((data.msg) ? data.msg : err); 
            });
        });
    };
    
    return api;
}]);