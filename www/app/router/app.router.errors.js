'use strict';

/*
 * State Declarations: Error Pages
 * 
 * Set up the states for error routes, such as the 
 * 404 'Page Not Found', 'User Not Authorized' and 'Maintenance Mode' pages.
 * Uses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

angular.module('app.router.errors', [])
    .config(['$stateProvider', 'USER_ROLES', 
    function ($stateProvider, USER_ROLES) {

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

        /* Error Page Not Found Page */
        
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
        
        /* Error Not Autorized Page */
        
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
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'layout@': {
                    templateUrl: 'app/views/maintenance/maintenance.html',
                    controller: 'MaintenanceCtrl'
                }
            }
        });
    }]);