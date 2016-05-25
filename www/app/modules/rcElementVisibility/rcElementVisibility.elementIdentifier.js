/*
 * Element & Field Visibility Module - Element Identifier Directive
 * 
 * This directive can be used to identify access settings in the database associated
 * with a element identifier set in the front end.
 * 
 * In this way you can create a generic tag like 'banner.frontpage.holidaysales.blackfriday'
 * and associate it with elements on the front end. Then the key service is able to 
 * connect (and restrict if necessary) access to elements with user roles.
 *
 * This directive must be set as an element attribute,
 *
 * Ex:
 * <div data-element-identifier="admin-field-visibility.fancy-auth-element">...</div>
 *
 * The attribute should have a value of the element identifier that has
 * been saved in the database for this element.
 *
 * The reccomended pattern for the element identifer is,
 * "state-or-page-identifier.field-or-element-identifier"
 *
 * Each element identifier must be unique in the database,
 * HOWEVER the same element identifier may be used on multiple elements.
 */
angular.module('rcElementVisibility.elementIdentifier', [])
    .directive('elementIdentifier', ['ElementVisibilityKeyService', 'RC_ELEMENT_VISIBILITY_OPTIONS', '$log',
        function (ElementVisibilityKeyService, RC_ELEMENT_VISIBILITY_OPTIONS, $log) {
            return{
                restrict: 'A', // Must Be an Attribute
                link: function (scope, element, attrs, ctrl) {
                    angular.element(element).hide();

                    /*
                     * Element identifier
                     *
                     * The element identifier is set by giving the attribute that
                     * assigns this directive a string matching a unique element
                     * identifier stored in the database.
                     *
                     * Ex:
                     * element-identifier="admin-field-visibility.fancy-auth-element"
                     *
                     * @type Boolean|String
                     */
                    var fieldIdentifier = attrs.elementIdentifier || false;

                    /*
                     * Element Model
                     * Hold database data retrieved from the database for
                     * initialized elements.
                     *
                     * @type Boolean|Object {
                     *  'id' : field.id,                    // Integer DB Table index
                     *  'identifier' : field.identifier,    // String(225) Unique
                     *  'desc': field.desc,                 // TEXT colum describing the element
                     *  'roles': field.rolesv               // Array() of Integers
                     * }
                     */
                    var elementModel = false;

                    /*
                     * Set this initialized elements model.
                     * @param {Object} field
                     * @returns {Object|Boolean}
                     */
                    function setThisElement(field) {
                        if (field && field.id && field.identifier && field.roles) {
                            elementModel = {
                                'id': field.id,
                                'identifier': field.identifier,
                                'desc': field.desc || '',
                                'roles': field.roles
                            };
                        }
                        return elementModel;
                    }
                    ;

                    /*
                     * Set Element Visibility
                     *
                     * First if the element model isnt set, the element is marked
                     * incomplete and hidden.
                     *
                     * Second we use the Auth Element Visibility isElementVisible
                     * method to determine if the element should be hidden based
                     * on its assigned roles.
                     *
                     * @returns {void}
                     */
                    function setElementVisibility() {
                        var inEditMode = ElementVisibilityKeyService.inEditMode();
                        if (inEditMode) {
                            angular.element(element).addClass("element-test-mode");
                            angular.element(element).show();
                        }
                        // If this elements model isnt set or its roles are not set
                        if (!elementModel || !elementModel.roles) {
                            angular.element(element).addClass("element-identifier-incomplete");

                            if (inEditMode) {
                                $log.warn(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Not initialized - ' + fieldIdentifier);
                                angular.element(element).attr('title', RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Element "' + fieldIdentifier + '" is not initialized.');
                            }

                            // Abort visibility because we dont know what to do with it
                            return;
                        }

                        ElementVisibilityKeyService.isElementVisible(elementModel.roles).then(function (result) {
                            angular.element(element).addClass("element-identifier-visible");
                            angular.element(element).show();

                            if (inEditMode) {
                                $log.debug(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Visible - ' + elementModel.identifier + ' ' + elementModel.desc);
                                angular.element(element).attr('title', RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Element "' + fieldIdentifier + '" is visible.');
                            }

                        }, function (error) {
                            angular.element(element).addClass("element-identifier-hidden");

                            if (inEditMode) {
                                $log.info(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Hidden - ' + elementModel.identifier + ' ' + elementModel.desc);
                                angular.element(element).attr('title', RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'Element "' + fieldIdentifier + '" is hidden.');
                            }

                        });
                    }

                    /*
                     * Initialize Identified Elements
                     * Run the Auth Element Visibility initElement to initialize
                     * and retrieve data about this element from the database.
                     *
                     * @param {String} fieldIdentifier
                     */
                    function initElement() {
                        ElementVisibilityKeyService.initElement(fieldIdentifier).then(function (result) {
                            // If the element is safely initialized set this
                            // elements model with data from the database.
                            setThisElement(result);
                            setElementVisibility();
                        }, function (error) {
                            setElementVisibility();
                        });
                    }
                    ;

                    /*
                     * Run Element Init
                     * or if in admin mode,
                     * print a log about this incomplete element to the console.
                     *
                     * @param {Boolean|String} fieldIdentifier
                     */
                    function run() {
                        if (fieldIdentifier) {
                            // If the elements identifier is set
                            initElement(fieldIdentifier);
                        } else if (ElementVisibilityKeyService.inEditMode()) {
                            // Or if the identifier is not set and the
                            // "Auth Field Level Element Edit Mode" is on
                            $log.error(RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'There is an unregistered element on this page.');
                            angular.element(element).attr('title', RC_ELEMENT_VISIBILITY_OPTIONS.commentFlag + 'This element has no identifier.');
                            angular.element(element).addClass("element-test-mode element-identifier-unregistered");
                            angular.element(element).show();
                        } else {
                            // Nothing was set to identify this element
                            angular.element(element).show();
                        }
                    }

                    /* Start element initialization. */
                    run();
                }
            };
        }]);

