'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.roles', [])
    .controller('AdminRolesCtrl', ['$scope', '$compile', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, $compile, DataTableHelper, DTColumnBuilder, ModalService) {

        /* Modal triggers */
        $scope.openNewRoleModal = function () {
            ModalService.openEditRole(false);
        };
        $scope.openEditRoleModal = ModalService.openEditRole;
        $scope.openEditGroupModal = ModalService.openEditGroup;
            
        // Init variables
        $scope.editing = false;
        $scope.list = {};

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

                        var addButton = '<button ng-click="openEditGroupModal(' + id + ')" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Group</button>';
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
            DTColumnBuilder.newColumn(null).withTitle('Groups').renderWith(function(data, type, full, meta) {
                return '<small>(' + data.groups.length +' Groups)</small>';
            }).withClass('responsive-control').notSortable(),
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('role').withTitle('Role'),
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
            return '<button type="button" ng-click="openEditRoleModal(\'' + data.id + '\')" class="btn btn-default btn-xs pull-right">Edit</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('groups').withTitle('Role Groups').withClass('none').notSortable()
        ];
        
    }]);