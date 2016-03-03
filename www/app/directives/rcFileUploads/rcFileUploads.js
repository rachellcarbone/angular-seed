'use strict';

/* 
 * Rachels Directives
 * 
 * A parent module for my custom directives.
 */

angular.module('rc.FileUploads', [])
.directive('rcImageUploadWithEditor', function (DIRECTIVES_URL) {
    return {
        restrict: 'A',          // Must be a attribute on a html tag
        scope: {
            imageUpload: '=rcImageUploadWithEditor',
            options: '=?options'
        },
        templateUrl: DIRECTIVES_URL + 'rcFileUploads/imageUploadWithEditor.html',
        link: function ($scope, element, attributes) {
            // Link - Programmatically modify resulting DOM element instances, 
            // add event listeners, and set up data binding. 
            
            $scope.inputLabel = attributes.inputLabel || 'Upload Venue Logo';
            $scope.cropAreaLabel = attributes.cropAreaLabel || 'Crop Your Image';
            $scope.cropPreviewLabel = attributes.cropPreviewLabel || 'Preview';
            
            $scope.imageUpload = {
                file : false,
                photostream : false,
                imageDataUrl : false,
                selectedFilesLabel : ''
            };
        },
        controller: ["$scope", 'DIRECTIVES_URL', function ($scope, DIRECTIVES_URL) {
            // Controller - Create a controller which publishes an API for 
            // communicating across directives.
            
            $scope.inputTemplateUrl = DIRECTIVES_URL + 'rcFileUploads/fileInputTemplate.html';
            
            $scope.fileSelectionCallback = function($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event) {
                $scope.imageUpload.file = $file;
                $scope.imageUpload.selectedFilesLabel = ($files.length > 0) ? $file.name : '';
            };
        }]
    };
});