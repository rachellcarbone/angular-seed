'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.configVariables', [])
    .controller('AdminConfigVariablesCtrl', ['$scope', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, DataTableHelper, DTColumnBuilder, ModalService) {

            /* Modal triggers */
            $scope.openNewVariableModal = function () {
                ModalService.openConfigVariable(false);
            };
            $scope.openEditVariableModal = ModalService.openConfigVariable;
        
            // Init variables
            $scope.editing = false;

            // DataTable Setup
            $scope.dtSystemVars = DataTableHelper.getDTStructure($scope, 'adminConfigList');
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
                    return '<button type="button" ng-click="openEditVariableModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
                }).notSortable()
            ];
        
    }]);