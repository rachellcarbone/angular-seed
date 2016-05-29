'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.storeCategories', [])
    .controller('AdminStoreCategoriesCtrl', ['$scope', '$compile', '$filter', '$state', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
        function($scope, $compile, $filter, $state, DataTableHelper, DTColumnBuilder, ModalService) {

        $scope.alertProxy = {};

        // Button navigate to new product state
        $scope.buttonNewProduct = function () {
            $state.go('app.admin.storeProducts.new');
        };
        
        // Open New Product Category Modal Button Event
        $scope.buttonOpenNewCategoryModal = function () {
            var modalInstance = ModalService.openEditProductCategory();
            modalInstance.result.then(function (selectedItem) {
                $scope.dtProductCategories.reloadData();
            }, function () {});
        };

        // Open New Product Tag Modal Button Event
        $scope.buttonOpenNewTagModal = function () {
            var modalInstance = ModalService.openEditRole();
            modalInstance.result.then(function (selectedItem) {
                $scope.dtProductCategories.reloadData();
            }, function () {});
        };
                
        // Open Edit Product Category Modal Button Event
        $scope.buttonOpenEditCategoryModal = function (id) {
            var found = $filter('filter')($scope.dtProductCategories.instance.DataTable.data(), {id: id}, true);
            if(angular.isDefined(found[0])) {
                var modalInstance = ModalService.openEditProductCategory(found[0]);
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtProductCategories.reloadData();
                }, function () {});
            }
        };

        // DataTable Setup
        $scope.dtProductCategories = DataTableHelper.getDTStructure($scope, 'adminStoreProductCategoryList');
        $scope.dtProductCategories.options.withOption('order', [0, 'desc']);
        
        $scope.dtProductCategories.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Category').renderWith(function(data, type, full, meta) {
                return (type !== 'display') ? data.category :
                        '<a ng-click="buttonOpenEditCategoryModal(\'' + data.id + '\')">' + data.category + '</a>';
            }),
            DTColumnBuilder.newColumn('identifier').withTitle('Slug').renderWith(function(data, type, full, meta) {
                return (type !== 'display') ? data :
                        '<code>' + data + '</code>';
            }),
            DTColumnBuilder.newColumn('description').withTitle('Description').notSortable()
        ];
        
    }]);