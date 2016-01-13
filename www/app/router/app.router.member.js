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
    'app.member'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Member (Authenticated) Route */
        $stateProvider.state('member', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@member': {
                    templateUrl: 'app/views/member/memberHeader/memberHeader.html',
                    controller: 'MemberHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/member/memberLayout/memberLayout.html',
                    controller: 'MemberLayoutCtrl'
                },
                'footer@member': {
                    templateUrl: 'app/views/member/memberFooter/memberFooter.html',
                    controller: 'MemberFooterCtrl'
                }
            }
        });

        $stateProvider.state('member.dashboard', {
            title: 'Member Dashboard',
            url: '/dashboard',
            views: {
                'content@member': {
                    templateUrl: 'app/views/member/dashboard/dashboard.html',
                    controller: 'MemberDashboardCtrl'
                }
            }
        });

        $stateProvider.state('member.profile', {
            title: 'User Profile',
            url: '/profile',
            views: {
                'content@member': {
                    templateUrl: 'app/views/member/profile/profile.html',
                    controller: 'MemberProfileCtrl'
                }
            }
        });

        $stateProvider.state('member.settings', {
            title: 'User Settings',
            url: '/settings',
            views: {
                'content@member': {
                    templateUrl: 'app/views/member/settings/settings.html',
                    controller: 'MemberSettingsCtrl'
                }
            }
        });
        
        
    }]);