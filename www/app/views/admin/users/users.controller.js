'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.users', [])
    .controller('AdminUsersCtrl', ['$scope', '$compile', 'DTOptionsBuilder', 'DTColumnBuilder', 'ApiRoutesDatatables', 
        function($scope, $compile, DTOptionsBuilder, DTColumnBuilder, ApiRoutesDatatables) {

        // Init Variables
        $scope.userList = {};
        $scope.groupList = {};

        // DataTable Setup
        $scope.dtUserGroups = {};
        $scope.dtUserGroups.options = DTOptionsBuilder.newOptions();

        $scope.dtUsers = {};
        $scope.dtUsers.instance = {};
        $scope.dtUsers.options = DTOptionsBuilder.fromFnPromise(ApiRoutesDatatables.adminUsersList())
        .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-4 col-md-4"l><"col-sm-4 col-md-4"i><"col-sm-4 col-md-4"p>>')
        .withOption('createdRow', function(row, data, dataIndex) {
            // Recompiling so we can bind Angular directive to the DT
            $compile(angular.element(row).contents())($scope);
            // Add this row to the user list for editing
            $scope.userList[data.id] = data;
        })
        .withOption('responsive', {
            details: {
                type: 'column',
                renderer: function(api, rowIdx, columns) {
                    // Get the group id
                    var id = 0;
                    var data = new Array();
                    angular.forEach(columns, function (value, key) {
                        if(value.title == 'ID') {
                            id = value.data;
                        }
                        if(value.title == 'User Groups') {
                            data = value.data;
                        }
                    });

                    var addButton = '<button ng-click="modalEditUserGroups(' + id + ')" class="btn btn-default btn-xs pull-right" type="button"><i class="fa fa-plus"></i> Group</button>';
                    var header = '<table datatable="" dt-options="dtUserGroups.options" class="table table-hover sub-table">\n\
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
                    body = (body) ? body : '<tr><td colspan="3"><p>This user has not been assigned to any groups.</p></td></tr>';

                    var html = header + body + '</tbody></table>';

                    var table = angular.element(html);

                    // compile the table to keep the directives (ngClick)
                    $compile(table.contents())($scope);

                    return table;
                }
            }
        });

        $scope.dtUsers.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Groups').renderWith(function(data, type, full, meta) {
                return '<small>(' + data.groups.length +' Groups)</small>';
            }).withClass('responsive-control').notSortable(),
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('blocked').withTitle('Enabled').renderWith(function(data, type, full, meta) {
                return (data === "1") ?
                        '<span class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>' :
                        '<span class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>';
            }),
            DTColumnBuilder.newColumn('verified').withTitle('Verified').renderWith(function(data, type, full, meta) {
                return (data === null) ?
                        '<span class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>' :
                        '<span class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>';
            }),
            DTColumnBuilder.newColumn('firstName').withTitle('First Name'),
            DTColumnBuilder.newColumn('lastName').withTitle('Last Name'),
            DTColumnBuilder.newColumn('email').withTitle('Email (username)'),
            DTColumnBuilder.newColumn('updatedBy').withTitle('Last Update'),
            DTColumnBuilder.newColumn('lastUpdated').withTitle('Updated On'),
            DTColumnBuilder.newColumn(null).withTitle('View').renderWith(function(data, type, full, meta) {
                return '<button type="button" class="btn btn-default btn-xs pull-right">View</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('groups').withTitle('User Groups').withClass('none').notSortable()
        ];
        
    }]);