'use strict';

/* 
 * Auth Role Visibility Service
 * 
 * Methods to check if a authorized role (of a state or element on a page) is 
 * either unauthenticated (publicly accessable) or that a users role has 
 * permissions to view that element.
 */

angular.module('rcAuth.visibility', [])
    .factory('VisibilityService', ['USER_ROLES', function(USER_ROLES) {
        
        var service = {};
        
        // Helper to check for items of an array in another array
        var containsAny  = function (haystack, arr) {
            return arr.some(function (v) {
                return haystack.indexOf(v) >= 0;
            });
        };
        
        /* 
         * Is Role Accissable Unauthenticated 
         * 
         * Checks a role (presumably for $state access or to see if a user can view
         * a page or menu item etc) to see if it is publicly accessable to everyone
         * including unauthenticated (not logged in) users.
         * 
         * @param {Array|String} authorized Role to be checked
         * @return {Boolean} Returns true if the role is for unauthenticated  traffic
         */
        service.isAccessUnauthenticated = function(access) {
            var guest = (angular.isArray(USER_ROLES.guest)) ? USER_ROLES.guest : [USER_ROLES.guest];
            var authorized = (angular.isArray(access)) ? access : [access];
            
            // If the roles are in an array
            return (containsAny(authorized, guest));
        };
        
        /* 
         * Is Role Visible To a Users Role
         * 
         * Checks a role (presumably for $state access or to see if a user can view
         * a page or menu item etc) to see if it is visible to the role of a user.
         * 
         * @param {Object} authorizedRole Role of the object / page / element 
         * @param {Object} userRole Role associated with a user, possibly guest/unauthenticated
         * @return {Boolean} Returns true if the userRole has access to the authorizedRole
         */
        service.isVisibleToUser = function(access, userRoles) {
            var user = (angular.isArray(userRoles)) ? userRoles : [userRoles];
            var authorized = (angular.isArray(access)) ? access : [access];
            
            // If the roles are in an array
            return (containsAny(authorized, user));
        };
        
        return service;
        
    }]);
