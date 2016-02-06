'use strict';

/* 
 * State Redirect Timeout
 * 
 * @author  Rachel L Carbone
 * 
 * @param toState string the name of a valid state
 * @param timeoutSeconds  int number of seconds to wait before changing state
 * 
 * Insert an inline <strong>#</strong> tag contianing the number of seconds
 * remaining until a state redirect.
 * 
 * <div data-rc-bootstrap-alerts="proxy" options="options"></div>
 */

var app = angular.module('rc.bootstrapAlerts', [])
        .directive('rcBootstrapAlerts',
        function() {
            return {
                restrict: 'A',
                template: '<uib-alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.msg}}</uib-alert>',
                scope: {
                    proxy: '=rcBootstrapAlerts',
                    options: '=?options'
                },
                controller: ['$scope', '$timeout', '$filter', function($scope, $timeout, $filter) {
                     
                    // Init 
                    $scope.alerts = [];
                    $scope.proxy = {};
                    
                    var timeoutDur = 5000;
                    
                    
                    // Private Functions
                    $scope.setTimerOnAlert = function(alertId) {
                        $timeout(function() {
                            var found = $filter('filter')($scope.alerts, {id: alertId}, true);
                            if (angular.isDefined(found[0])) {
                                var index = $scope.alerts.indexOf(found[0]);
                                $scope.alerts.splice(index, 1);  
                            }
                        }, timeoutDur);
                    };

                    $scope.closeAlert = function(index) {
                        $scope.alerts.splice(parseInt(index), 1);
                    };
                    
                    // Proxy

                    $scope.proxy.close = $scope.closeAlert;

                    $scope.proxy.closeAll = function(index) {
                        $scope.alerts = [];
                    };
                    
                    $scope.proxy.add = function(message, type) {
                        var alertType = (angular.isDefined(type) && (type === 'success' || type === 'info' || type === 'warning' || type === 'danger')) ? type : 'danger';
                        var id = Date.parse(new Date()).toString();
                        
                        $scope.alerts.push({type: alertType, msg: message, id: id });
                        $scope.setTimerOnAlert(id);
                    };
                    
                    // Shortcuts
                    $scope.proxy.success = function(message) {
                        $scope.proxy.add(message, 'success');
                    };
                    $scope.proxy.info = function(message) {
                        $scope.proxy.add(message, 'info');
                    };
                    $scope.proxy.warning = function(message) {
                        $scope.proxy.add(message, 'warning');
                    };
                    $scope.proxy.warn = $scope.proxy.warning;
                    $scope.proxy.danger = function(message) {
                        $scope.proxy.add(message, 'danger');
                    };
                    $scope.proxy.error = $scope.proxy.danger;
                    
                }]
            };
        });