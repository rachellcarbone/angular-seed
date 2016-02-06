'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.systemVariables', [])
    .controller('AdminSystemVariablesCtrl', ['$scope', '$filter', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, $filter, DataTableHelper, DTColumnBuilder, ModalService) {

            /* Modal triggers */
            $scope.buttonOpenNewVariableModal = function () {
                ModalService.openSystemVariable();
            };
            
            $scope.buttonOpenEditVariableModal = function (id) {
                var found = $filter('filter')($scope.dtSystemVars.instance.DataTable.data(), {id: id}, true);
                if(angular.isDefined(found[0])) {
                    ModalService.openSystemVariable(found[0]);
                } else {
                    console.log(found);
                    console.log('Couldnt find value');
                }
            };
        
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
                    return '<button type="button" ng-click="buttonOpenEditVariableModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
                }).notSortable()
            ];
        
    }]);