'use strict';

/*
 * Centeral Router
 * 
 * Include all the router area sub modules and use the
 * url router provider to set redirects and error routes. 
 * 
 * Setup error states.
 */

var app = angular.module('app.router', [
  'ui.router',
  'rcAuth.constants',
  'app.maintenance',
  'app.error',
  'app.router.admin',
  'app.router.auth',
  'app.router.member',
  'app.router.public',
  'app.router.store'
]);
app.config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES', 
    function ($stateProvider, $urlRouterProvider, USER_ROLES) {

        /*  Abstract Error Route */
        $stateProvider.state('error', {
            url: '/error',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'layout@': {
                    templateUrl: 'app/views/error/errorLayout/errorLayout.html',
                    controller: 'ErrorLayoutCtrl'
                }
            }
        });

        /* Error Pages */
        $stateProvider.state('error.notfound', {
            title: 'Page Not Found',
            url: '/404',
            views: {
                'content@error': {
                    templateUrl: 'app/views/error/notFound/notFound.html',
                    controller: 'ErrorNotFoundCtrl'
                }
            }
        });
        
        $stateProvider.state('error.notauthorized', {
            title: 'User Not Authorized',
            url: '/unauthorized',
            views: {
                'content@error': {
                    templateUrl: 'app/views/error/notAuthorized/notAuthorized.html',
                    controller: 'ErrorNotAuthorizedCtrl'
                }
            }
        });
        
        /* Maintenance Page */
        $stateProvider.state('maintenance', {
            title: 'Maintenance Mode',
            url: '/maintenance',
            views: {
                'layout@': {
                    templateUrl: 'app/views/maintenance/maintenance.html',
                    controller: 'MaintenanceCtrl'
                }
            }
        });

        // For any unmatched url, redirect to /
        $urlRouterProvider.when('', '/');
        $urlRouterProvider.when('/#', '/');
        $urlRouterProvider.otherwise('/error/404');
    }]);