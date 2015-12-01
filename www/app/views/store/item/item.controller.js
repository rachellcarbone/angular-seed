'use strict';

angular.module('app.store.item', [])
        .controller('ItemDetailCtrl', ['$scope', 'rcCart', function ($scope, rcCart) {
                
            $scope.item = {
                sku: "1",
                name: "Lorem",
                shortDesc: "Summus brains sit​​, morbo vel maleficia? De apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium. ",
                desc: "Summus brains sit​​, morbo vel maleficia?\n\r\n\rDe apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium.\n\r\n\rPie gummi bears tiramisu sugar plum cookie. Donut chocolate bar tart cotton candy chocolate cake cheesecake.\n\r\n\rPie dessert lollipop lollipop ice cream. Jelly beans donut brownie dragée apple pie bonbon. Danish jelly tiramisu candy. Sweet gingerbread lollipop toffee. Toffee donut apple pie toffee liquorice toffee jujubes. Cake bear claw cake lemon drops liquorice jelly-o. Macaroon pastry gummi bears oat cake topping. Pudding dragée candy canes cake. Sweet roll fruitcake brownie croissant danish chocolate cake sweet roll chocolate cake marzipan.",
                price: "49.99",
                regularPrice: "69.99",
                qty: 2,
                total: "9.98"
            };
            
            
            $scope.updateTotal = function() {
                $scope.item.total = ($scope.item.qty * parseFloat($scope.item.price)).toFixed(2);
            };
    }]);