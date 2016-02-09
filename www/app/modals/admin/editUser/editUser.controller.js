'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editUser', [])
    .controller('EditUserModalCtrl', ['$scope', '$uibModalInstance', '$log', '$filter', 'AlertConfirmService', 'editing', 'ApiRoutesUsers', 'groupList',
    function($scope, $uibModalInstance, $log, $filter, AlertConfirmService, editing, ApiRoutesUsers, groupList) {
        $scope.groupList = groupList;
        
        /* Used to restrict alert bars */
        $scope.alertProxy = {};

        /* Holds the add / edit form on the modal */
        $scope.form = {};

        /* Modal Mode */    
        $scope.setMode = function(type) {
            $scope.viewMode = false;
            $scope.newMode = false;
            $scope.editMode = false;
            $scope.manageGroupsMode = false;

            switch(type) {
                case 'new':
                    $scope.newMode = true;
                    break;
                case 'edit':
                    $scope.editMode = true;
                    break;
                case 'groups':
                    $scope.manageGroupsMode = true;
                    break;
                case 'view':
                default:
                    $scope.viewMode = true;
                    break;
            }
        };

        if(angular.isDefined(editing.id)) {
            $scope.setMode('view');
        } else {
            $scope.setMode('new');
        }

        $scope.getMode = function() {
            if($scope.newMode) {
                return 'new';
            } else if($scope.editMode) {
                return 'edit';
            } else if($scope.manageGroupsMode) {
                return 'groups';
            } else {
                return 'view';
            }
        };

        /* Save for resetting purposes */
        $scope.saved = (angular.isDefined(editing.id)) ? angular.copy(editing) : {
            'nameFirst' : '',
            'nameLast' : '',
            'email' : '',
            'disabled' : 'false'
        };

        /* Item to display and edit */
        $scope.editing = angular.copy($scope.saved);
        $scope.editing.disabled = (angular.isUndefined($scope.editing.disabled) || $scope.editing.disabled === null) ? false : $scope.editing.disabled;

        /* Click event for the Add / New button */
        $scope.buttonNew = function() {
            ApiRoutesUsers.addUser($scope.editing).then(
                function (result) {
                    $scope.editMode = false;
                }, function (error) {
                    $log.info(error);
                });
        };

        /* Click event for the Save button */
        $scope.buttonSave = function() {
            AlertConfirmService.confirm('Are you sure you want to manually override this user?')
                .result.then(function () {
                    ApiRoutesUsers.saveUser($scope.editing).then(
                        function (result) {
                            $uibModalInstance.close(result);
                        }, function (error) {
                            $scope.alertProxy.error(error);
                        });
                }, function () {
                    $log.info(error);
                });
        };

        /* Click event for the Delete button */
        $scope.buttonDelete = function() {
            AlertConfirmService.confirm('Are you sure you want to disable this user? They will no longer be able to log in.')
                .result.then(function () {
                    ApiRoutesUsers.deleteUser($scope.editing.id).then(
                        function (result) {
                            $uibModalInstance.close(result);
                        }, function (error) {
                            $scope.alertProxy.error(error);
                        });
                });
        };

        /* Click event for the Manage Roles button */
        $scope.buttonManageGroups = function() {
            $scope.setMode('groups');
        };

        /* Click event for the Cancel button */
        $scope.buttonCancel = function() {
            var mode = $scope.getMode();

            switch(mode) {
                case 'edit':
                case 'groups':
                    $scope.setMode('view');
                    break;
                case 'new':
                case 'view':
                default:
                    $uibModalInstance.dismiss(false);
                    break;
            };
        };

        /* Click event for the Edit button*/
        $scope.buttonEdit = function() {
            $scope.setMode('edit');
        };

        /* Return BOOL if Group is assigned to this user */
        $scope.isUserAssignedToGroup = function(groupId) {
            var found = $filter('filter')($scope.saved.groups, {id: groupId}, true);
            return (angular.isDefined(found[0]));
        };

        $scope.buttonRemoveUserFromGroup = function(groupId) {
            ApiRoutesUsers.unassignUserFromGroup({ 'userId':$scope.saved.id, 'groupId': groupId }).then(
                function (result) {
                    $scope.alertProxy.success(result.msg);

                    angular.forEach($scope.saved.groups, function (obj, index) {
                        if (obj.id == groupId) {
                            $scope.saved.groups.splice(index, 1);
                            return;
                        }
                    });

                }, function (error) {
                    $scope.alertProxy.error(error);
                });
        };

        $scope.buttonAddUserToGroup = function(groupId) {
            ApiRoutesUsers.assignUserToGroup({ 'userId':$scope.saved.id, 'groupId': groupId }).then(
                function (result) {
                    $scope.alertProxy.success(result.msg);

                    var found = $filter('filter')($scope.groupList, {id: groupId}, true);
                    if (angular.isDefined(found[0])) {
                        $scope.saved.groups.push(found[0]);
                    }
                }, function (error) {
                    $scope.alertProxy.error(error);
                });
        };
}]);
