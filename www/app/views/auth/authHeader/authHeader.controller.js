'use strict';

/* 
 * Layout Header: Auth
 * 
 * Used to control the auth layout header and top navigtion.
 */

angular.module('app.auth.header', [])
        .controller('AuthHeaderCtrl', ['$scope', 'siteSystemVariables',  
        function ($scope, siteSystemVariables) {
        
        //* Site configuration variables pre loaded by the resolve
        $scope.siteOptions = siteSystemVariables;
        
    }]);