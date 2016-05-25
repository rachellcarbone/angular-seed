'use strict';

/* 
 * Element & Field Visibility Module
 * 
 * This module provides a directive and a lookup service that can be used to create
 * id's (a tag, or a string) used to identify access settings in the database.
 * 
 * In this way you can create a generic tag like 'banner.frontpage.holidaysales.blackfriday'
 * and associate it with elements on the front end. Then the key service is able to 
 * connect (and restrict if necessary) access to elements with user roles.
 */

angular.module('rcElementVisibility', [
    'rcElementVisibility.constants',
    'rcElementVisibility.elementIdentifier',
    'rcElementVisibility.keyService'
]);