'use strict';

/*
 * Application .run
 * 
 * On a successful state change set the root scopes title property
 * used for the html <title> tag.
 * 
 * Angular .run exicuted after the .config function.
 */

var app = angular.module('app.run', []);
app.run(function ($rootScope) {
    
    // Execute every time the state is successfully changed (after interceptors)
    $rootScope.$on('$stateChangeSuccess', function (event, current, previous) {
        // If the new state has property for 'title' (set in the state declaration) 
        // set the root scope variable title to be used by the index.html template.
        $rootScope.title = (current.hasOwnProperty('title')) ? current.title : 'Rachel\'s New Project Seed';
        // If the new state has property for 'bodyClass' (set in the state declaration) 
        // set the root scope variable title to be used by the index.html template.
        $rootScope.bodyClass = (current.hasOwnProperty('bodyClass')) ? current.bodyClass : '';
    });
    
});