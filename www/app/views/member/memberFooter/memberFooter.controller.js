'use strict';

/* 
 * Layout Footer: Admin
 * 
 * Used to control the member layout footer.
 */

angular.module('app.member.footer', [])
        .controller('MemberFooterCtrl', ['$scope', 'siteConfigVariables', 
        function ($scope, siteConfigVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteConfigVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
        
    }]);