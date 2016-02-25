'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rcAuth.user', [])
    .service('UserSession', [function() {
    
    // Create a copy of this session object
    var self = this;
    
    // Create a varable to hold the logged in user session
    // False by default because no user is logged in yet
    self.user = false;
    
    // Validate that an object is a valid user object
    self.validateUser = function(user, session) {
        return (angular.isDefined(user.id) &&
                angular.isDefined(user.displayName) &&
                angular.isDefined(user.nameFirst) &&
                angular.isDefined(user.nameLast) &&
                angular.isDefined(user.email) && 
                angular.isDefined(user.roles));
    };
    
    // Create a user session
    self.create = function(user) {
        // Validate that the sent object is a valid user
        if(self.validateUser(user) &&
            angular.isDefined(user.apiKey) &&
            angular.isDefined(user.apiToken)) {
            // Set the user session
            self.user = user;
            return self.user;
        } else {
            return false;
        }
    };
    
    // Create a user session
    self.updateUser = function(user) {
        // Validate that the sent object is a valid user
        if(self.validateUser(user)) {
            // Set the user session
            user.apiKey = self.user.apiKey;
            user.apiToken = self.user.apiToken;
            self.user = user;
            return self.user;
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
        return (!self.user) ? false : self.user;
    };
    
    self.getAuthCredentials = function() {
        return (!self.user) ? false : {
            'apiKey' : self.user.apiKey,
            'apiToken' : self.user.apiToken
        };
    };
    
    // Safly return the User Id
    self.id = function() {
        return (!self.user.id) ? false : self.user.id;
    };
    
    // Safly return the User Display Name
    self.displayName = function() {
        return (!self.user.displayName) ? false : self.user.displayName;
    };
    
    // Safly return the User Email
    self.email = function() {
        return (!self.user.email) ? false : self.user.email;
    };
    
    // Safly return the User Role Object
    self.roles = function() {
        return (!self.user.roles) ? false : self.user.roles;
    };
    
    // Return public methods
    return {
            create : self.create,
            destroy : self.destroy,
            get : self.get,
            updateUser : self.updateUser,
            getAuthCredentials: self.getAuthCredentials,
            id : self.id,
            displayName : self.displayName,
            email : self.email,
            roles : self.roles
        };
        
    }]);
