'use strict';

/* 
 * Rachels Directives
 * 
 * A parent module for my custom directives.
 */

angular.module('rc.DropDowns', [])
.directive('rcDropDownStates', function () {
    return {
        require: 'select',
        transclude: true,
        template: '<option ng-repeat="(key, value) in stateList" value="{{key}}">{{key}}</option>',
        link: function ($scope, element, attributes, ctrl, transclude) {
            $scope.addTerritories = (attributes.territories && (attributes.territories === true || attributes.territories === "true"));
            $scope.addMilitary = (attributes.military && (attributes.military === true || attributes.military === "true"));
            // Add transcluded options to the beginning of the select list
            // EX: <option value="">Select State</option>
            transclude(function (clone) {
                element.prepend(clone);
            });
        },
        controller: ["$scope", function ($scope) {
            $scope.stateList = {
                "AL": "Alabama",
                "AK": "Alaska",
                "AZ": "Arizona",
                "AR": "Arkansas",
                "CA": "California",
                "CO": "Colorado",
                "CT": "Connecticut",
                "DE": "Delaware",
                "FL": "Florida",
                "GA": "Georgia",
                "HI": "Hawaii",
                "ID": "Idaho",
                "IL": "Illinois",
                "IN": "Indiana",
                "IA": "Iowa",
                "KS": "Kansas",
                "KY": "Kentucky",
                "LA": "Louisiana",
                "ME": "Maine",
                "MD": "Maryland",
                "MA": "Massachusetts",
                "MI": "Michigan",
                "MN": "Minnesota",
                "MS": "Mississippi",
                "MO": "Missouri",
                "MT": "Montana",
                "NE": "Nebraska",
                "NV": "Nevada",
                "NH": "New Hampshire",
                "NJ": "New Jersey",
                "NM": "New Mexico",
                "NY": "New York",
                "NC": "North Carolina",
                "ND": "North Dakota",
                "OH": "Ohio",
                "OK": "Oklahoma",
                "OR": "Oregon",
                "PA": "Pennsylvania",
                "RI": "Rhode Island",
                "SC": "South Carolina",
                "SD": "South Dakota",
                "TN": "Tennessee",
                "TX": "Texas",
                "UT": "Utah",
                "VT": "Vermont",
                "VA": "Virginia",
                "WA": "Washington",
                "WV": "West Virginia",
                "WI": "Wisconsin",
                "WY": "Wyoming"
            };

            if ($scope.addTerritories) {
                angular.extend($scope.stateList, {
                    "GU": "Guam",
                    "AS": "American Samoa",
                    "DC": "District Of Columbia",
                    "FM": "Federated States Of Micronesia",
                    "MH": "Marshall Islands",
                    "MP": "Northern Mariana Islands",
                    "PW": "Palau",
                    "PR": "Puerto Rico",
                    "VI": "Virgin Islands"
                });
            }

            if ($scope.addMilitary) {
                angular.extend($scope.stateList, {
                    "AA": "Armed Forces Americas",
                    "AP": "Armed Forces Pacific",
                    "AE": "Armed Forces Others"
                });
            }
        }]
    };
})
.directive('rcDropDownDays', function () {
    return {
        require: 'select',
        transclude: true,
        template: '<option ng-repeat="(key, value) in dayList" value="{{key}}">{{key}}</option>',
        link: function ($scope, element, attributes, ctrl, transclude) {
            transclude(function (clone) {
                element.prepend(clone);
            });
        },
        controller: ["$scope", function ($scope) {
            $scope.dayList = {
                "Sunday": "Sunday",
                "Monday": "Monday",
                "Tuesday": "Tuesday",
                "Wednesday": "Wednesday",
                "Thursday": "Thursday",
                "Friday": "Friday",
                "Saturday": "Saturday"
            };
        }]
    };
})
