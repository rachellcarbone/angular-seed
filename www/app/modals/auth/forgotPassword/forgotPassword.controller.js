'use strict';

/* @author  Kapil Akhia */

angular.module('app.modal.forgotPassword', [])
    .controller('ForgotPasswordCtrl', ['$scope', '$uibModalInstance', 'ForgotEmailAddress', 'AuthService',
    function ($scope, $uibModalInstance, ForgotEmailAddress, AuthService) {

        /* Used to restrict alert bars */
        $scope.alertProxy = {};

        /* Holds the form on the modal */
        $scope.form = {};

        $scope.forgotpasswordEmail = {
            'email': ForgotEmailAddress
        };

        /* Click event for the Cancel button */
        $scope.buttonCancel = function () {
            $uibModalInstance.dismiss(false);
        };

        /* Click event for the Submit button*/
        $scope.buttonSubmit = function () {

            //Make API Call to send reset password link in mail
            $scope.$broadcast('show-errors-check-validity');
            if ($scope.form.modalForm.$valid) {
                AuthService.forgotpassword($scope.forgotpasswordEmail).then(function (results) {
                }, function (error) {
                    $scope.alertProxy.error(error);
                });
            } else {
                $scope.form.modalForm.$setDirty();
                $scope.alertProxy.error('Please fill in both fields.');
            }



        };



    }]);