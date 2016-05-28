'use strict';

/* 
 * Layout Header: Store
 * 
 * Used to control the store sub layout header and store navigtion.
 */

angular.module('app.elements.storeSubHeader', [])
        .controller('StoreSubHeaderCtrl', ['$scope', function ($scope) {
                
        $scope.categories = ['Mens', 'Womens', 'Boys', 'Girls', 'Accessories'];
    }]);