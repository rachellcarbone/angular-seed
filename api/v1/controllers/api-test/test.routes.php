<?php namespace API;
 require_once dirname(__FILE__) . '/test.controller.php';

class TestRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /test/ routes - publicly accessable test route
        
        $app->group('/api-test', $authenticateForRole('public'), function () use ($app) {

            $app->map("/get/status/", function () use ($app) {
                TestController::getApiStatus($app);
            })->via('GET', 'POST');
        
        });
    }
}