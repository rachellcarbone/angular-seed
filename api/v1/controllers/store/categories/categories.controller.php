<?php namespace API;
require_once dirname(__FILE__) . '/categories.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';

use \Respect\Validation\Validator as v;


class CategoryController {

    static function getCategory($app, $categoryId) {
        if(!v::intVal()->validate($categoryId)) {
            return $app->render(400,  array('msg' => 'Could not select product category. Check your parameters and try again.'));
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
        return preg_replace('/[^a-zA-Z0-9-_.]/', '', str_replace(' ', '-', strtolower(trim($string))));
    }
    
    static function unassignField($app) {
        if(!v::key('fieldId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not unassign role from field. Check your parameters and try again.'));
        } 
        
        $data = array (
            ':auth_field_id' => $app->request->post('fieldId'),
            ':auth_role_id' => $app->request->post('roleId')
        );
        
        if(RoleData::deleteFieldAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been unassigned from field.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not unassign role from field.'));
        }
    }
    
    static function assignField($app) {
        if(!v::key('fieldId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not assign role from field. Check your parameters and try again.'));
        }
        
        $data = array (
            ':auth_field_id' => $app->request->post('fieldId'),
            ':auth_role_id' => $app->request->post('roleId'),
            ":created_user_id" => APIAuth::getUserId()
        );
        
        if(RoleData::insertFieldAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been assigned to field.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not assign role to field.'));
        }
    }
    
    static function unassignGroup($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not unassign role from group. Check your parameters and try again.'));
        } 
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':auth_role_id' => $app->request->post('roleId')
        );
        
        if(RoleData::deleteGroupAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been unassigned from group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not unassign role from group.'));
        }
    }
    
    static function assignGroup($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not assign role from group. Check your parameters and try again.'));
        }
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':auth_role_id' => $app->request->post('roleId'),
            ":created_user_id" => APIAuth::getUserId()
        );
        
        if(RoleData::insertGroupAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been assigned to group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not assign role to group.'));
        }
    }
    
}
