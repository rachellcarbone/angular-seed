<?php namespace API;
 require_once dirname(__FILE__) . '/emails.controller.php';

class EmailRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        $app->group('/send-email', $authenticateForRole('public'), function () use ($app) {
            
            /*
             * token
             */
            $app->map("/validate-token/:token", function ($token) use ($app) {
                EmailController::validateInviteToken($app, $token);
            })->via('GET', 'POST');
            
            /*
             * email, nameFirst (optional), nameLast (optional), phone (optional)
             */
            $app->map("/invite-player/", function () use ($app) {
                EmailController::sendPlayerInviteEmail($app);
            })->via('GET', 'POST');
            
            /*
             * teamId, email, nameFirst (optional), nameLast (optional), phone (optional)
             */
            $app->map("/team-invite/", function () use ($app) {
                EmailController::sendTeamInviteEmail($app);
            })->via('GET', 'POST');
            
            /*
             * teamId, userId, inviteToken
             */
            $app->map("/team-invite/accept/", function () use ($app) {
                EmailController::acceptTeamInvite($app);
            })->via('GET', 'POST');
            
            /*
             * teamId, userId, inviteToken
             */
            $app->map("/team-invite/decline/", function () use ($app) {
                EmailController::declineTeamInvite($app);
            })->via('GET', 'POST');
            
        });
    }
}