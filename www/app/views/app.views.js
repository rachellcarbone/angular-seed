'use strict';

/* 
 * Admin Pages Module
 * 
 * Include controllers and other modules required on the admin pages.
 */

angular.module('app.views', [
    'app.elements.adminHeader',
    'app.elements.authHeader',
    'app.elements.memberHeader',
    'app.elements.publicFooter',
    'app.elements.publicHeader',
    'app.elements.storeSubHeader',
    
    'app.admin.dashboard',
    'app.admin.fieldVisibility',
    'app.admin.groups',
    'app.admin.roles',
    'app.admin.systemVariables',
    'app.admin.users',
    
    'app.auth.login',
    'app.auth.resetPassword',
    'app.auth.signup',
    'app.auth.signupInvite',
    
    'app.error.maintenance',
    'app.error.notAuthorized',
    'app.error.notFound',
    
    'app.member.dashboard',
    'app.member.profile',
    'app.member.settings',
    
    'app.public.about',
    'app.public.contact',
    'app.public.landing',
    'app.public.terms',
    'app.public.tour',
    
    'app.store.cart',
    'app.store.category',
    'app.store.item',
    'app.store.storeHome'
]);