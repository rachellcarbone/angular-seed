'use strict';

/* 
 * Layout Footer: Admin
 * 
 * Used to control the admin layout footer.
 */

angular.module('app.admin.footer', [])
        .controller('AdminFooterCtrl', ['$scope', 'siteSystemVariables', 
        function ($scope, siteSystemVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteSystemVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
    }]);