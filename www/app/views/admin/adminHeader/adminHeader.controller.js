'use strict';

/* 
 * Layout Header: Admin
 * 
 * Used to control the admin layout header and top navigtion.
 */

angular.module('app.admin.header', [])
        .controller('AdminHeaderCtrl', ['$scope', 'UserSession', function ($scope, UserSession) {
        
        $scope.user = UserSession.get();
    }]);