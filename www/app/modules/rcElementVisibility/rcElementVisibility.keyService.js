'use strict';

/*
 * Element & Field Visibility Module - Visibility Key Managment Service
 * 
 * Manages the key and whether an element is visible on the page.
 */

var app = angular.module('rcElementVisibility.keyService', []);

app.factory('ElementVisibilityKeyService',
    function($cookies, $q, $log, RC_ELEMENT_VISIBILITY_OPTIONS, AuthService, ApiRoutesSystemVisibility) {

        // PRIVATE: Holds auth key array
        var authElementKey = false;

        /*
         * PUBLIC: Load Element Key
         * Gets the element key from the database and saves it to the
         * service cache. Used to init or refresh the list.
         * Returns a promise
         */
        var loadKey = function () {
            return $q(function (resolve, reject) {
                ApiRoutesSystemVisibility.getElementVisibilityKey().then(function (response) {
                    authElementKey = (angular.isArray(response.key)) ? response.key : [];
                    resolve(authElementKey);
                    $log.debug(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Loaded Auth Key');
                }, function (error) {
                    reject("Error loading key.");
                    $log.error(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + error.data);
                });
            });
        };

        /*
         * PUBLIC: Reload Key
         */
        var clearCache = function () {
            return $q(function (resolve, reject) {
                loadKey().then(function (response) {
                    resolve(authElementKey);
                }, function (error) {
                    reject("Error loading key.");
                });
            });
        };

        /*
         * PUBLIC: Load Element Key
         * Returns a promise
         */
        var getCachedKey = function () {
            return $q(function (resolve, reject) {
                if(authElementKey !== false) {
                    resolve(authElementKey);
                } else {
                    loadKey().then(function (response) {
                        resolve(authElementKey);
                    }, function (error) {
                        reject("Error loading key.");
                    });
                }
            });
        };

        /*
         * PRIVATE: Get Element From Key
         * Returns a promise
         */
        var getElementFromKey = function(fieldIdentifier) {
            return $q(function (resolve, reject) {
                // Get element key from cache
                // (may have to make an api call to get it)
                getCachedKey().then(function (response) {

                    var found = false;

                    // Search the chached element key for our element identifier
                    angular.forEach(response, function (value, key) {
                        // Cant break angular foreach, if flags are the best fix
                        if (found === false && fieldIdentifier === value.identifier) {
                            found = value;
                        }
                    });

                    return resolve(found);
                }, function (error) {
                    return reject("Error getting element from key.");
                });
            });
        };

        // Cookie (AKA Edit Mode) Managment


        /*
         * PUBLIC: Enable "Auth Field Level Element Edit Mode"
         * Creates a short cooke that signifies the application is in
         * element edit mode. Cookie expiration is RC_ELEMENT_VISIBILITY_OPTIONS constant.
         */
        var enableEditMode = function() {
            disableEditMode();

            var expireDate = new Date();
            expireDate.setTime(expireDate.getTime() + (RC_ELEMENT_VISIBILITY_OPTIONS.cookieExpInMinutes * 60 * 1000));
            $cookies.put(RC_ELEMENT_VISIBILITY_OPTIONS.cookieEditingFlag, 'true', {'expires': expireDate});

            return true;
        };

        /*
         * PUBLIC: Manually Disable "Auth Field Level Element Edit Mode"
         * Deletes the cookie that represents edit mode for auth elements.
         */
        var disableEditMode = function() {
            $cookies.remove(RC_ELEMENT_VISIBILITY_OPTIONS.cookieEditingFlag);
            return true;
        };

        /*
         * PUBLIC: Is Sit in "Auth Field Level Element Edit Mode"
         * Returns a boolean if the auth element visibility module is in
         * edit mode. Used mostly to init elements and display more robust
         * debugging logs.
         */
        var inEditMode = function() {
            return ($cookies.get(RC_ELEMENT_VISIBILITY_OPTIONS.cookieEditingFlag));
        };


        var isElementVisible = function(roles) {
            return AuthService.isAuthorized(roles);
        };

        var initElement = function(fieldIdentifier) {
            // Prepare asynchronous answer
            return $q(function (resolve, reject) {
                // get initialized element from key
                getElementFromKey(fieldIdentifier).then(function (response) {
                    // If the element wasnt found to be initialized
                    if (response === false) {
                        // And we are NOT in edit mode
                        if (!inEditMode()) {
                            return reject("This element is not initialized.");
                        }

                        // If we are in edit mode then initialize the element
                        ApiRoutesSystemVisibility.initVisibilityElement({ 'fieldIdentifier' : fieldIdentifier }).then(function (response) {
                            authElementKey.push(response.field);
                            if (inEditMode()) {
                                return $log.debug('Initialized ', response.field);
                            }
                            resolve(response.field);
                        }, function (error) {
                            return reject("Error initializing element.");
                        });
                    } else {
                        return resolve(response);
                    }

                }), function (error) {
                    return reject(error);
                };
            });
        };

        // Public Methods

        return {
            getKey: loadKey,               // Returns Promise
            clearCache: clearCache,                // Returns Promise
            enableEditMode: enableEditMode,     // Bool
            disableEditMode: disableEditMode,   // Bool
            inEditMode: inEditMode,           // Bool
            isElementVisible: isElementVisible,
            initElement: initElement
        };

    });
