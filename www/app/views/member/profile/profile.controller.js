'use strict';

/* 
 * Member Profile Page
 * 
 * Controller for the member profile page used to view and edit a users profile.
 */

angular.module('app.member.profile', [])
    .controller('MemberProfileCtrl', ['$scope', '$log', 'UserSession', 'ApiRoutesUsers', 
    function($scope, $log, UserSession, ApiRoutesUsers) {
        
        /* Used to restrict alert bars */
        $scope.restrictTo = "edit-user-modal";

        /* Holds the add / edit form on the modal */
        $scope.form = {};

        /* User to display and edit */
        $scope.user = UserSession.get();

        /* Click event for the Add / New button */
        $scope.buttonChangePassword = function() {
            ApiRoutesUsers.changePassword($scope.user).then(
                function (result) {
                    $scope.editMode = false;
                }, function (error) {
                    $log.info(error);
                });
        };

        /* Click event for the Save button */
        $scope.buttonSave = function() {
            ApiRoutesUsers.saveUser($scope.user).then(
                function (result) {
                    $scope.editMode = false;
                }, function (error) {
                    $log.info(error);
                });
        };
    }]);