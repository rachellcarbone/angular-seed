'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.invitePlayer', [])        
    .controller('InvitePlayerModalCtrl', ['$scope', '$uibModalInstance', 'ApiRoutesEmails', 'InvitingPlayer',
    function($scope, $uibModalInstance, ApiRoutesEmails, InvitingPlayer) {   
    
    $scope.invitingPlayer = InvitingPlayer;
    $scope.invitingTeamName = (InvitingPlayer && angular.isDefined(InvitingPlayer.teams[0].name)) ? InvitingPlayer.teams[0].name : '';
    
    /* Used to restrict alert bars */
    $scope.alertProxy = {};
    
    /* Holds the add / edit form on the modal */
    $scope.form = {};
    
    /* Item to display and edit */
    $scope.invite = {
        'email' : '',
        'phone' : '',
        'nameFirst' : '',
        'nameLast' : ''
    };
    
    /* Click event for the Add Team button */
    $scope.buttonInvite = function() {
        if($scope.invitingPlayer && $scope.invitingPlayer.teams[0].id) {
            
            $scope.invite.invitedById = $scope.invitingPlayer.id;
            $scope.invite.invitedByFirstName = $scope.invitingPlayer.nameFirst;
            $scope.invite.invitedByLastName = $scope.invitingPlayer.nameLast;
            $scope.invite.teamId = $scope.invitingPlayer.teams[0].id;
            $scope.invite.teamName = $scope.invitingPlayer.teams[0].name;
            
            ApiRoutesEmails.sendTeamInviteEmail($scope.invite).then(function(response) {
                $uibModalInstance.close(response.msg);
            }, function(error) {
                $scope.alertProxy.error(error);
            });
        } else {
            ApiRoutesEmails.sendInviteNewPlayerEmail($scope.invite).then(function(response) {
                $uibModalInstance.close(response.msg);
            }, function(error) {
                $scope.alertProxy.error(error);
            });
        }
    };
        
    /* Click event for the Cancel button */
    $scope.buttonCancel = function() {
        $uibModalInstance.dismiss(false);
    };
    
}]);