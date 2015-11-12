'use strict';

/*
 * App Configuration
 * 
 * General configuration settings for the app.
 */

var app = angular.module('app.config', [
  //'flow',
  //'ngNotificationsBar'
]);
app.config(['$locationProvider', function ($locationProvider) {
        
        // Allows for use of regular URL path segments (i.e. /articles/21), 
        // instead of their hashbang equivalents (/#/articles/21).
        // https://docs.angularjs.org/guide/$location#html5-mode
        $locationProvider.html5Mode(true);
        
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