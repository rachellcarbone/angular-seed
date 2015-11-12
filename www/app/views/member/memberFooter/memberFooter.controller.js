'use strict';

/* 
 * Layout Footer: Admin
 * 
 * Used to control the member layout footer.
 */

angular.module('app.member.footer', [])
        .controller('MemberFooterCtrl', ['$scope', function ($scope) {
        
                $scope.currentYear = moment().year();
        
    }]);