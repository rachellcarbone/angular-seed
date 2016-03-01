<?php namespace API;
 require_once dirname(__FILE__) . '/actions.controller.php';

class ActionRoutes {
    
    static function addRoutes($app, $authenticateForAction) {
            
        //* /action/ routes - admin users only
        
        $app->group('/track-action', $authenticateForAction('public'), function () use ($app) {

            /*
             * code, action
             * 
             * code = (mysql TEXT column) user entered input (optional) 
             * action = (mysql TEXT column) page identifier or description sent by the interface
             */
            $app->post("/insert/", function () use ($app) {
                ActionController::addAction($app);
            });
        });
    }
}