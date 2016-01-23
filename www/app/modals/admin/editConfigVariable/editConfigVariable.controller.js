'use strict';

/* @author  Rachel Carbone */

angular.module('app.modal.editConfigVariable', [])
    .controller('EditCategoryModalCtrl',
    function($rootScope, $scope, $modalInstance, CategoryService, modalSettings, notifications, CommonMethods) {
    $scope.restrictTo = "categoryModal";
        
    $scope.form = {};
    $scope.category = (modalSettings.category) ? modalSettings.category : {};
    $scope.categories = (modalSettings.categories) ? modalSettings.categories : [];
    $scope.inNewMode = (typeof($scope.category.id) === 'undefined');
    
    $scope.buttonNew = function() {
        var found = angular.copy(CommonMethods.findAllInArray($scope.categories, 'category', $scope.category.category));
        
        if(found.length > 0) {
            $scope.category.category = '';
            $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': 'A category with that name already exists.', 'type' : 'danger' });
        } else if ($scope.forms.categoryForm.$valid) {
            
            CategoryService.insertCategory($scope.category)
                .then(function(result) {
                    notifications.showSuccess(result);
                    $modalInstance.close(true);
                }, function(error) {
                    $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': error, 'type' : 'danger' });
                    $scope.$broadcast('form-validate');
                });
                
        } else {
            $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': 'Please fill in all required fields.', 'type' : 'danger' });
            $scope.$broadcast('form-validate');
        }
    };
    
    $scope.buttonSave = function() {
        var found = angular.copy(CommonMethods.findAllInArray($scope.categories, 'category', $scope.category.category));
        
        if(found.length > 1) {
            $scope.category.category = '';
            $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': 'A category with that name already exists.', 'type' : 'danger' });
        } else if ($scope.forms.categoryForm.$valid) {
            
            CategoryService.saveCategory($scope.category, $scope.category.id)
                .then(function(result) {
                    notifications.showSuccess(result);
                    $modalInstance.close(true);
                }, function(error) {
                    $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': error, 'type' : 'danger' });
                    $scope.$broadcast('form-validate');
                });
                
        } else {
            $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': 'Please fill in all required fields.', 'type' : 'danger' });
            $scope.$broadcast('form-validate');
        }
    };
    
    $scope.buttonDelete = function() {
        if (confirm('Are you sure you want to permanently delete this category? This action cannot be undone.')) {
            
            CategoryService.deleteCategory($scope.category.id)
                .then(function(result) {
                    notifications.showSuccess(result);
                    $modalInstance.close(true);
                }, function(error) {
                    $rootScope.$broadcast('alertbar-add', { 'sender' : $scope.restrictTo, 'message': error, 'type' : 'danger' });
                });
                
        } else {
            $scope.modalInEditMode(false);
        }
    };
        
    $scope.buttonCancel = function() {
        $modalInstance.dismiss(false);
    };
});
