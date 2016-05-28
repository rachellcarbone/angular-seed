'use strict';

/* 
 * Layout Header: Admin
 * 
 * Used to control the admin layout header and top navigtion.
 */

angular.module('app.elements.adminHeader', [])
        .controller('AdminHeaderCtrl', ['$scope', 'UserSession', 'AuthService', 'siteSystemVariables', 
        function ($scope, UserSession, AuthService, siteSystemVariables) {
        
        /* Site configuration variables are pre loaded within the 
         * state resolve method in the app.router */
        $scope.siteOptions = siteSystemVariables;
        
        /* User display name for logged in indicator. */
        $scope.userDisplayName = UserSession.displayName();

        /* Logout function in the auth service. */
        $scope.logout = AuthService.logout;
        
        /* ui.bootstrap navbar collapse flag. */
        $scope.navbarCollapsed = true;
        
        /* ui.bootstrap logged in user menu drop down. */
        $scope.userNavDropdownIsOpen = false;
        
        /* ui.bootstrap authentication menu drop down. */
        $scope.authNavDropdownIsOpen = false;
        
        /* Trigger the outtermost navbar to collapse (the hamburger button)
         * when a link is selected. */
        $(".navbar-nav li.trigger-collapse a").click(function (event) {
            $scope.navbarCollapsed = true;
        });
    }]);