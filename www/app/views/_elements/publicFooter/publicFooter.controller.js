'use strict';

/* 
 * Layout Footer: Public
 * 
 * Used to control the public layout footer.
 */

angular.module('app.elements.publicFooter', ['angularMoment'])
    .controller('PublicFooterCtrl', ['$scope', 'siteSystemVariables', 
    function ($scope, siteSystemVariables) {

    //* Get site configuration variables
    $scope.siteOptions = siteSystemVariables;

    //* Year for copyright display
    $scope.currentYear = moment().year();

}]);