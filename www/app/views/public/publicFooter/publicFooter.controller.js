'use strict';

/* 
 * Layout Footer: Public
 * 
 * Used to control the public layout footer.
 */

angular.module('app.public.footer', ['angularMoment'])
        .controller('PublicFooterCtrl', ['$scope', function ($scope) {
        
        $scope.currentYear = moment().year();
        
    }]);