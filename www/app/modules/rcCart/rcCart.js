'use strict';

/* 
 * Shopping Cart Services
 * 
 */

angular.module('rcCart', [
    'rcCart.store',
    'rcCart.directives'
])
.factory('rcCart', ['$log', function($log) {
    var self = this;
    var cart = {};

    self.cartContents = new Array();
    self.cartTotal = 0;

    function Product(sku, name, desc, price, qty) {
        this.setSku = function(sku) {
            this.sku = sku;
        };

        this.setName = function(name) {
            this.name = name;
        };

        this.setDesc = function(desc) {
            this.desc = desc;
        };

        this.setPrice = function(price) {
            this.price = parseFloat(price);
        };

        this.setQty = function(qty) {
            this.qty = parseInt(qty);
        };

        this.findTotal = function() {
            this.qty = parseInt(qty);
        };

        this.toObject = function() {
            return {
                sku: this.sku,
                name: this.name,
                desc: this.desc,
                price: this.price,
                qty: this.qty,
                total: this.total
            };
        }

        this.sku = this.setSku(sku);
        this.name = this.setName(name);
        this.desc = this.setDesc(desc);
        this.price = this.setPrice(price);
        this.qty = this.setQty(qty);
        this.total = 0.00;

        this.findTotal();
    }

    cart.addItem = function(sku, name, desc, price, qty) {
        var item = new Product(sku, name, desc, price, qty);
        self.cartContents.push(item);
    };

    cart.updateQty = function(product, qty) {
        if(qty <= 0) {
            cart.removeItem(product);
        } else {
            product.setQty(qty);
        }
    };

    cart.removeItem = function(product) { 
        var index = self.cartTotal.indexOf(product);
        if (index >= 0) {
            self.cartTotal.splice(index, 1);
            return true;
        }
        return false;
    };

    cart.getItem = function(sku) {
        angular.forEach(self.cartContents, function (product, index) {
            if(product.sku === sku) {
                return product.toObject();
            }
        });
        return false;
    };

    cart.getItems = function() {
        return self.cartContents;
    };

    cart.empty = function() {
        self.cartContents = new Array();
    };

    cart.updateTotal = function() {
        self.cartTotal = 0.00;
        angular.forEach(self.cartContents, function (product, index) {
            self.cartTotal += (product.price * product.price.qty);
        });
    };

    cart.getTotal = function() {
        return cartTotal;
    };

    cart.getItemCount = function() {
        return self.cartContents.length;
    };

    cart.loadSavedCart = function(cartContents) {
        cart.empty();
        angular.forEach(cartContents, function (product, index) {
            cart.addItem(product.sku, product.name, product.desc, product.price, product.qty);
        });
    };

    return cart;

}]);