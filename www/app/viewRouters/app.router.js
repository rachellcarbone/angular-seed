'use strict';

/*
 * Centeral Router
 * 
 * Include all the router area sub modules and use the
 * url router provider to set redirects and error routes. 
 * 
 * Setup error states.
 */

/*
 * https://github.com/angular-ui/ui-router/wiki/Multiple-Named-Views
 * 
 * EXAMPLE:
 * .state('contacts.detail', {
    views: {
        ////////////////////////////////////
        // Relative Targeting             //
        // Targets parent state ui-view's //
        ////////////////////////////////////

        // Relatively targets the 'detail' view in this state's parent state, 'contacts'.
        // <div ui-view='detail'/> within contacts.html
        "detail" : { },            

        // Relatively targets the unnamed view in this state's parent state, 'contacts'.
        // <div ui-view/> within contacts.html
        "" : { }, 

        ///////////////////////////////////////////////////////
        // Absolute Targeting using '@'                      //
        // Targets any view within this state or an ancestor //
        ///////////////////////////////////////////////////////

        // Absolutely targets the 'info' view in this state, 'contacts.detail'.
        // <div ui-view='info'/> within contacts.detail.html
        "info@contacts.detail" : { }

        // Absolutely targets the 'detail' view in the 'contacts' state.
        // <div ui-view='detail'/> within contacts.html
        "detail@contacts" : { }

        // Absolutely targets the unnamed view in parent 'contacts' state.
        // <div ui-view/> within contacts.html
        "@contacts" : { }

        // absolutely targets the 'status' view in root unnamed state.
        // <div ui-view='status'/> within index.html
        "status@" : { }

        // absolutely targets the unnamed view in root unnamed state.
        // <div ui-view/> within index.html
        "@" : { } 
  });
 */

var app = angular.module('app.router', [
  'ui.router',
  'rcAuth.constants',
  'app.views',
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
            data: {authorizedRoles: USER_ROLES.guest},
            resolve: {
                AuthService: 'AuthService',
                initUser: function(AuthService) {
                    return AuthService.init();
                },
                siteSystemVariables: function() {
                    return {
                        siteTitle : 'Angular Seed',
                        siteUrl : 'angular-seed.com',
                        siteCopywrite : 'All rights reserved.'
                    };
                }
            },
            views: {
                'layout@': {
                    templateUrl: 'app/views/_elements/pageLayout.html'
                },
                'footer@app': {
                    templateUrl: 'app/views/_elements/publicFooter/publicFooter.html',
                    controller: 'PublicFooterCtrl'
                }
            }
        });

        /*  Abstract Error Route */
        $stateProvider.state('app.error', {
            url: '/error',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest}
        });

        /* Error Pages */
        $stateProvider.state('app.error.notfound', {
            title: 'Page Not Found',
            url: '/404',
            views: {
                'content@app': {
                    templateUrl: 'app/views/error/notFound/notFound.html',
                    controller: 'ErrorNotFoundCtrl'
                }
            }
        });
        
        $stateProvider.state('app.error.notauthorized', {
            title: 'User Not Authorized',
            url: '/unauthorized',
            views: {
                'content@app': {
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

        // For any unmatched url, redirect to /
        $urlRouterProvider.when('', '/');
        $urlRouterProvider.when('/#', '/');
        $urlRouterProvider.otherwise('/error/404');
    }]);