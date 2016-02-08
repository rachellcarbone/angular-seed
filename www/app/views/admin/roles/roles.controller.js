'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.roles', [])
    .controller('AdminRolesCtrl', ['$scope', '$compile', '$filter', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, $compile, $filter, DataTableHelper, DTColumnBuilder, ModalService) {

        /* Modal triggers */
            // Edit Role Modal
            $scope.buttonOpenEditRoleModal = function (id) {
                var found = $filter('filter')($scope.dtGroupRoles.instance.DataTable.data(), {id: id}, true);
                if(angular.isDefined(found[0])) {
                    var modalInstance = ModalService.openEditRole(found[0]);
                    modalInstance.result.then(function (selectedItem) {
                        $scope.dtGroupRoles.reloadData();
                    }, function () {});
                }
            };
            
            // New Group Modal
            $scope.buttonOpenNewGroupModal = function () {
                var modalInstance = ModalService.openEditGroup();
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtUserGroups.reloadData();
                }, function () {});
            };
            
            // New Role Modal
            $scope.buttonOpenNewRoleModal = function () {
                var modalInstance = ModalService.openEditRole();
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtUserGroups.reloadData();
                }, function () {});
            };
            
            // New Visibility Field Modal
            $scope.buttonOpenNewVisibilityFieldModal = function () {
                var modalInstance = ModalService.openEditVisibilityField();
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtUserGroups.reloadData();
                }, function () {});
            };
            
        
            // New Group to Role Modal
            $scope.buttonAssignGroupModal = function (id) {
                var modalInstance = ModalService.openAssignGroupRole(id);
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtUsers.reloadData();
                }, function () {});
            };

        // DataTable Setup
        $scope.dtGroupRoles = DataTableHelper.getDTStructure($scope, 'adminRolesList');
        $scope.dtGroupRoles.options.withOption('responsive', {
                details: {
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        // Get the role id
                        var id = 0;
                        var data = new Array();
                        angular.forEach(columns, function (value, key) {
                            if(value.title == 'ID') {
                                id = value.data;
                            }
                            if(value.title == 'Role Groups') {
                                data = value.data;
                            }
                        });

                        var addButton = '<button ng-click="buttonAssignGroupModal(' + id + ')" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Group</button>';
                        var header = '<table datatable="" dt-options="dtRoleGroups.options" class="table table-hover sub-table">\n\
                            <thead><tr>\n\
                            <td>ID</td>\n\
                            <td>Group</td>\n\
                            <td>Description' + addButton + '</td>\n\
                            </tr></thead><tbody>';


                        var body = '';
                        $.each(data, function(index, value) {
                            body += '<tr>' +
                                '<td>' + value['id'] + '</td> ' +
                                '<td>' + value['group'] + '</td> ' +
                                '<td>' + value['desc'] + '</td> ' +
                                '</tr>';
                        });

                        // Create angular table element
                        body = (body) ? body : '<tr><td colspan="3"><p>This role is not associated with any groups.</p></td></tr>';

                        var html = header + body + '</tbody></table>';

                        var table = angular.element(html);

                        // compile the table to keep the directives (ngClick)
                        $compile(table.contents())($scope);

                        return table;
                    }
                }
            });
            
        $scope.dtGroupRoles.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Groups').withClass('responsive-control text-right noclick').renderWith(function(data, type, full, meta) {
                return '<a><small>(' + data.groups.length +')</small> <i class="fa"></i></a>';
            }).notSortable(),
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('role').withTitle('Role'),
            DTColumnBuilder.newColumn('desc').withTitle('Description'),
            DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On').renderWith(function (data, type, full, meta) {
                return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
            }),
            DTColumnBuilder.newColumn(null).withTitle('').withClass('text-center noclick').renderWith(function(data, type, full, meta) {
            return '<button type="button" ng-click="buttonOpenEditRoleModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">View</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('groups').withTitle('Role Groups').withClass('none').notSortable()
        ];
        
    }]);