'use strict';

/* 
 * rcImageUploadWithEditor
 * 
 * @author  Rachel L Carbone
 * 
 * @param imageUpload object reference to a $scope object used to retrieve data from this control in parent controllers
 * @param labelInput  string (optional) Label used for the imgage upload input and button controls
 * @param labelBrowseButton  string (optional) Label used one the image upload button
 * @param labelCropArea  string (optional) Label for the area used to crop selected image
 * @param labelCropPreview  string (optional) Label for the cropped image preview
 * 
 * Requires Angular Directives:
 *  ngFileUpload https://github.com/danialfarid/ng-file-upload
 *  ngImgCrop    https://github.com/alexk111/ngImgCrop
 * 
 * <div rc-image-upload-with-editor="venueLogo"
 *      data-label-input="Upload Venue Logo"
 *      data-label-browse-button="Select Logo"
 *      data-label-crop-area="Crop Your Logo"
 *      data-label-crop-preview="Logo Preview"></div>
 */


angular.module('rc.FileUploads', ['ngFileUpload', 'ngImgCrop'])
.directive('rcImageUploadWithEditor', function (DIRECTIVES_URL) {
    return {
        restrict: 'A',          // Must be a attribute on a html tag
        scope: {
            imageUpload: '=rcImageUploadWithEditor',    // $scope object REQUIRED
            savedImageDataUrl: '=savedImageDataUrl',    // $scope object REQUIRED
            labelInput: '@',                            // string optional
            labelBrowseButton: '@',                     // string optional
            labelCropArea: '@',                         // string optional
            labelCropPreview: '@'                       // string optional
        },
        templateUrl: DIRECTIVES_URL + 'rcFileUploads/imageUploadWithEditor.html',
        link: function ($scope, element, attributes) {
            
            // Setup object to hold uploaded image
            $scope.imageUpload = (angular.isUndefined($scope.imageUpload)) ? {} : $scope.imageUpload;
            
            // Be careful not to override saved image
            $scope.imageUpload.file = $scope.imageUpload.file || false;
            $scope.imageUpload.photostream = $scope.imageUpload.photostream || false;
            $scope.imageUpload.imageDataUrl = $scope.imageUpload.imageDataUrl || false;
            
            // Selected File Label
            $scope.imageUpload.selectedFilesLabel = (angular.isUndefined($scope.savedImageDataUrl)) ? '' : 'Saved Image';
            
            // Set custom or default label text
            $scope.labelInput = attributes.labelInput || 'Image Upload';
            $scope.labelBrowseButton = attributes.labelBrowseButton || 'Browse';
            $scope.labelCropArea = attributes.labelCropArea || 'Crop Your Image';
            $scope.labelCropPreview = attributes.labelCropPreview || 'Preview';
            $scope.labelSavedImage = attributes.labelSavedImage || 'Saved Image';
            
            
        },
        controller: ["$scope", '$timeout', function ($scope, $timeout) {
            /* File Selection Callback
             * called when files are selected, dropped, or cleared */
            $scope.fileSelectionCallback = function($files, $file, $newFiles, $duplicateFiles, $invalidFiles, $event) {
                $scope.imageUpload.file = $file;
                $scope.imageUpload.selectedFilesLabel = ($files.length > 0) ? $file.name : '';
            };
        }]
    };
});