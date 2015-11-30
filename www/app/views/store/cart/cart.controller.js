'use strict';

angular.module('app.store.cart', [])
        .controller('CartCtrl', ['$scope', 'rcCart', function ($scope, rcCart) {
                
                var cartContents = new Array(
                        {
                            sku: "1",
                            name: "Lorem",
                            desc: "Summus brains sit​​, morbo vel maleficia? De apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium. ",
                            price: "4.99",
                            qty: "2",
                            total: "9.98"
                        },
                        {
                            sku: "2",
                            name: "Lorqsdasdasdasdasdwem",
                            desc: "Summus brains sit​​, morbo vel maleficia? De apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium. ",
                            price: "50.00",
                            qty: "1",
                            total: "50.00"
                        },
                        {
                            sku: "3",
                            name: "12312332 123123 123Lorasdasdem",
                            desc: "Summus brains sit​​, morbo vel maleficia? De apocalypsi gorger omero undead survivor dictum mauris. Hi mindless mortuis soulless creaturas, imo evil stalking monstra adventus resi dentevil vultus comedat cerebella viventium. ",
                            price: "998.75",
                            qty: "1",
                            total: "998.75"
                        }
                );
        
            rcCart.loadSavedCart(cartContents);
            
            $scope.cartItems = rcCart.getItems();
            
            $scope.checkout = {
                
            };
    }]);