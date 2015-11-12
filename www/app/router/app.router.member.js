'use strict';

/*
 * State Declarations: Member / Authenticated
 * 
 * Set up the states for logged in user routes, such as the 
 * user profile page and other authenticated states.
 * Ueses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.member', [
    'rachels.auth.constants',
    'app.member'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Member (Authenticated) Route */
        $stateProvider.state('member', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'layout@': {
                    templateUrl: 'app/views/member/memberLayout/memberLayout.html',
                    controller: 'MemberLayoutCtrl'
                }
            }
        });

        $stateProvider.state('member.dashboard', {
            title: 'Member Dashboard',
            url: '/dashboard',
            views: {
                'content@': {}
            }
        });
        
        
    }]);