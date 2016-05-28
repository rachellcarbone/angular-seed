'use strict';

/* 
 * Public Pages Module
 * 
 * Include controllers and other modules required on the un authenticated pages.
 */

angular.module('app.public', [
    'app.public.about',
    'app.public.contact',
    'app.public.landing',
    'app.public.header',
    'app.public.footer',
    'app.public.terms',
    'app.public.tour'
]);