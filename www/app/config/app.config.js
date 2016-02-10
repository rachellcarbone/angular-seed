'use strict';

/*
 * App Configuration
 * 
 * General configuration settings for the app.
 */

var app = angular.module('app.config', [
    'ui.router'
  //'flow',
  //'ngNotificationsBar'
]);
app.config(['$locationProvider', '$urlMatcherFactoryProvider', 
    function ($locationProvider, $urlMatcherFactoryProvider) {
        
        /*
         * Routing Setup
         */
        
        // Allows for use of regular URL path segments (i.e. /article/21), 
        // instead of their hashbang equivalents (/#/article/21).
        // https://docs.angularjs.org/guide/$location#html5-mode
        $locationProvider.html5Mode(true);
        
        // Defines whether URL matching should be case sensitive (the default behavior), or not.
        // http://angular-ui.github.io/ui-router/site/#/api/ui.router.util.$urlMatcherFactory
        $urlMatcherFactoryProvider.caseInsensitive(true);
        
        // Defines whether URLs should match trailing slashes, or not (the default behavior).
        // http://angular-ui.github.io/ui-router/site/#/api/ui.router.util.$urlMatcherFactory
        $urlMatcherFactoryProvider.strictMode(false);
        
        /*
         * Tripple check that I dont need this.
         * $locationProvider.hashPrefix('!');
         */
        
        /*
app.config(['$httpProvider', 'notificationsConfigProvider', 'flowFactoryProvider',
    function ($httpProvider, notificationsConfigProvider, flowFactoryProvider) {
        */

        /* Notifications Bar Config
         * Set global settings for the ng-notifications bar.
         * https://github.com/alexbeletsky/ng-notifications-bar 
        notificationsConfigProvider.setAutoHide(true);
        notificationsConfigProvider.setHideDelay(5000);
        notificationsConfigProvider.setAcceptHTML(false);*/


        /* Flow JS File Upload Config
         * Can be used with different implementations of Flow.js.
         * https://github.com/flowjs/ng-flow 
        flowFactoryProvider.defaults = {
            singleFile: true,
            permanentErrors: [404, 500, 501],
            maxChunkRetries: 1,
            chunkRetryInterval: 5000,
            simultaneousUploads: 4
        };*/
        // flowFactoryProvider.factory = fustyFlowFactory;
    }]);