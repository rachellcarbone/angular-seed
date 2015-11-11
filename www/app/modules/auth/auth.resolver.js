'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('auth.resolver', [])
    .factory('AuthResolver', function ($q, $rootScope, $state) {
        
    return {
        resolve: function () {
            var deferred = $q.defer();
            deferred.resolve(true);
            var unwatch = $rootScope.$watch('_userSession', function (currentUser) {
                if (angular.isDefined(currentUser)) {
                    if (currentUser) {
                        deferred.resolve(currentUser);
                    } else {
                        deferred.reject();
                        $state.go('auth.login');
                    }
                    unwatch();
                }
            });
            return deferred.promise;
        }
    };
    
});


