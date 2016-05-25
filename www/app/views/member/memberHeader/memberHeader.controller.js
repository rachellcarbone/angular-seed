'use strict';

/* 
 * Layout Header: Member
 * 
 * Used to control the member layout header and top navigtion.
 */

angular.module('app.member.header', [])
        .controller('MemberHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 'siteSystemVariables', 
        function ($scope, UserSession, AuthService, siteSystemVariables) {
        
        //* Site configuration variables pre loaded by the resolve
        $scope.siteOptions = siteSystemVariables;
        
        //* User display name for logged in indicator
        $scope.userDisplayName = UserSession.displayName();

        //* Logout function in the auth service
        $scope.logout = AuthService.logout;
        
        //* ui.bootstrap navbar
        $scope.navbarCollapsed = true;
        
        //* ui.bootstrap logged in user menu drop down
        $scope.userNavDropdownIsOpen = false;
        
        $(".navbar-nav li.trigger-collapse a").click(function (event) {
            $scope.navbarCollapsed = true;
        });
    }]);