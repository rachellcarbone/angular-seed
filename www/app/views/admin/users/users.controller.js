'use strict';

/* 
 * Admin Users Page
 * 
 * Controller for the admin users page where system users can be viewed and mofified.
 */

angular.module('app.admin.users', [])
    .controller('AdminUsersCtrl', ['$scope', '$compile', '$filter', 'DTOptionsBuilder', 'DTColumnBuilder', 'DataTableHelper', 'ModalService',
        function($scope, $compile, $filter, DTOptionsBuilder, DTColumnBuilder, DataTableHelper, ModalService) {

        $scope.alertProxy = {};
        
        /* Modal triggers */
        // Edit User Modal
        $scope.buttonOpenEditUserModal = function (id) {
            var found = $filter('filter')($scope.dtUsers.instance.DataTable.data(), {id: id}, true);
            if(angular.isDefined(found[0])) {
                var modalInstance = ModalService.openEditUser(found[0]);
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtUsers.reloadData();
                }, function () {});
            }
        };
        
        
        $scope.buttonSignupPlayer = function() {
            var modalInstance = ModalService.openSignup(false);
            modalInstance.result.then(function(result) {
                $scope.alertProxy.success('New player added.');
                $scope.dtUsers.reloadData();
            });
        };

        // DataTable Setup
        $scope.dtUserGroups = {};
        $scope.dtUserGroups.options = DTOptionsBuilder.newOptions();

        $scope.dtUsers = DataTableHelper.getDTStructure($scope, 'adminUsersList');
        $scope.dtUsers.options.withOption('order', [2, 'desc']).withOption('responsive', {
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

                    var header = '<table datatable="" dt-options="dtUserGroups.options" class="table table-hover sub-table">\n\
                        <thead><tr>\n\
                        <td>ID</td>\n\
                        <td>Group</td>\n\
                        <td>Description</td>\n\
                        </tr></thead><tbody>';

                    var body = '';
                    $.each(data, function(index, value) {
                        body += '<tr><td>' + value.id + '</td><td>' + value.group + '</td><td>' + value.desc + '</td></tr>\n';
                    });

                    // Create angular table element
                    body = (body) ? body : '<tr><td colspan="3"><p>This user has not been assigned to any groups.</p></td></tr>';

                    var table = angular.element(header + body + '</tbody></table>');

                    // compile the table to keep the directives (ngClick)
                    $compile(table.contents())($scope);

                    return table;
                }
            }
        });

        $scope.dtUsers.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Groups').withClass('responsive-control text-right noclick').renderWith(function(data, type, full, meta) {
                return '<a><small>(' + data.groups.length +')</small> <i class="fa"></i></a>';
            }).notSortable(),
            DTColumnBuilder.newColumn(null).withTitle('').withClass('text-center').renderWith(function(data, type, full, meta) {
                var blocked = (data.disabled === null) ?
                        '<span title="User is enabled." class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>' :
                        '<span title="User has been disabled and cannot login." class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>';
                var verified = (data.verified === null) ?
                        '<span title="This user has not verified their email." class="label label-danger" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-exclamation-circle"></i></span>' :
                        '<span title="Email verified." class="label label-success" style="font-size: 12px; padding: 5px 8px;"><i class="fa fa-lg fa-check-circle-o"></i></span>';
                return blocked+ verified;
            }).notSortable(),
            DTColumnBuilder.newColumn('id').withTitle('ID'),
            DTColumnBuilder.newColumn('nameFirst').withTitle('First'),
            DTColumnBuilder.newColumn('nameLast').withTitle('Last'),
            DTColumnBuilder.newColumn(null).withTitle('Email').renderWith(function(data, type, full, meta) {
                return '<a ng-click="buttonOpenEditUserModal(\'' + data.id + '\')">' + data.email + '</a>';
            }),
            DTColumnBuilder.newColumn('phone').withTitle('Phone'),
            DTColumnBuilder.newColumn(null).withTitle('Team Name').renderWith(function (data, type, full, meta) {
                return (data.teamId) ? '<a ng-click="buttonOpenEditTeamModal(' + data.teamId +')">' + data.team +'</a>' : '';
            }),
            DTColumnBuilder.newColumn('created').withTitle('User Since').renderWith(function (data, type, full, meta) {
                return moment(data, 'YYYY-MM-DD HH:mm:ss').format('M/D/YYYY');
            }),
            DTColumnBuilder.newColumn(null).withTitle('').renderWith(function(data, type, full, meta) {
                return '<button ng-click="buttonOpenEditUserModal(\'' + data.id + '\')" type="button" class="btn btn-default btn-xs pull-right">View</button>';
            }).notSortable(),
            DTColumnBuilder.newColumn('groups').withTitle('User Groups').withClass('none').notSortable()
        ];
        
    }]);