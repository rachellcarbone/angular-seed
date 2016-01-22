'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.configVariables', [])
    .controller('AdminConfigVariablesCtrl', ['$scope', '$compile', 'DTOptionsBuilder', 'DTColumnBuilder', 'ApiRoutesDatatables', 
        function($scope, $compile, DTOptionsBuilder, DTColumnBuilder, ApiRoutesDatatables) {

            // Init variables
            $scope.editing = false;
            $scope.list = {};

            // DataTable Setup
            $scope.dtSystemVars = {};
            $scope.dtSystemVars.instance = {};
            $scope.dtSystemVars.options = DTOptionsBuilder.fromFnPromise(ApiRoutesDatatables.adminConfigList())
                .withBootstrap()
                .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-4 col-md-4"l><"col-sm-4 col-md-4"i><"col-sm-4 col-md-4"p>>')
                .withPaginationType('full_numbers')
                .withOption('createdRow', function (row, data, dataIndex) {
                    // Recompiling so we can bind Angular directive to the DT
                    $compile(angular.element(row).contents())($scope);
                    // Add this row to the variable list for editing
                    $scope.list[data.id] = data;
                });
            $scope.dtSystemVars.columns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('name').withTitle('Name'),
                DTColumnBuilder.newColumn('value').withTitle('Value'),
                DTColumnBuilder.newColumn('createdBy').withTitle('Created By'),
                DTColumnBuilder.newColumn('created').withTitle('Created').renderWith(function (data, type, full, meta) {
                    return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
                }),
                DTColumnBuilder.newColumn('updatedBy').withTitle('Last Update'),
                DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On').renderWith(function (data, type, full, meta) {
                    return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
                }),
                DTColumnBuilder.newColumn(null).withTitle('Edit').renderWith(function (data, type, full, meta) {
                    return '<button type="button" ng-click="openEditModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
                }).notSortable()
            ];
        
    }]);