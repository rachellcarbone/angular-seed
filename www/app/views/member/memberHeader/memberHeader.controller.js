'use strict';

/* 
 * Layout Header: Member
 * 
 * Used to control the member layout header and top navigtion.
 */

angular.module('app.member.header', [])
        .controller('MemberHeaderCtrl', ['$scope', 'UserSession', function ($scope, UserSession) {
        
        $scope.user = UserSession.get();
    }]);