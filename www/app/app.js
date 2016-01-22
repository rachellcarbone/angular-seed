'use strict';

/* 
 * Angular Seed - v0.1 
 * https://github.com/rachellcarbone/angular-seed/
 * Copyright (c) 2015 Rachel L Carbone; Licensed MIT
 */

// Include modules, and run application.
angular.module('theApp', [
    'ui.bootstrap',
    'ui.bootstrap.showErrors',
    'datatables',
    'ngCookies',
    'ngMessages',
    'api.v1',
    'rcDirectives',
    'rcAuth',
    'rcCart',
    'app.config',
    'app.router',
    'app.run',
    'app.run.dev',
    'app.filters'
]);