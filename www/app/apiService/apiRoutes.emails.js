'use strict';

/* 
 * API Routes for Triggering Emails
 * 
 * API calls related to email notifications.
 */

angular.module('apiRoutes.emails', [])
.factory('ApiRoutesEmails', ['ApiService', '$q', function (API, $q) {
        
    var api = {};
    
    api.validateInviteToken = function(token) { 
        if(angular.isUndefined(token)) {
            return API.reject('Invalid token please check your parameters and try again.');
        }
        return API.post('/send-email/validate-token/' + token, 'Could validate invite token.');
    };
    
    api.sendInviteNewPlayerEmail = function(newPlayer) { 
        if(!angular.isString(newPlayer.email)) {
            return API.reject('Invalid email please check your parameters and try again.');
        }
        return API.post('/send-email/invite-player', newPlayer, 'Could not send player invite.');
    };
    
    api.sendTeamInviteEmail = function(newPlayer) { 
        if(!angular.isString(newPlayer.email) ||
            !angular.isNumber(parseInt(newPlayer.invitedById)) ||
            !angular.isNumber(parseInt(newPlayer.teamId)) ||
            !angular.isString(newPlayer.invitedByFirstName) ||
            !angular.isString(newPlayer.invitedByLastName)) {
            return API.reject('Invalid email please check your parameters and try again.');
        }
        return API.post('/send-email/team-invite', newPlayer, 'Could not send team invite.');
    };
    
    api.acceptTeamInvite = function(token, userId, teamId) { 
        if(angular.isUndefined(token) || 
            angular.isUndefined(userId) || 
            angular.isUndefined(teamId)) {
            return API.reject('Invalid invite token please check your parameters and try again.');
        }
        return API.post('/send-email/team-invite/accept', { 'inviteToken' : token, 'teamId' : teamId, 'userId' : userId }, 'Could not accept team invite.');
    };
    
    api.declineTeamInvite = function(token, userId, teamId) { 
        if(angular.isUndefined(token) || 
            angular.isUndefined(userId) || 
            angular.isUndefined(teamId)) {
            return API.reject('Invalid invite token please check your parameters and try again.');
        }
        return API.post('/send-email/team-invite/decline', { 'inviteToken' : token, 'teamId' : teamId, 'userId' : userId }, 'Could not decline team invite.');
    };
    
    return api;
}]);