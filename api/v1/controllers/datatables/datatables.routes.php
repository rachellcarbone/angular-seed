<?php namespace API;
 require_once dirname(__FILE__) . '/datatables.controller.php';

class DatatableRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /datatable/admin/ routes - admin users only
        
        $app->group('/datatable/admin', $authenticateForRole('admin'), function () use ($app) {

            $app->post("/users", function () use ($app) {
                DatatablesController::getUsers($app);
            });

            $app->post("/user-groups", function () use ($app) {
                DatatablesController::getUserGroups($app);
            });

            $app->post("/group-roles", function () use ($app) {
                DatatablesController::getGroupRoles($app);
            });

            $app->post("/system-variables", function () use ($app) {
                DatatablesController::getConfigVariables($app);
            });

            $app->post("/visibility-fields", function () use ($app) {
                DatatablesController::getVisibilityFields($app);
            });

            $app->post("/trivia-games", function () use ($app) {
                DatatablesController::getCurrentGames($app);
            });

            $app->post("/trivia-teams", function () use ($app) {
                DatatablesController::getTriviaTeams($app);
            });

            $app->post("/trivia-venues", function () use ($app) {
                DatatablesController::getTriviaVenues($app);
            });

            $app->post("/game-checkins/:teamId", function ($teamId) use ($app) {
                DatatablesController::getTeamGameCheckins($app, $teamId);
            });
            
        });
        
        // Games
        
        $app->group('/datatable', $authenticateForRole('public'), function () use ($app) {
            
            $app->map("/games/", function () use ($app) {
                DatatablesController::getGames($app);
            })->via('GET', 'POST');
            
            $app->map("/games/host/:hostId/", function ($hostId) use ($app) {
                DatatablesController::getHostGames($app, $hostId);
            })->via('GET', 'POST');
            
            $app->map("/games/venue/:venueId/", function ($venueId) use ($app) {
                DatatablesController::getVenueGames($app, $venueId);
            })->via('GET', 'POST');
            
            $app->map("/games/team/:teamId/", function ($teamId) use ($app) {
                DatatablesController::getTeamGames($app, $teamId);
            })->via('GET', 'POST');
            
            $app->map("/game/simple-scoreboard/:gameId/:roundNumber", function ($gameId, $roundNumber) use ($app) {
                DatatablesController::getGameSimpleScoreboard($app, $gameId, $roundNumber);
            })->via('GET', 'POST');
            
        });
    }
}