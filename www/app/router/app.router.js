'use strict';

/*
 * Central Router
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
angular.module('app.router.init', [])
    .config(['$stateProvider', '$urlRouterProvider', 'USER_ROLES', 
    function ($stateProvider, $urlRouterProvider, USER_ROLES) {

        /*  Abstract App */
        $stateProvider.state('app', {
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            resolve: {
                $q: '$q',
                AuthService: 'AuthService',
                ElementVisibilityKeyService: 'ElementVisibilityKeyService',
                initUser: function($q, AuthService) {
                    return $q(function (resolve, reject) {
                        AuthService.init().then(function (data) {
                            resolve(data);
                        }, function (error) {
                            reject(error);
                        });
                    });
                },
                siteSystemVariables: function() {
                    return {
                        siteTitle : 'Trivia Joint',
                        siteUrl : 'triviajoint.com',
                        siteCopywrite : 'All rights reserved.'
                    };
                },
                InitElementVisibilityKeyService: function (initUser, ElementVisibilityKeyService) {
                    return ElementVisibilityKeyService.getKey();
                }
            }
        });
    }]);

angular.module('app.router.default', [])
    .config(['$urlRouterProvider',
    function ($urlRouterProvider) {

        // For any unmatched url, redirect to /
        $urlRouterProvider.when('', '/');
        $urlRouterProvider.when('/', '/');
        $urlRouterProvider.when('/#', '/');
        $urlRouterProvider.otherwise('/error/404');
    }]);

angular.module('app.router', [
  'ui.router',
  'rcAuth.constants',
  'app.maintenance',
  'app.error',
  'app.router.init',
  'app.router.errors',
  'app.router.admin',
  'app.router.auth',
  'app.router.host',
  'app.router.member',
  'app.router.public',
  'app.router.venue',
  'app.router.default'
]);