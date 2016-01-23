'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.tagVisibility', [])
    .controller('AdminTagVisibilityCtrl', ['$scope', 'DataTableHelper', 'DTColumnBuilder',
        function($scope, DataTableHelper, DTColumnBuilder) {

            // Init variables
            $scope.editing = false;

            // DataTable Setup
            $scope.dtTags = DataTableHelper.getDTStructure($scope, 'adminTagElementsList');
            $scope.dtTags.columns = [
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('identifier').withTitle('Identifier'),
                DTColumnBuilder.newColumn('type').withTitle('Type'),
                DTColumnBuilder.newColumn('desc').withTitle('Description'),
                DTColumnBuilder.newColumn('initialized').withTitle('Initialized').renderWith(function(data, type, full, meta) {
                    return (data === null) ?
                            '<span class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>' :
                            '<span class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>';
                }),
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