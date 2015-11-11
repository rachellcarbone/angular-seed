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
  'rachels.auth',
  'app.router.admin',
  'app.router.auth',
  'app.router.member',
  'app.router.public'
]);
app.config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES', 
    function ($stateProvider, $urlRouterProvider, USER_ROLES) {

        /* Error Pages 
        $stateProvider.state('app.error', {
            url: '',
            abstract: true,
            views: {
                'container@': {
                    templateUrl: 'js/app/controllers/general/views/errorPages.html'
                }
            }
        })
        .state('app.error.notfound', {
            title: 'Page Not Found',
            url: '/error/not-found'
        })
        .state('app.error.notauthorized', {
            title: 'User Not Authorized',
            url: '/error/not-authorized'
        });
*/

        // For any unmatched url, redirect to /
        $urlRouterProvider.when('', '/');
        $urlRouterProvider.when('/#', '/');
        $urlRouterProvider.otherwise('/error/not-found');
    }]);