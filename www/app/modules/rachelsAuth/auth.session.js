'use strict';

/* 
 * Auth Services
 * Sets Nav, Session and Build as global page variables. 
 */

angular.module('rachels.auth.session', [])
    .service('Session', [function() {
            
    var self = this;
    self.user = false;
    
    self.validateUser = function(opt) {
        return (typeof(opt) !== 'undefined' &&
                typeof(opt.id) !== 'undefined' &&
                typeof(opt.fname) !== 'undefined' &&
                typeof(opt.fname) !== 'undefined' &&
                typeof(opt.role) !== 'undefined');
    };
    
    self.create = function(opt) {
        if(self.validateUser(opt)) {
            self.user = {
                analystId: opt.id,
                analystName: opt.fname + ' ' + opt.lname,
                analystFName: opt.fname,
                analystLName: opt.lname,
                analystRole: opt.role
            };
            return true;
        } else {
            return false;
        }
    };
    
    self.destroy = function() {
        self.user = false;
        return true;
    };
    
    self.get = function() {
        return angular.copy(self.user);
    };
    
    return {
            create : self.create,
            destroy : self.destroy,
            get : self.get
        };
        
    }]);
