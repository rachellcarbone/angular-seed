'use strict';

/*
 * Dev Application .run
 * 
 * On each state change start, delete the template for the new state from 
 * angular template cache if it has been saved. This is done because testers
 * may have trouble seeing changes quickly on dev servers.
 * 
 * Angular .run exicuted after the .config function.
 * 
 * IMPORTANT: Remove when out of dev mode
 */

var app = angular.module('app.run.dev', []);
app.run(function ($rootScope, $cacheFactory) {
    
    // Execute every time a state change begins
    $rootScope.$on('$stateChangeStart', function (event, toState, toParams, fromState, fromParams) {
        // If the state we are headed to has cached template views
        if (typeof (toState) !== 'undefined' && typeof (toState.views) !== 'undefined') {
            // Loop through each view in the cached state
            for (var key in toState.views) {
                // Delete templeate from cache
                console.log("Delete cached template: " + toState.views[key].templateUrl);
                $cacheFactory.get('templates').remove(toState.views[key].templateUrl);
            }
        }
    });

    
});