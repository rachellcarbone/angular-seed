'use strict';

/* 
 * Layout Header: Admin
 * 
 * Used to control the admin layout header and top navigtion.
 */

angular.module('app.admin.header', [])
        .controller('AdminHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 
        function ($scope, UserSession, AuthService) {
        
        /* User display name for logged in indicator */
        $scope.userDisplayName = UserSession.displayName();

        /* Logout function in the auth service */
        $scope.logout = AuthService.logout;
        
    }]);