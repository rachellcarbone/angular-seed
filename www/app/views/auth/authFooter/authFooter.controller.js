'use strict';

/* 
 * Layout Footer: Auth
 * 
 * Used to control the auth layout footer.
 */

angular.module('app.auth.footer', [])
    .controller('AuthFooterCtrl', ['$scope', 'siteConfigVariables', 
        function ($scope, siteConfigVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteConfigVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
        
    }]);