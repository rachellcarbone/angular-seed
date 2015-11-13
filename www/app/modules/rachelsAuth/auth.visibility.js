'use strict';

/* 
 * Auth Role Visibility Service
 * 
 * Methods to check if a authorized role (of a state or element on a page) is 
 * either unauthenticated (publicly accessable) or that a users role has 
 * permissions to view that element.
 */

angular.module('rachels.auth.visibility', [])
    .factory('VisibilityService', ['USER_ROLES', function(USER_ROLES) {
        
        var service = {};
        
        /* 
         * Is Role Accissable Unauthenticated 
         * 
         * Checks a role (presumably for $state access or to see if a user can view
         * a page or menu item etc) to see if it is publicly accessable to everyone
         * including unauthenticated (not logged in) users.
         * 
         * @param {Object} authorizedRole Role to be checked
         * @return {Boolean} Returns true if the role is for unauthenticated  traffic
         */
        service.isAccessUnauthenticated = function(authorizedRole) {
            return true;//(authorizedRole === USER_ROLES.guest);
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
        service.isVisibleToUser = function(authorizedRole, userRole) {
            return (authorizedRole <= userRole);
        };
        
        return service;
        
    }]);
