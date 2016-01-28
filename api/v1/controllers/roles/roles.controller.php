<?php namespace API;
require_once dirname(__FILE__) . '/roles.data.php';
require_once dirname(dirname(__FILE__)) . '/groups/groups.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';

use \Respect\Validation\Validator as v;


class RoleController {

    static function getRole($app, $roleId) {
        if(!v::intVal()->validate($roleId)) {
            return $app->render(400,  array('msg' => 'Could not select role. Check your parameters and try again.'));
        }
        $role = RoleData::getRole($roleId);
        if($role) {
            return $app->render(200, array('role' => $role));
        } else {
            return $app->render(400,  array('msg' => 'Could not select role.'));
        }
    }
    
    static function addRole($app) {
        // Validate parameters
        if(!v::key('role', v::stringType())->validate($app->request->post()) || 
           !v::key('desc', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Add role failed. Check your parameters and try again.'));
        }
        
        // Verify a unique slug
        $slug = self::makeSlug($app->request->post('role'));
        $existing = RoleData::selectRoleBySlug($slug);
        if ($existing) {
            return $app->render(400,  array('msg' => 'Could not add role. A role with that name already exists.', 'role' => $existing));
        }
        
        // Add the verifed role   
        $roleId = RoleData::insertRole(array (
            ":role" => $app->request->post('role'),
            ":slug" => self::makeSlug($app->request->post('role')),
            ":desc" => $app->request->post('desc'),
            ":created_user_id" => APIAuth::getUserId(),
            ":last_updated_by" => APIAuth::getUserId()
        ));
        
        // Return success
        if($roleId) {
            GroupData::addNewRoleToAdminGroup($roleId);
            $role = RoleData::getRole($roleId);
            return $app->render(200, array('role' => $role));
        } else {
            return $app->render(400,  array('msg' => 'Could not add new role.'));
        }
    }
    
    static function saveRole($app, $roleId) {
        // Validate parameters
        if(!v::intVal()->validate($roleId) || 
           !v::key('role', v::stringType())->validate($app->request->post()) || 
           !v::key('desc', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Update role failed. Check your parameters and try again.'));
        }
        
        // Verify a unique slug
        $slug = self::makeSlug($app->request->post('role'));
        $existing = RoleData::selectRoleBySlug($slug, $roleId);
        if ($existing) {
            return $app->render(400,  array('msg' => 'Could not edit role. A role with that name already exists.', 'role' => $existing));
        }
        
        // Update the verifed role
        $saved = RoleData::updateRole(array (
            ':id' => $roleId,
            ":role" => $app->request->post('role'),
            ":slug" => $slug,
            ":desc" => $app->request->post('desc'),
            ":last_updated_by" => APIAuth::getUserId()
        ));
        
        // Return success
        if($saved) {
            $role = RoleData::getRole($roleId);
            return $app->render(200, array('role' => $role));
        } else {
            return $app->render(400,  array('msg' => 'Could not update role.'));
        }
    }
    
    static function deleteRole($app, $roleId) {
        if(!v::intVal()->validate($roleId)) {
            return $app->render(400,  array('msg' => 'Could not delete role. Check your parameters and try again.'));
        } else if(RoleData::deleteRole($roleId)) {
            return $app->render(200,  array('msg' => 'Role has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete role.'));
        }
    }

    static function makeSlug($string) {
        return preg_replace('/[^a-zA-Z0-9-_.]/', '', str_replace(' ', '-', trim($string)));
    }
    
}
