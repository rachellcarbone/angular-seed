'use strict';

/* 
 * Layout Footer: Public
 * 
 * Used to control the public layout footer.
 */

angular.module('app.public.footer', ['angularMoment'])
        .controller('PublicFooterCtrl', ['$scope', 'siteConfigVariables', 
        function ($scope, siteConfigVariables) {
            
        //* Get site configuration variables
        $scope.siteOptions = siteConfigVariables;
        
        //* Year for copyright display
        $scope.currentYear = moment().year();
        
    }]);