'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.groups', [])
    .controller('AdminGroupsCtrl', ['$scope', '$compile', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, $compile, DataTableHelper, DTColumnBuilder, ModalService) {

        /* Modal triggers */
        $scope.openNewGroupModal = function () {
            ModalService.openEditGroup(false);
        };
        $scope.openEditGroupModal = ModalService.openEditGroup;
        $scope.openEditRoleModal = ModalService.openEditRole;
        
        // Init variables
        $scope.editing = false;
        $scope.groupList = {};

        // DataTable Setup
        $scope.dtUserGroups = DataTableHelper.getDTStructure($scope, 'adminGroupsList');
        $scope.dtUserGroups.options.withOption('responsive', {
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

                        var addButton = '<button ng-click="openEditRoleModal(' + id + ')" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Role</button>';
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
            DTColumnBuilder.newColumn('createdBy').withTitle('Created By'),
            DTColumnBuilder.newColumn('created').withTitle('Created').renderWith(function (data, type, full, meta) {
                return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
            }),
            DTColumnBuilder.newColumn('updatedBy').withTitle('Last Update'),
            DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On').renderWith(function (data, type, full, meta) {
                return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY h:mm a');
            }),
            DTColumnBuilder.newColumn(null).withTitle('Edit').renderWith(function(data, type, full, meta) {
                return '<button type="button" ng-click="openEditGroupModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('roles').withTitle('Group Roles').withClass('none').notSortable()
        ];

        
    }]);