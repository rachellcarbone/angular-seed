'use strict';

/*
 * String Filters
 */

var app = angular.module('filters.string', []);

app.filter("formatParagraphsAsHtml", function () {
    return function (value) {
        if (!value) {
            return value;
        }
        return value.replace(/\n\r?/g, '<br /><br />');
    };
});

app.filter('wordCounter', function () {
    return function (value) {
        
        if (value && (typeof value === 'string')) {
            return value.trim().split(/\s+/).length;
        }
        return 0;
        
    };
});

app.filter('readingTimeInSeconds', function () {
    return function (value) {
        
        if (value && (typeof value === 'string')) {
            var words = value.trim().split(/\s+/).length;
            return Math.round(words / 3);
        } 
        else if (value && (value === parseInt(value, 10))) {
            return Math.round(value / 3);
        } 
        return 0;
        
    };
});
