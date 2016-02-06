'use strict';

/* 
 * JQuery DataTable Helper
 *  
 * Helper for JQuery DataTable initialization to keep the settings consistent.
 */

angular.module('DataTableHelper', [])
.factory('DataTableHelper', ['$compile', 'DTOptionsBuilder', 'ApiRoutesDatatables', 
    function($compile, DTOptionsBuilder, ApiRoutesDatatables) {
        
    var helper = {};
    
    helper.getDTStructure = function($scope, method) {
        var dt = {};

        /* Holds a copy of each row data */
        dt.rows = {};

        /* Object to hold DataTableInstance */
        //dt.instance = {};
        dt.instance = function (instance) {
            dt.instance = instance;
        };

        dt.options = DTOptionsBuilder.fromFnPromise(ApiRoutesDatatables[method])
            .withBootstrap()
            .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-4 col-md-4"l><"col-sm-4 col-md-4"i><"col-sm-4 col-md-4"p>>')
            .withPaginationType('full_numbers')
            .withOption('createdRow', function (row, data, dataIndex) {
                // Recompiling so we can bind Angular directive to the DT
                $compile(angular.element(row).contents())($scope);
                // Add this row to the variable list for editing
                dt.rows[data.id] = data;
            });
            

        dt.reloadData = function () {
            var resetPaging = true;
            var callback = function (json) {
                console.log(json);
            };
            dt.instance.reloadData();
        };
        
        return dt;
    };

    return helper;
}]);