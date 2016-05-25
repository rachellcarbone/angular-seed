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
    
    helper.getDTStructure = function($scope, method, params, rowCallback, params2, params3) {
        var dt = {};

        /* Holds a copy of each row data */
        dt.rows = {};

        /* Object to hold DataTableInstance */
        //dt.instance = {};
        dt.instance = function (instance) {
            dt.instance = instance;
        };

        dt.options = DTOptionsBuilder.fromFnPromise(function() { return ApiRoutesDatatables[method](params, params2, params3); })
            .withBootstrap()
            .withDOM('<"row"<"col-sm-12 col-md-12"fr><"col-sm-12 col-md-12 add-space"t><"col-sm-6 col-md-4"l><"col-sm-6 col-md-4"i><"col-sm-12 col-md-4"p>>')
            .withPaginationType('full_numbers')
            .withOption('rowCallback', function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if(angular.isFunction(rowCallback)) {
                    // Unbind first in order to avoid any duplicate handler (see https://github.com/l-lin/angular-datatables/issues/87)
                    $('td:not(.noclick)', nRow).unbind('click');
                    $('td:not(.noclick)', nRow).bind('click', function() {
                        $scope.$apply(function() {
                            rowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull);
                        });
                    });
                }

                return nRow;
            })
            .withOption('createdRow', function(nRow, aData, iDisplayIndex) {
                // Recompiling so we can bind Angular directive to the DT
                $compile(angular.element(nRow).contents())($scope);
                // Add this row to the variable list for editing
                dt.rows[aData.id] = aData;

                return nRow;
            });
            
            dt.reloadData = function () {
                var resetPaging = true;
                var callback = function (json) {
                    console.log(json);
                };
                dt.instance.reloadData(callback, resetPaging);
            };

            $scope.$on('event:dataTableLoaded', function(event, loadedDT) {
                $('#' + loadedDT.id).on('xhr.dt', function (e, settings, json) {
                    dt.data = json;
                });
            });
        
        return dt;
    };

    return helper;
}]);