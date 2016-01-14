'use strict';

/* 
 * Layout Header: Public
 * 
 * Used to control the public layout header and top navigtion.
 */

angular.module('app.public.header', [])
        .controller('PublicHeaderCtrl', ['$scope', 'UserSession', function ($scope, UserSession) {
        
        $scope.user = UserSession.get();

        $scope.userIsLoggedIn = false;

    }]);