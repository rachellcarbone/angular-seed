<?php namespace API;
require_once dirname(__FILE__) . '/config.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';

use \Respect\Validation\Validator as v;


class ConfigController {
    
    static function getVariable($app, $variableId) {
        $variable = ConfigData::getVariableById($variableId);
        return $app->render(200, array('variable' => $variable));
    }
    
    static function addVariable($app) {
        if(!v::key('name', v::stringType())->validate($app->request->post()) || 
           !v::key('value', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Save failed. Check your parameters and try again.'));
        }
        // Check if a variable with this name already exists
        $existing = ConfigData::getVariableByName($app->request->post('name'));
        if($existing) {
            return $app->render(400,  array('msg' => 'A system config variable with that name already exists.'));
        }
        
        // Add Cariable
        $data = array (
            ":name" => $app->request->post('name'),
            ":value" => $app->request->post('value'),
            ":created_user_id" => APIAuth::getUserId(),
            ":last_updated_by" => APIAuth::getUserId()
        );
        
        $variableId = ConfigData::insertVariable($data);
        if($variableId) {
            $config = ConfigData::getVariableById($variableId);
            return $app->render(200, array('variable' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select system config variable.'));
        }
    }
    
    static function saveVariable($app, $variableId) {
        if(!v::intVal()->validate($variableId) || 
           !v::key('name', v::stringType())->validate($app->request->post()) || 
           !v::key('value', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Update failed. Check your parameters and try again.'));
        }
        
        $savedConfig = ConfigData::getVariableById($variableId);
        if(!$savedConfig) {
            return $app->render(400, array('msg' => 'Variable doesnt seem to exist.'));
        } else if ($savedConfig->locked) {
            return $app->render(400, array('msg' => 'This config variable is locked. It cannot be changed or deleted without special permissions.'));
        }
        
        $disabled = (v::key('disabled', v::boolVal())->validate($app->request->post())) ? $app->request->post('disabled') : 0;
        
        $data = array (
            ":id" => $variableId,
            ":name" => $app->request->post('name'),
            ":value" => $app->request->post('value'),
            ":disabled" => $disabled,
            ":indestructable" => $savedConfig->indestructable,
            ":locked" => $savedConfig->locked,
            ":last_updated_by" => APIAuth::getUserId()
        );
        
        $config = ConfigData::updateVariable($data);
        if($config) {
            $config = ConfigData::getVariableById($variableId);
            return $app->render(200, array('variable' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not update system config variable.'));
        }
    }
    
    static function saveVariablePermissions($app) {
        if(!v::key('id', v::intVal())->validate($app->request->post()) ||
           !v::key('indestructable', v::boolVal())->validate($app->request->post()) || 
           !v::key('locked', v::boolVal())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Update failed. Check your parameters and try again.'));
        }
    
        $savedConfig = ConfigData::getVariableById($app->request->post('id'));
        if(!$savedConfig) {
            return $app->render(400, array('msg' => 'Variable doesnt seem to exist.'));
        }
        
        // If its locked its also indestructable
        $data = array (
            ":id" => $app->request->post('id'),
            ":indestructable" => ($app->request->post('locked')) ? 1 : $app->request->post('indestructable'),
            ":locked" => $app->request->post('locked'),
            ":last_updated_by" => APIAuth::getUserId()
        );
        
        $config = ConfigData::updateVariablePermissions($data);
        if($config) {
            $config = ConfigData::getVariableById($app->request->post('id'));
            return $app->render(200, array('variable' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not update system config variable permissions.'));
        }
    }
    
    static function deleteVariable($app, $variableId) {
        $savedConfig = ConfigData::getVariableById($variableId);
        if($savedConfig && ($savedConfig->locked || $savedConfig->indestructable)) {
            return $app->render(401, array('msg' => 'This config variable is locked or indestructable and deleted without special permissions.'));
        }
        
        if(ConfigData::deleteVariable($variableId)) {
            return $app->render(200,  array('msg' => 'System config variable has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete system config variable. Check your parameters and try again.'));
        }
    }
}
