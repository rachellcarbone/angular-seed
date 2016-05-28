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
    'rcAuth.constants',
    'app.views'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Member (Authenticated) Route */
        $stateProvider.state('app.member', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.user},
            views: {
                'header@app': {
                    templateUrl: 'app/views/member/memberHeader/memberHeader.html',
                    controller: 'MemberHeaderCtrl'
                }
            }
        });

        $stateProvider.state('app.member.dashboard', {
            title: 'Member Dashboard',
            url: '/dashboard',
            views: {
                'content@app': {
                    templateUrl: 'app/views/member/dashboard/dashboard.html',
                    controller: 'MemberDashboardCtrl'
                }
            }
        });

        $stateProvider.state('app.member.profile', {
            title: 'User Profile',
            url: '/profile',
            views: {
                'content@app': {
                    templateUrl: 'app/views/member/profile/profile.html',
                    controller: 'MemberProfileCtrl'
                }
            }
        });

        $stateProvider.state('app.member.settings', {
            title: 'User Settings',
            url: '/settings',
            views: {
                'content@app': {
                    templateUrl: 'app/views/member/settings/settings.html',
                    controller: 'MemberSettingsCtrl'
                }
            }
        });
        
        
    }]);