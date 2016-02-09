'use strict';

/*
 * String Filters
 * 
 * In HTML Template Binding
 * {{ filter_expression | filter : expression : comparator}}
 * 
 * In JavaScript
 * $filter('filter')(array, expression, comparator)
 * 
 * https://docs.angularjs.org/api/ng/filter/filter
 */

var app = angular.module('app.filters.string', []);

app.filter("formatParagraphsAsHtml", ['$sce', function ($sce) {
    return function (value) {
        if (!value) {
            return value;
        }
        return $sce.trustAs('html', value.replace(/\n\r?/g, '<br />'));
    };
}]);

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

app.filter('getSlugPeriodSeperated', [function () {
    return function (value) {
        if (!value) {
            return value;
        }
        var a = value.trim();
        var b = a.toLowerCase();
        var c = b.replace(/ /g, '.');
        var d = c.replace(/[^a-zA-Z0-9-_.]/g, '');
        
        return d;
    };
}]);

app.filter('getSlugDashSeperated', [function () {
    return function (value) {
        if (!value) {
            return value;
        }
        var a = value.trim();
        var b = a.toLowerCase();
        var c = b.replace(/ /g, '-');
        var d = c.replace(/[^a-zA-Z0-9-_.]/g, '');
        
        return d;
    };
}]);