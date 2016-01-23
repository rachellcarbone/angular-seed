'use strict';

/* 
 * Layout Header: Admin
 * 
 * Used to control the admin layout header and top navigtion.
 */

angular.module('app.admin.header', [])
        .controller('AdminHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 'siteConfigVariables', 
        function ($scope, UserSession, AuthService, siteConfigVariables) {
        
        //* Site configuration variables pre loaded by the resolve
        $scope.siteOptions = siteConfigVariables;
        
        //* User display name for logged in indicator
        $scope.userDisplayName = UserSession.displayName();

        //* Logout function in the auth service
        $scope.logout = AuthService.logout;
        
        //* ui.bootstrap navbar
        $scope.navbarCollapsed = true;
        
        //* ui.bootstrap logged in user menu drop down
        $scope.userNavDropdownIsOpen = false;
        
        //* ui.bootstrap authentication menu drop down
        $scope.authNavDropdownIsOpen = false;
        
    }]);