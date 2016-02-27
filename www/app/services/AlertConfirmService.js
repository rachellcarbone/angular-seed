'use strict';

/* 
 * Service to Load UI Bootstrap Alert or Confrim Modals
 * 
 * Includes modal controllers and provides and api to launch the modal.
 * https://angular-ui.github.io/bootstrap/#/modal
 */

angular.module('AlertConfirmService', [])
.factory('AlertConfirmService', ['$uibModal', function($uibModal) {
        var api = {};

        /* Return the uibModalInstance */
        api.alert = function(options) {
            var message = 'It\'s dangerous to go alone.';
            var header = 'Alert!';
            var buttonText = 'OK';
                
            if (typeof options === "object") {
                message = options.message || message;
                header = options.header || header;
                buttonTextOk = options.buttonText || buttonText;
            }
            
            var templateHtml = '<div class="modal-header"><h3 class="modal-title">{{header}}</h3></div>' +
                    '<div class="modal-body">' + message + '</div>' +
                    '<div class="modal-footer"><button ng-click="buttonCancel()" class="btn btn-primary" type="button">{{buttonTextOk}}</button></div>';
            
            return $uibModal.open({
                size: 'sm',
                backdrop: 'static',
                template: templateHtml,
                resolve: {
                  message: function () {
                    return message;
                  },
                  header: function () {
                    return header;
                  },
                  buttonTextOk: function () {
                    return buttonTextOk;
                  }
                },
                controller: function ($scope, $uibModalInstance, message, header, buttonTextOk) {
                    $scope.message = message;
                    $scope.header = header;
                    $scope.buttonTextOk = buttonTextOk;
                    
                    /* Click event for the Cancel button */
                    $scope.buttonCancel = function() {
                        $uibModalInstance.dismiss(false);
                    };
                }
            });
        };

        /* Return the uibModalInstance */
        api.confirm = function(msg, title) {
            var question = 'Are you sure you really want to do that? The action cannot be undone.';
            var header = 'Please Confirm';
            var buttonTextConfirm = 'Confirm';
            var buttonTextCancel = 'Cancel';
                
            if (angular.isObject(msg)) {
                question = msg.question || question;
                header = msg.header || header;
                buttonTextConfirm = msg.buttonTextConfirm || buttonTextConfirm;
                buttonTextCancel = msg.buttonTextCancel || buttonTextCancel;
            } else if (angular.isString(msg)) {
                question = msg;
            }
            
            if (angular.isObject(title)) {
                question = title.question || question;
                header = title.header || header;
                buttonTextConfirm = title.buttonTextConfirm || buttonTextConfirm;
                buttonTextCancel = title.buttonTextCancel || buttonTextCancel;
            } else if (angular.isString(title)){
                header = title;
            }
            
            var templateHtml = '<div class="modal-header"><h3 class="modal-title">{{header}}</h3></div>' +
                    '<div class="modal-body">' + question + '</div>' +
                    '<div class="modal-footer"><button ng-click="buttonCancel()" class="btn btn-warning" type="button">{{buttonTextCancel}}</button>' +
                    '<button ng-click="buttonConfirm()" class="btn btn-danger" type="button">{{buttonTextConfirm}}</button></div>';
            
            return $uibModal.open({
                size: 'sm',
                backdrop: 'static',
                template: templateHtml,
                resolve: {
                  question: function () {
                    return question;
                  },
                  header: function () {
                    return header;
                  },
                  buttonTextConfirm: function () {
                    return buttonTextConfirm;
                  },
                  buttonTextCancel: function () {
                    return buttonTextCancel;
                  }
                },
                controller: function ($scope, $uibModalInstance, question, header, buttonTextConfirm, buttonTextCancel) {
                    $scope.question = question;
                    $scope.header = header;
                    $scope.buttonTextConfirm = buttonTextConfirm;
                    $scope.buttonTextCancel = buttonTextCancel;
                    
                    /* Click event for the Confirm button */
                    $scope.buttonConfirm = function() {
                        $uibModalInstance.close(true);
                    };
                    
                    /* Click event for the Cancel button */
                    $scope.buttonCancel = function() {
                        $uibModalInstance.dismiss(false);
                    };
                }
            });
        };
        
        return api;
}]);