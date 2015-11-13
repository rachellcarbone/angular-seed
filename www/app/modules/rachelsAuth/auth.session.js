'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rachels.auth.session', [])
    .service('UserSession', [function() {
    
    // Create a copy of this session object
    var self = this;
    
    // Create a varable to hold the logged in user session
    // False by default because no user is logged in yet
    self.user = false;
    
    // Validate that an object is a valid user object
    self.validateUser = function(opt) {
        return (typeof(opt) !== 'undefined' &&
                typeof(opt.id) !== 'undefined' &&
                typeof(opt.displayName) !== 'undefined' &&
                typeof(opt.email) !== 'undefined' &&
                typeof(opt.role) !== 'undefined' &&
                typeof(opt.apiHash) !== 'undefined');
    };
    
    // Create a user session
    self.create = function(opt) {
        // Validate that the sent object is a valid user
        if(self.validateUser(opt)) {
            // Set the user session
            self.user = {
                id: opt.id,
                displayName: opt.displayName,
                email: opt.email,
                role: opt.role,
                apiHash: opt.apiHash
            };
            return true;
        } else {
            return false;
        }
    };
    
    // Destryo the current session object
    self.destroy = function() {
        self.user = false;
        return true;
    };
    
    // Return a copy of the user object
    // If no user is logged in this will return false
    self.get = function() {
        return angular.copy(self.user);
    };
    
    // Return public methods
    return {
            create : self.create,
            destroy : self.destroy,
            get : self.get
        };
        
    }]);
