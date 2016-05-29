'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.storeProducts', [])
    .controller('AdminStoreProductsCtrl', ['$scope', '$compile', '$filter', '$state', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
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
                $scope.dtProducts.reloadData();
            }, function () {});
        };

        // Open New Product Tag Modal Button Event
        $scope.buttonOpenNewTagModal = function () {
            var modalInstance = ModalService.openEditRole();
            modalInstance.result.then(function (selectedItem) {
                $scope.dtProducts.reloadData();
            }, function () {});
        };
                
        // Open Edit Product Category Modal Button Event
        $scope.buttonEditProduct = function (id) {
            $state.go('app.admin.storeProducts.edit', { 'productId' : id });
        };

        // DataTable Setup
        $scope.dtProducts = DataTableHelper.getDTStructure($scope, 'adminStoreProductList');
        $scope.dtProducts.options.withOption('order', [0, 'desc']);
            
        $scope.dtProducts.columns = [
            DTColumnBuilder.newColumn('id').withTitle('Id'),
            DTColumnBuilder.newColumn(null).withTitle('Qty').renderWith(function(data, type, full, meta) {
                return (type !== 'display') ? data.item :
                        '<a ng-click="buttonEditProduct(\'' + data.id + '\')">' + data.item + '</a>';
            }),
            DTColumnBuilder.newColumn('tagline').withTitle('Tagline'),
            DTColumnBuilder.newColumn('description').withTitle('Description').notSortable(),
            DTColumnBuilder.newColumn('quantityAvailable').withTitle('Qty Availiable').withTitle('Item').renderWith(function(data, type, full, meta) {
                return (data === null) ? 'Unlimited' : data;
            }),
            DTColumnBuilder.newColumn('fullPrice').withTitle('Full Price'),
            DTColumnBuilder.newColumn('currentPrice').withTitle('Price')
        ];
        
    }]);