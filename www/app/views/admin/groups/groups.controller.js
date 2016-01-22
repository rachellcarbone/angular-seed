'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.groups', [])
    .controller('AdminGroupsCtrl', ['$scope', '$compile', 'DTOptionsBuilder', 'DTColumnBuilder', 'ApiRoutesDatatables', 
        function($scope, $compile, DTOptionsBuilder, DTColumnBuilder, ApiRoutesDatatables) {

        // Init variables
        $scope.editing = false;
        $scope.groupList = {};

        // DataTable Setup
        $scope.dtUserGroups = {};
        $scope.dtUserGroups.instance = {};
        $scope.dtUserGroups.options = DTOptionsBuilder.fromFnPromise(ApiRoutesDatatables.adminGroupsList())
        .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-4 col-md-4"l><"col-sm-4 col-md-4"i><"col-sm-4 col-md-4"p>>')
        .withOption('order', [2, 'asc' ])
        .withPaginationType('full_numbers')
        .withOption('createdRow', function(row, data, dataIndex) {
            // Recompiling so we can bind Angular directive to the DT
            $compile(angular.element(row).contents())($scope);
            // Add this row to the group list for validation
            $scope.groupList[data.id] = data;
        })
        .withOption('responsive', {
                details: {
                    type: 'column',
                    renderer: function(api, rowIdx, columns) {
                        var id = 0;
                        var data = new Array();
                        angular.forEach(columns, function (value, key) {
                            if(value.title == 'ID') {
                                id = value.data;
                            }
                            if(value.title == 'Group Roles') {
                                data = value.data;
                            }
                        });

                        var addButton = '<button ng-click="openAddRoleModal(' + id + ')" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Role</button>';
                        var header = '<table datatable="" dt-options="dtGroupRoles.options" class="table table-hover sub-table">\n\
                            <thead><tr>\n\
                            <td>ID</td>\n\
                            <td>Role</td>\n\
                            <td>Description' + addButton + '</td>\n\
                            </tr></thead><tbody>';


                        var body = '';
                        $.each(data, function(index, value) {
                            body += '<tr>' +
                                '<td>' + value['id'] + '</td> ' +
                                '<td>' + value['role'] + '</td> ' +
                                '<td>' + value['desc'] + '</td> ' +
                                '</tr>';
                        });

                        // Create angular table element
                        body = (body) ? body : '<tr><td colspan="3"><p>This group does not contain any roles.</p></td></tr>';

                        var html = header + body + '</tbody></table>';

                        var table = angular.element(html);

                        // compile the table to keep the directives (ngClick)
                        $compile(table.contents())($scope);

                        return table;
                    }
                }
            });
        $scope.dtUserGroups.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Roles').renderWith(function(data, type, full, meta) {
                return '<small>(' + data.roles.length +' Roles)</small>';
            }).withClass('responsive-control').notSortable(),
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('group').withTitle('Group'),
            DTColumnBuilder.newColumn('desc').withTitle('Description'),
            DTColumnBuilder.newColumn('created_by').withTitle('Created By'),
            DTColumnBuilder.newColumn('created_ts').withTitle('Created On'),
            DTColumnBuilder.newColumn('last_updated_by').withTitle('Last Update'),
            DTColumnBuilder.newColumn('last_updated_ts').withTitle('Updated On'),
            DTColumnBuilder.newColumn(null).withTitle('Edit').renderWith(function(data, type, full, meta) {
                return '<button type="button" ng-click="openEditModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('roles').withTitle('Group Roles').withClass('none').notSortable()
        ];

        
    }]);