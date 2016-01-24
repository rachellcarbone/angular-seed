'use strict';

/* 
 * Layout Footer: Auth
 * 
 * Used to control the auth layout footer.
 */

angular.module('app.auth.footer', [])
    .controller('AuthFooterCtrl', ['$scope', 'siteSystemVariables', 
        function ($scope, siteSystemVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteSystemVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
        
    }]);