<?php namespace API;
require_once dirname(__FILE__) . '/config.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';

use \Respect\Validation\Validator as v;


class ConfigController {
    
    static function getVariable($app, $variableId) {
        if(!v::intVal()->validate($variableId)) {
            return $app->render(400,  array('msg' => 'Could not select system config variable.'));
        }
        $variable = ConfigData::getVariableById($variableId);
        if($variable) {
            return $app->render(200, array('variable' => $variable));
        } else {
            return $app->render(400,  array('msg' => 'System config variable could not be found.'));
        }
    }
    
    static function getVariableByName($app) {
        if(!v::key('name', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not select system config variable.'));
        }
        $variable = ConfigData::getVariableByName($app->request->post('name'));
        if($variable) {
            return $app->render(200, array('variable' => $variable));
        } else {
            return $app->render(400,  array('msg' => 'System config variable could not be found.'));
        }
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
        
        $disabled = $savedConfig->disabled; 
        if (v::key('disabled')->validate($app->request->post())) {
            // TODO: Implement cusitom boolean Respect\Validator
            // Converting to boolean did not work well, 
            // This allows a wider range of true false values
            $disabled = ($app->request->post('disabled') === 1 || 
                        $app->request->post('disabled') === '1' || 
                        $app->request->post('disabled') === true || 
                        $app->request->post('disabled') === 'true') ? 1 : 0;
        }
        
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
    
    static function saveVariablePermissions($app, $variableId) {
        if(!v::intVal()->validate($variableId) ||
           !v::key('indestructable')->validate($app->request->post()) || 
           !v::key('locked')->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(401, array('msg' => 'Update failed. Check your parameters and try again.'));
        }
    
        $savedConfig = ConfigData::getVariableById($variableId);
        if(!$savedConfig) {
            return $app->render(400, array('msg' => 'Variable doesnt seem to exist.'));
        }
        
        $indestructable = $savedConfig->indestructable; 
        // Converting to boolean did not work well, 
        // This allows a wider range of true false values
        $indestructable = ($app->request->post('indestructable') === 1 || 
                    $app->request->post('indestructable') === '1' || 
                    $app->request->post('indestructable') === true || 
                    $app->request->post('indestructable') === 'true') ? 1 : 0;
        
        $locked = $savedConfig->locked; 
        // Converting to boolean did not work well, 
        // This allows a wider range of true false values
        $locked = ($app->request->post('locked') === 1 || 
                    $app->request->post('locked') === '1' || 
                    $app->request->post('locked') === true || 
                    $app->request->post('locked') === 'true') ? 1 : 0;
        
        // If its locked its also indestructable
        $data = array (
            ":id" => $variableId,
            ":indestructable" => ($locked) ? 1 : $indestructable,
            ":locked" => $locked,
            ":last_updated_by" => APIAuth::getUserId()
        );
        
        $config = ConfigData::updateVariablePermissions($data);
        if($config) {
            $config = ConfigData::getVariableById($variableId);
            return $app->render(200, array('variable' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not update system config variable permissions.'));
        }
    }
    
    static function deleteVariable($app, $variableId) {
        if(!v::intVal()->validate($variableId)) {
            return $app->render(400,  array('msg' => 'Could not find system config variable.'));
        }
        
        $savedConfig = ConfigData::getVariableById($variableId);
        if($savedConfig && ($savedConfig->locked || $savedConfig->indestructable)) {
            return $app->render(401, array('msg' => 'This config variable is locked or indestructable and cannot deleted without special permissions.'));
        }
        
        if(ConfigData::deleteVariable($variableId)) {
            return $app->render(200,  array('msg' => 'System config variable has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete system config variable. Check your parameters and try again.'));
        }
    }
}
