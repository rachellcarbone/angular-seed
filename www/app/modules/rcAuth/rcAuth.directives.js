'use strict';

/* 
 * rcAuthVisibleIf
 * 
 * Checks to see if the logged in user has permissions to view a DOM object,
 * (whatever html that is given the auth directive), and if they do not, the
 * directive removes its element from the DOM.
 * 
 * @author  Rachel L Carbone
 * 
 * @param rcAuthVisibleIf string reference to a rcAuth constant key from USER_ROLES
 * 
 * <div rc-auth-visible-if="user"></div>
 */

angular.module('rcAuth.directives', [])
.directive('rcAuthVisibleIf', ['AuthService', 'USER_ROLES', function(AuthService, USER_ROLES) {
    return {
        restrict: 'A',          // Must be a attribute on a html tag
        scope: {
            rcAuthVisibleIf: '@rcAuthVisibleIf'    // string role
        },
        link: function ($scope, element, attributes) {
            
            AuthService.isAuthorized(USER_ROLES[attributes.rcAuthVisibleIf]).then(function (results) {
                // Do nothing - the user is authorized to view this element
            }, function (results) {
                element.remove();
            });
        }
    };
}]);