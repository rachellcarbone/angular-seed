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
    'auth.constants',
    'app.admin'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Admin Route */
        $stateProvider.state('admin', {
            url: '/admin',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'layout@': {
                    templateUrl: 'app/views/admin/adminLayout/adminLayout.html',
                    controller: 'AdminLayoutCtrl'
                }
            }
        });

        $stateProvider.state('admin.dashboard', {
            title: 'Admin Dashboard',
            url: '/dashboard',
            views: {
                'content@admin': {
                    templateUrl: 'app/views/admin/dashboard/dashboard.html',
                    controller: 'AdminDashboardCtrl'
                }
            }
        });
        
    }]);