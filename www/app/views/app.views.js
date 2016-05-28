'use strict';

/* 
 * Admin Pages Module
 * 
 * Include controllers and other modules required on the admin pages.
 */

angular.module('app.views', [
    'app.elements.publicFooter',
    
    'app.admin.header',
    'app.admin.dashboard',
    'app.admin.fieldVisibility',
    'app.admin.groups',
    'app.admin.roles',
    'app.admin.systemVariables',
    'app.admin.users',
    
    'app.auth.header',
    'app.auth.login',
    'app.auth.resetPassword',
    'app.auth.signup',
    'app.auth.signupInvite',
    
    'app.error.maintenance',
    'app.error.notAuthorized',
    'app.error.notFound',
    
    'app.member.header',
    'app.member.dashboard',
    'app.member.profile',
    'app.member.settings',
    
    'app.public.header',
    'app.public.about',
    'app.public.contact',
    'app.public.landing',
    'app.public.terms',
    'app.public.tour',
    
    'app.store.cart',
    'app.store.category',
    'app.store.item',
    'app.store.storeHome',
    'app.store.subheader'
]);