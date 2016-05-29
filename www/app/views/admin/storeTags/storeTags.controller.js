'use strict';

/* 
 * Admin Roles Page
 * 
 * Controller for the admin roles page where user roles can be viewed and mofified.
 */

angular.module('app.admin.storeTags', [])
    .controller('AdminStoreTagsCtrl', ['$scope', '$compile', '$filter', '$state', 'DataTableHelper', 'DTColumnBuilder', 'ModalService',
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
                $scope.dtProductTags.reloadData();
            }, function () {});
        };

        // Open New Product Tag Modal Button Event
        $scope.buttonOpenNewTagModal = function () {
            var modalInstance = ModalService.openEditRole();
            modalInstance.result.then(function (selectedItem) {
                $scope.dtProductTags.reloadData();
            }, function () {});
        };
                
        // Open Edit Product Category Modal Button Event
        $scope.buttonOpenEditTagModal = function (id) {
            var found = $filter('filter')($scope.dtProductTags.instance.DataTable.data(), {id: id}, true);
            if(angular.isDefined(found[0])) {
                var modalInstance = ModalService.openEditProductCategory(found[0]);
                modalInstance.result.then(function (selectedItem) {
                    $scope.dtProductTags.reloadData();
                }, function () {});
            }
        };

        // DataTable Setup
        $scope.dtProductTags = DataTableHelper.getDTStructure($scope, 'adminStoreProductTagList');
        $scope.dtProductTags.options.withOption('order', [0, 'desc']);
            
        $scope.dtProductTags.columns = [
            DTColumnBuilder.newColumn(null).withTitle('Tag').renderWith(function(data, type, full, meta) {
                return (type !== 'display') ? data.tag :
                        '<a ng-click="buttonOpenEditTagModal(\'' + data.id + '\')">' + data.tag + '</a>';
            }),
            DTColumnBuilder.newColumn('identifier').withTitle('Slug').renderWith(function(data, type, full, meta) {
                return (type !== 'display') ? data :
                        '<code>' + data + '</code>';
            }),
            DTColumnBuilder.newColumn('description').withTitle('Description').notSortable()
        ];
        
    }]);