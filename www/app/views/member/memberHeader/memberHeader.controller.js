'use strict';

/* 
 * Layout Header: Member
 * 
 * Used to control the member layout header and top navigtion.
 */

angular.module('app.member.header', [])
        .controller('MemberHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 
        function ($scope, UserSession, AuthService) {
        
        /* User display name for logged in indicator */
        $scope.userDisplayName = UserSession.displayName();

        /* Logout function in the auth service */
        $scope.logout = AuthService.logout;
        
    }]);