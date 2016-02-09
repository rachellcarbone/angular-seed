<?php namespace API;
 require_once dirname(__FILE__) . '/fields.controller.php';

class FieldRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        //* /field/ routes - admin users only
        
        $app->group('/field', $authenticateForRole('admin'), function () use ($app) {
            
            /*
             * id
             */
            $app->map("/get/:fieldId/", function ($fieldId) use ($app) {
                FieldController::getField($app, $fieldId);
            })->via('GET', 'POST');

            /*
             * identifier, type, desc
             */
            $app->post("/insert/", function () use ($app) {
                FieldController::addField($app);
            });

            /*
             * id, identifier, type, desc
             */
            $app->post("/update/:fieldId/", function ($fieldId) use ($app) {
                FieldController::saveField($app, $fieldId);
            });

            /*
             * id
             */
            $app->post("/initialize/:fieldId/", function ($fieldId) use ($app) {
                FieldController::initializeField($app, $fieldId);
            });

            /*
             * id
             */
            $app->map("/delete/:fieldId/", function ($fieldId) use ($app) {
                FieldController::deleteField($app, $fieldId);
            })->via('DELETE', 'POST');
            
            /*
             * roleId, fieldId
             */
            $app->post("/unassign-role/", function () use ($app) {
                FieldController::unassignRole($app);
            });
            
            /*
             * roleId, fieldId
             */
            $app->post("/assign-role/", function () use ($app) {
                FieldController::assignRole($app);
            });
            
        });
    }
}