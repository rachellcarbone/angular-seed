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

        /*  Abstract App */
        $stateProvider.state('app', {
            abstract: true,
            resolve: {
                AuthService: 'AuthService',
                initUser: function(AuthService) {
                    return AuthService.init();
                },
                siteConfigVariables: function() {
                    return {
                        siteTitle : 'Angular Seed',
                        siteUrl : 'angular-seed.com',
                        siteCopywrite : 'All rights reserved.'
                    };
                }
            }
        });

        /*  Abstract Error Route */
        $stateProvider.state('app.error', {
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
        $stateProvider.state('app.error.notfound', {
            title: 'Page Not Found',
            url: '/404',
            views: {
                'content@app.error': {
                    templateUrl: 'app/views/error/notFound/notFound.html',
                    controller: 'ErrorNotFoundCtrl'
                }
            }
        });
        
        $stateProvider.state('app.error.notauthorized', {
            title: 'User Not Authorized',
            url: '/unauthorized',
            views: {
                'content@app.error': {
                    templateUrl: 'app/views/error/notAuthorized/notAuthorized.html',
                    controller: 'ErrorNotAuthorizedCtrl'
                }
            }
        });
        
        /* Maintenance Page */
        $stateProvider.state('app.maintenance', {
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