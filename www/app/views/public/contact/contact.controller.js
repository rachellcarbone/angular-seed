'use strict';

/* 
 * Public Contact Page
 * 
 * Contact Form.
 */

angular.module('app.public.contact', [])
    .controller('PublicContactCtrl', ['$scope', '$state', function($scope, $state) {
        
        $scope.$state = $state;
        
    }]);