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
            $scope.dtInstance = {};
            $scope.dtOptions = DTOptionsBuilder.fromFnPromise(ApiRoutesDatatables.adminConfigList())
                .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-4 col-md-4"l><"col-sm-4 col-md-4"i><"col-sm-4 col-md-4"p>>')
                .withPaginationType('full_numbers')
                .withOption('createdRow', function (row, data, dataIndex) {
                    // Recompiling so we can bind Angular directive to the DT
                    $compile(angular.element(row).contents())($scope);
                    // Add this row to the variable list for editing
                    $scope.list[data.id] = data;
                });
            $scope.dtColumns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('name').withTitle('Name'),
                DTColumnBuilder.newColumn('value').withTitle('Value'),
                DTColumnBuilder.newColumn('last_updated_by').withTitle('Last Update'),
                DTColumnBuilder.newColumn('last_updated_ts').withTitle('Updated On'),
                DTColumnBuilder.newColumn(null).withTitle('Edit').renderWith(function (data, type, full, meta) {
                    return '<button type="button" ng-click="openEditModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
                }).notSortable()
            ];
        
    }]);