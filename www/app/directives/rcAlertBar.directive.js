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
 * <div data-rc-alert-bar="broadcastIdentifier"></div>
 */

var app = angular.module('rc.alertBar', [])
        .directive('rcAlertBar',
        function() {
            return {
                restrict: 'A',
                template: '<alert ng-repeat="alert in alerts" type="{{alert.type}}" close="closeAlert($index)">{{alert.msg}}</alert>',
                scope: {
                    broadcastIdentifier: '=?rcAlertBar',
                    proxy: '=?proxy',
                    defaultMessage: '=?defaultMessage',
                    defaultType: '=?defaultType'
                },
                controller: ['$scope', '$timeout', function($scope, $timeout) {
                    $scope.visibleDuration = 5000;
                    
                    if ($scope.broadcastIdentifier && typeof $scope.broadcastIdentifier === 'string') {
                        $scope.onlyShowLabels = [$scope.broadcastIdentifier];
                    } else if ($scope.broadcastIdentifier && Array.isArray($scope.broadcastIdentifier)) {
                        $scope.onlyShowLabels = $scope.broadcastIdentifier;
                    } else {
                        $scope.onlyShowLabels = [];
                    }

                    $scope.validInRestriction = function(sender) {
                        if ($scope.onlyShowLabels.length === 0 ||
                                typeof sender === 'undefined' ||
                                sender === 'true' ||
                                (typeof sender === 'string' && sender.toLowerCase() === 'all')) {
                            return true;
                        } else if (Array.isArray(sender)) {
                            for(var i = 0; i < sender.length; i++) {
                                if($scope.onlyShowLabels.indexOf(sender[i]) >= 0) {
                                    return true;
                                }
                            }
                            return false;
                        } else {
                            return ($scope.onlyShowLabels.indexOf(sender) >= 0);
                        }
                                
                    };

                    $scope.getValidAlertType = function(type) {
                        if(typeof type !== 'undefined' && type.length > 0) {
                            var t = type.toLowerCase();
                            return (t === 'danger' || t === 'warning' || t === 'success' || t === 'info') ? 
                                t : 'danger';
                        } else {
                            return 'danger';
                        }
                    };
                        
                    $scope.addAlert = function(message, type) {
                        var msg = (typeof message !== 'undefined' && message.length > 0) ?
                            message : $scope.defaultMessage ;
                        // Ensure apropriate type
                        var alertType = $scope.getValidAlertType(type);
                        var id = Date.parse(new Date()).toString();
                        
                        $scope.alerts.push({type: alertType, msg: msg, id: id });
                        $scope.setTimerOnAlert(id.toString());
                    };
                    
                    $scope.setTimerOnAlert = function(alertId) {
                        $timeout(function() {
                            for(var i = 0; i < $scope.alerts.length; i++) {
                                if($scope.alerts[i].id === alertId) {
                                    $scope.alerts.splice(i, 1);
                                    break;
                                }
                            }
                        }, $scope.visibleDuration);
                    };

                    $scope.closeAlert = function(index) {
                        if (typeof index === 'undefined' ||
                                index.length === 0 ||
                                index === 'true' ||
                                (typeof index === 'string' && index.toLowerCase() === 'all')) {
                            $scope.alerts = [];
                        } else if (parseInt(index)){
                            $scope.alerts.splice(parseInt(index), 1);
                        }
                    };
                    
                    // Broadcast Events
                    
                    $scope.$on('alertbar-add', function(event, args) {
                        if($scope.validInRestriction(args.sender)) {
                            $scope.addAlert(args.message, args.type);
                        }
                    });
                    
                    $scope.$on('alertbar-close', function(event, args) {
                        if($scope.validInRestriction(args.sender)) {
                            $scope.closeAlert(args.index);
                        }
                    });
        
                    /* Init */
                    $scope.alerts = [];
                    $scope.defaultMessage = (typeof $scope.defaultMessage !== 'undefined' && $scope.defaultMessage.length > 0) ?
                        $scope.defaultMessage : 'A error occured.';
                    $scope.defaultType = $scope.getValidAlertType($scope.defaultType);
                                            
                    $scope.proxy = {
                        add: $scope.addAlert,
                        close: $scope.closeAlert
                    };
                }]
            };
        });