<?php namespace API;
 require_once dirname(__FILE__) . '/fields.controller.php';

class FieldRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /field/ routes - admin users only
        
        $app->group('/field', $authenticateForRole('admin'), function () use ($app) {
            
            $app->post("/:fieldId/", function ($fieldId) use ($app) {
                FieldController::getField($app, $fieldId);
            });

            $app->post("/insert/", function () use ($app) {
                FieldController::addField($app);
            });

            $app->post("/update/:fieldId/", function ($fieldId) use ($app) {
                FieldController::saveField($app, $fieldId);
            });

            $app->map("/delete/:fieldId/", function ($fieldId) use ($app) {
                FieldController::deleteField($app, $fieldId);
            })->via('DELETE', 'POST');
            
        });
    }
}