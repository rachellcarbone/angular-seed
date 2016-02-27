<?php namespace API;
 require_once dirname(__FILE__) . '/system.controller.php';

class SystemRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /cron/ routes - admin db cleanup routes    
            
        $app->group('/system', $authenticateForRole('admin'), function () use ($app) {

            $app->map("/auth/delete/expired-tokens/", function () use ($app) {
                SystemController::deleteExpiredAuthTokens($app);
            })->via(['DELETE', 'POST']);

            $app->map("/auth/delete/broken-lookup-entries/", function () use ($app) {
                SystemController::deleteBrokenLookupEntries($app);
            })->via(['DELETE', 'POST']);

        });
        
    }
}