'use strict';

/*
 * Number Filters
 * 
 * In HTML Template Binding
 * {{ filter_expression | filter : expression : comparator}}
 * 
 * In JavaScript
 * $filter('filter')(array, expression, comparator)
 * 
 * https://docs.angularjs.org/api/ng/filter/filter
 */

var app = angular.module('app.filters.number', []);

app.filter("formatPrice", function () {
    return function (value) {
        if (!value) {
            return value;
        }
        return '$' + value;
    };
});