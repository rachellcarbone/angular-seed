'use strict';

/*
 * State Declarations: Public / No Authentication
 * 
 * Set up the states for public routes, such as the landing 
 * page and other un authenticated states.
 * Uses ui-roter's $stateProvider.
 * 
 * Set each state's title (used in the config for the html <title>).
 * 
 * Set auth access for each state.
 */

var app = angular.module('app.router.public', [
    'rcAuth.constants',
    'app.public'
]);
app.config(['$stateProvider', 'USER_ROLES', function ($stateProvider, USER_ROLES) {

        /*  Abstract Public (Un Authenticated) Route */
        $stateProvider.state('app.public', {
            url: '',
            abstract: true,
            data: {authorizedRoles: USER_ROLES.guest},
            views: {
                'header@app.public': {
                    templateUrl: 'app/views/public/publicHeader/publicHeader.html',
                    controller: 'PublicHeaderCtrl'
                },
                'layout@': {
                    templateUrl: 'app/views/public/publicLayout/publicLayout.html',
                    controller: 'PublicLayoutCtrl'
                },
                'footer@app.public': {
                    templateUrl: 'app/views/public/publicFooter/publicFooter.html',
                    controller: 'PublicFooterCtrl'
                }
            }
        });

        $stateProvider.state('app.public.homepage', {
            title: 'Redirecting to Trivia Joint Homepage...',
            url: '/',
            resolve: {
                $window: '$window',
                homepageRedirect : function($window) {
                    $window.top.location.href = 'http://www.triviajoint.com/';
                }
            }
        });
        
        $stateProvider.state('app.public.landing', {
            bodyClass: 'public landing',
            title: 'Welcome',
            url: '/game-list',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/landing/landing.html',
                    controller: 'PublicLandingCtrl'
                }
            }
        });

        $stateProvider.state('app.public.game', {
            bodyClass: 'public scoreboard',
            title: 'Game Scoreboard',
            url: '/scoreboard/:gameId/:roundNumber',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/member/scoreboard/scoreboard.html',
                    controller: 'MemberScoreboardDashboardCtrl'
                }
            },
            resolve: {
                $q: '$q',
                $rootScope: '$rootScope', 
                $state: '$state',
                TriviaScoreboard: 'TriviaScoreboard',
                AlertConfirmService: 'AlertConfirmService',
                currentGame: function(initUser, TriviaScoreboard, AlertConfirmService, $stateParams, $rootScope, $state, $q) {
                    $stateParams.roundNumber = (parseInt($stateParams.roundNumber)) ? $stateParams.roundNumber : 1;
                    return $q(function (resolve, reject) {
                        TriviaScoreboard.loadGame($stateParams.gameId, $stateParams.roundNumber).then(function (result) {
                            if(!result && $stateParams.roundNumber > 1) {
                                $rootScope.$evalAsync(function () {
                                    $state.go('app.public.game', {gameId: $stateParams.gameId, roundNumber: 1 });
                                });
                            } else if (!result) {
                                AlertConfirmService.alert('A game with this ID could not be found. Confirm your URL and try again.', 'Game could not be loaded.')
                                    .result.then(function () {
                                        $rootScope.$evalAsync(function () {
                                            $state.go('app.public.landing');
                                        });
                                    }, function (declined) {
                                        $rootScope.$evalAsync(function () {
                                            $state.go('app.public.landing');
                                        });
                                    });
                            } else {
                                resolve(result);
                            }
                        }, function (error) {
                            $rootScope.$evalAsync(function () {
                                $state.go('app.public.landing');
                            });
                            console.log(error);
                            reject(false);
                        });
                    }); 
                }
            }
        });
        
        $stateProvider.state('app.public.gameRedirect', {
            url: '/scoreboard/:gameId',
            resolve: {
                $state: '$state',
                currentGame: function($stateParams, $state) {
                    $state.go('app.public.game', {gameId: $stateParams.gameId, roundNumber: 1 });
                }
            }
        });

        $stateProvider.state('app.public.about', {
            bodyClass: 'public about',
            title: 'About Us',
            url: '/about',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/about/about.html',
                    controller: 'PublicAboutCtrl'
                }
            }
        });

        $stateProvider.state('app.public.terms', {
            title: 'Terms of Use',
            url: '/terms-of-use',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/terms/terms.html',
                    controller: 'PublicTermsCtrl'
                }
            }
        });

        $stateProvider.state('app.public.tour', {
            bodyClass: 'public tour',
            title: 'Tour',
            url: '/tour',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/tour/tour.html',
                    controller: 'PublicTourCtrl'
                }
            }
        });

        $stateProvider.state('app.public.contact', {
            bodyClass: 'public contact',
            title: 'Contact Us',
            url: '/contact',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/contact/contact.html',
                    controller: 'PublicContactCtrl'
                }
            }
        });

        $stateProvider.state('app.public.contact.confirmation', {
            title: 'Message Sent',
            url: '/message-sent'
        });
        
        /* Site Map */
        $stateProvider.state('app.public.sitemap', {
            bodyClass: 'public sitemap',
            title: 'Site Map',
            url: '/sitemap',
            views: {
                'content@app.public': {
                    templateUrl: 'app/views/public/sitemap/sitemap.html'
                }
            }
        });
        
    }]);