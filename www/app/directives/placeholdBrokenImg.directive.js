'use strict';

/* 
 * Placehold Broken Images
 * 
 * @author  Rachel L Carbone
 * 
 * @param imgHeight int the hight of the image to be returned (not the css height of the element)
 * @param imgWidth  int the width of the image to be returned (not the css width of the element)
 * @param imgCategory optional enum 'animals', 'arch', 'nature', 'people', or 'tech' - defaults to 'any'
 * @param imgFilter optional enum 'grayscale' or 'sepia', defaults to nothing
 * 
 * Placed as a attribute on a <img> tag the directive will check if the img
 * src is valid and if it is a broken image it will replace the src with a 
 * placeholder link to the placeimg.com image service.
 * 
 * <img src="" 
 *      class="img-responsive img-thumbnail"
 *      data-img-height="200"
 *      data-img-width="300"
 *      data-img-category="nature"
 *      data-img-filter="grayscale"
 *      data-placehold-broken-img/>
 */

angular.module('rachels.placeholdBrokenImg', [])
    .directive('placeholdBrokenImg', function($http) {
        
    return {
        restrict: 'A',          // Must be a attribute on an <img> tag
        scope: {
            imgHeight: '@',     // Must be int, defaults to 200
            imgWidth: '@',      // Must be int, defaults to 200
            imgCategory: '@',   // *Optional - 'animals', 'arch', 'nature', 'people', or 'tech'
            imgFilter: '@'      // *Optional - 'grayscale' or 'sepia'
        },
        link: function(scope, element, attrs) {
            
            scope.$watch(function() {
                return attrs.ngSrc;
            }, function () {
                
                /*
                 * Set Placehloder Src
                 * 
                 * Set the src of an image element to a valid src to the 
                 * PlaceImg.com image placeholder service. 
                 *
                 * @param element img element that the directive is a attribute of
                 * @param attrs object the element attrs object sent to the link function of the directive
                 */
                var setPlaceholderSrc = function(element, attrs) {
                    
                    // Helper function to validate an integer
                    var isInteger = function(x) {
                        return (parseInt(x) && parseInt(x) % 1 === 0);
                    };

                    // Validate imgHeight as a valid integer, or default 'h' to 200px 
                    var h = (attrs.imgHeight && isInteger(attrs.imgHeight)) ? attrs.imgHeight : 200;
                    
                    // Validate imgWidth as a valid integer, or default 'w' to 200px 
                    var w = (attrs.imgWidth && isInteger(attrs.imgWidth)) ? attrs.imgWidth : 200;
                    
                    // From https://placeimg.com/
                    var c = 'any';
                    switch(attrs.imgCategory) {
                        case 'animals':
                        case 'arch':
                        case 'nature':
                        case 'people':
                        case 'tech':
                            c = attrs.imgCategory;
                            break;
                    }
                    
                    // Filter is blank by default
                    var f = '';
                    // If the imgFilter param is set and is a valid option
                    if(attrs.imgFilter && 
                            (attrs.imgFilter === 'grayscale' || attrs.imgFilter === 'sepia')) {
                        // Set f to the filter for concatenation
                        f = '/' + attrs.imgFilter;
                    }
                    
                    // Format and encode parameters
                    var params = encodeURI(w + '/' + h + '/' + c + f);
                    
                    // Create placehloder service url
                    var placeholderSrc = 'https://www.placeimg.com/' + params;
                    
                    // Set element src to placeholder url
                    element.attr('src', placeholderSrc);
                };

                // If the src is undefined or null
                if (typeof(attrs.ngSrc) === 'undefined' || !attrs.ngSrc) {
                    // Set the image with a placeholder
                    setPlaceholderSrc(element, attrs);
                } else {
                    // Test the image url
                    $http.get(attrs.ngSrc).success(function(){
                        // If its valid do nothing
                    }).error(function(){
                        // If its a broken image give it a placeholder
                        setPlaceholderSrc(element, attrs);
                    });
                }
            });
            
        }
    };
    
});