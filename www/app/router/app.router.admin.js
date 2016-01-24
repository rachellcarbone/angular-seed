'use strict';

/*
 * State Declarations: Admin Pages
 * 
 * Set up the states for admin routes, such as the 
 * system settings page and other admin states.
 * Ueses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.admin', [
    'rcAuth.constants',
    'app.admin'
]);
app.config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES', 
    function ($stateProvider, $urlRouterProvider, USER_ROLES) {

        /*  Abstract Admin Route */
        $stateProvider.state('app.admin', {
            url: '/admin',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.admin},
            views: {
                'header@app.admin': {
                    templateUrl: 'app/views/admin/adminHeader/adminHeader.html',
                    controller: 'AdminHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/admin/adminLayout/adminLayout.html',
                    controller: 'AdminLayoutCtrl'
                },
                'footer@app.admin': {
                    templateUrl: 'app/views/admin/adminFooter/adminFooter.html',
                    controller: 'AdminFooterCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.dashboard', {
            title: 'Admin Dashboard',
            url: '/dashboard',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/dashboard/dashboard.html',
                    controller: 'AdminDashboardCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.roles', {
            title: 'Group Roles',
            url: '/groups-roles',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/roles/roles.html',
                    controller: 'AdminRolesCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.groups', {
            title: 'User Groups',
            url: '/user-groups',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/groups/groups.html',
                    controller: 'AdminGroupsCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.users', {
            title: 'System Users',
            url: '/users',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/users/users.html',
                    controller: 'AdminUsersCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.config', {
            title: 'System Configuration Variables',
            url: '/system-variables',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/systemVariables/systemVariables.html',
                    controller: 'AdminSystemVariablesCtrl'
                }
            }
        });

        $stateProvider.state('app.admin.visibility', {
            title: 'Define Tag Visibility',
            url: '/tag-visibility',
            views: {
                'content@app.admin': {
                    templateUrl: 'app/views/admin/fieldVisibility/tagVisibility.html',
                    controller: 'AdminFieldVisibilityCtrl'
                }
            }
        });
        
        // For any unmatched url, redirect to /
        $urlRouterProvider.when('/admin/', '/admin/dashboard');
        $urlRouterProvider.when('/admin', '/admin/dashboard');
        
    }]);