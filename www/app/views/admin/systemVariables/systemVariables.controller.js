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
                var modalInstance = ModalService.openSystemVariable();
                modalInstance.result.then(function (selectedItem) {
                $scope.dtSystemVars.reloadData();
                }, function () {});
            };
            
            $scope.buttonOpenEditVariableModal = function (id) {
                var found = $filter('filter')($scope.dtSystemVars.instance.DataTable.data(), {id: id}, true);
                if(angular.isDefined(found[0])) {
                    var modalInstance = ModalService.openSystemVariable(found[0]);
                    modalInstance.result.then(function (selectedItem) {
                        $scope.dtSystemVars.reloadData();
                    }, function () {});
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
                DTColumnBuilder.newColumn('disabled').withTitle('Disabled').withClass('text-center').renderWith(function (data, type, full, meta) {
                    return (data === '1') ? '<span class="label label-default" title="Disabled, this variable will not be loaded."><i class="fa fa-lg fa-eye"></i></span>' : 
                            '<span class="label" title="Enabled for use in the system."><i class="fa fa-lg fa-eye-slash"></i></span>';
                }),
                DTColumnBuilder.newColumn('indestructible').withTitle('Indestructible').withClass('text-center').renderWith(function (data, type, full, meta) {
                    return (data === '1') ? '<span class="label label-warning" title="Variable cannot be deleted."><i class="fa fa-lg fa-thumb-tack"></i></span>' : 
                            '<span class="label" title="Variable can be deleted."><i class="fa fa-lg fa-wrench"></i></span>';
                }),
                DTColumnBuilder.newColumn('locked').withTitle('Locked').withClass('text-center').renderWith(function (data, type, full, meta) {
                    return (data === '1') ? '<span class="label label-danger" title="This variable is locked and cannot be changed."><i class="fa fa-lg fa-lock"></i></span>' : 
                            '<span class="label" title="This variable is unlocked and editable."><i class="fa fa-lg fa-unlock"></i></span>';
                }),
                DTColumnBuilder.newColumn('updatedBy').withTitle('Last Update'),
                DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On').renderWith(function (data, type, full, meta) {
                    return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
                }),
                DTColumnBuilder.newColumn(null).withTitle('Edit').withClass('text-center').renderWith(function (data, type, full, meta) {
                    return '<button type="button" ng-click="buttonOpenEditVariableModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
                }).notSortable()
            ];
            
    }]);