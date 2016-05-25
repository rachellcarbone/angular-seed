'use strict';

/*
 * Element & Field Visibility Module Constants
 * 
 * Constants used within the rcELementVisibility Module.
 */

var app = angular.module('rcElementVisibility.constants', []);

app.constant('RC_ELEMENT_VISIBILITY_OPTIONS', {
        // Unique comment / logging prefix for this module
        commentFlag : 'FIELD VISIBILITY:: ',
        // Unique value for the auth edit mode cookie
        cookieEditingFlag : 'TJS_IN_FIELD_LEVEL_ELEMENT_EDIT_MODE',
        // Number of minutes until the auth edit mode cookie expires
        cookieExpInMinutes : 10
});