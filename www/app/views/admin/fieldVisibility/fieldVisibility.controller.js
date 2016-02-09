'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.fieldVisibility', [])
    .controller('AdminFieldVisibilityCtrl', ['$scope', '$compile', '$filter', 'DataTableHelper', 'DTOptionsBuilder', 'DTColumnBuilder', 'ModalService',
        function($scope, $compile, $filter, DataTableHelper, DTOptionsBuilder, DTColumnBuilder, ModalService) {

            /* Modal triggers */
            // Edit Visibility Field Modal
            $scope.buttonOpenEditVisibilityFieldModal = function (id) {
                var found = $filter('filter')($scope.dtFields.instance.DataTable.data(), {id: id}, true);
                if(angular.isDefined(found[0])) {
                    var modalInstance = ModalService.openEditVisibilityField(found[0]);
                    modalInstance.result.then(function (selectedItem) {
                        $scope.dtFields.reloadData();
                    }, function () {});
                }
            };
            
            // New Group Modal
            $scope.buttonOpenNewGroupModal = ModalService.openEditGroup;
            
            // New Role Modal
            $scope.buttonOpenNewRoleModal = ModalService.openEditRole;
            
            // New Visibility Field Modal
            $scope.buttonOpenNewVisibilityFieldModal = function () {
                var modalInstance = ModalService.openEditVisibilityField();
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtFields.reloadData();
                }, function () {});
            };

            // DataTable Setup
            $scope.dtFieldRoles = {};
            $scope.dtFieldRoles.options = DTOptionsBuilder.newOptions();
                    
            $scope.dtFields = DataTableHelper.getDTStructure($scope, 'adminVisibilityFieldList');
            $scope.dtFields.options.withOption('responsive', {
                details: {
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var data = {};
                        angular.forEach(columns, function (value, key) {
                            if(value.title === 'getData') {
                                data = value.data;
                            }
                        });

                        var addButton = '<button ng-click="openEditRoleModal()" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Role</button>';
                        var header = '<table datatable="" dt-options="dtFieldRoles.options" class="table table-hover sub-table">\n\
                            <thead><tr>\n\
                            <td>ID</td>\n\
                            <td>Role</td>\n\
                            <td>Description' + addButton +'</td>\n\
                            </tr></thead><tbody>';

                        var body = '';
                        $.each(data.roles, function(index, value) {
                            body += '<tr>' +
                                '<td>' + value['id'] + '</td> ' +
                                '<td>' + value['role'] + '</td> ' +
                                '<td>' + value['desc'] + '</td> ' +
                                '</tr>';
                        });

                        // Create angular table element
                        body = (body) ? body : '<tr><td colspan="3"><p>No roles have been assiciated with this element.</p></td></tr>';

                        var html = header + body + '</tbody></table>';

                        var table = angular.element(html);

                        // compile the table to keep the directives (ngClick)
                        $compile(table.contents())($scope);

                        return table;
                    }
                }
            });
            
            $scope.dtFields.columns = [
                DTColumnBuilder.newColumn(null).withTitle('Roles').withClass('responsive-control text-right noclick').renderWith(function(data, type, full, meta) {
                    return '<a><small>(' + data.roles.length +')</small> <i class="fa"></i></a>';
                }).notSortable(),
                DTColumnBuilder.newColumn('id').withTitle('ID'),
                DTColumnBuilder.newColumn('identifier').withTitle('Identifier'),
                DTColumnBuilder.newColumn('type').withTitle('Type'),
                DTColumnBuilder.newColumn('desc').withTitle('Description'),
                DTColumnBuilder.newColumn('initialized').withTitle('Initialized').withClass('text-center').renderWith(function(data, type, full, meta) {
                    return (data === null) ?
                            '<span class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>' :
                            '<span class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>';
                }),
                DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On').renderWith(function (data, type, full, meta) {
                    return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
                }),
                DTColumnBuilder.newColumn(null).withTitle('').withClass('text-center noclick').renderWith(function (data, type, full, meta) {
                    return '<button type="button" ng-click="buttonOpenEditVisibilityFieldModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">View</button>';
                }).notSortable(),
                DTColumnBuilder.newColumn(null).withTitle('getData').withClass('none').notSortable()
            ];
        
    }]);