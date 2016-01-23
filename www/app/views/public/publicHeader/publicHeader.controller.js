'use strict';

/* 
 * Layout Header: Public
 * 
 * Used to control the public layout header and top navigtion.
 */

angular.module('app.public.header', [])
        .controller('PublicHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 
        function ($scope, UserSession, AuthService) {
        
        /* User display name for logged in indicator */
        $scope.userDisplayName = UserSession.displayName();

        /* Logout function in the auth service */
        $scope.logout = AuthService.logout;

    }]);