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

app.filter("formatMySQLDate", function () {
    return function (value) {
        if (!value) {
            return value;
        }
        return moment(value, 'YYYY-MM-DD HH:mm:ss').tz('America/New_York').format('M/D/YYYY h:mm a');
    };
});

app.filter('numberEx', ['numberFilter', '$locale',
    function (number, $locale) {

        var formats = $locale.NUMBER_FORMATS;
        return function (input, fractionSize) {
            
            if(!angular.isDefined(input)) {
                return input;
            }
            
            if(!angular.isDefined(fractionSize)) {
                fractionSize = 2;
            }
            //Get formatted value
            var formattedValue = number(input, fractionSize);

            //get the decimalSepPosition
            var decimalIdx = formattedValue.indexOf(formats.DECIMAL_SEP);

            //If no decimal just return
            if (decimalIdx === -1) {
                return formattedValue;
            } else {
                var whole = formattedValue.substring(0, decimalIdx);
                var decimal = (Number(formattedValue.substring(decimalIdx)) || "").toString();

                return whole + decimal.substring(1);
            }
        };
    }
]);