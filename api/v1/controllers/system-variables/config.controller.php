<?php namespace API;
require_once dirname(__FILE__) . '/config.data.php';

use \Respect\Validation\Validator as v;


class ConfigController {
    
    static function getVariable($app, $variableId) {
        
    }
    
    static function addVariable($app) {
        if(!v::key('name', v::stringType())->validate($app->request->post()) || 
           !v::key('value', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Login failed. Check your parameters and try again.'));
        }
        
        $data = array (
            ":name" => $app->request->post('name'),
            ":value" => $app->request->post('value'),
            ":disabled" => 0,
            ":indestructable" => 0,
            ":locked" => 0,
            ":created_user_id" => 1,
            ":last_updated_by" => 1
        );
        
        $config = ConfigData::getVariableByName($data);
        if($config) {
            return $app->render(200, array('config' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select config.'));
        }
    }
    
    static function saveVariable($app) {
        $config = ConfigData::updateVariable();
        if($config) {
            return $app->render(200, array('config' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select config.'));
        }
    }
    
    static function deleteVariable($app, $variableId) {
        if(ConfigData::deleteVariable($variableId)) {
            return $app->render(200,  array('msg' => 'Config has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete config. Check your parameters and try again.'));
        }
    }
}
