'use strict';

/* 
 * Layout Footer: Admin
 * 
 * Used to control the admin layout footer.
 */

angular.module('app.admin.footer', [])
        .controller('AdminFooterCtrl', ['$scope', 'siteConfigVariables', 
        function ($scope, siteConfigVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteConfigVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
    }]);