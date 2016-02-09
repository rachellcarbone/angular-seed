<?php namespace API;
require_once dirname(__FILE__) . '/groups.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';

use \Respect\Validation\Validator as v;


class GroupController {

    static function getGroup($app, $groupId) {
        if(!v::intVal()->validate($groupId)) {
            return $app->render(400,  array('msg' => 'Could not select group. Check your parameters and try again.'));
        }
        $group = GroupData::getGroup($groupId);
        if($group) {
            return $app->render(200, array('group' => $group));
        } else {
            return $app->render(400,  array('msg' => 'Could not select group.'));
        }
    }
    
    static function addGroup($app) {
        // Validate parameters
        if(!v::key('group', v::stringType())->validate($app->request->post()) || 
           !v::key('desc', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Add failed. Check your parameters and try again.'));
        }
        
        // Verify a unique slug
        $slug = self::makeSlug($app->request->post('group'));
        $existing = GroupData::selectGroupBySlug($slug);
        if ($existing) {
            return $app->render(400,  array('msg' => 'Could not add group. A group with that name already exists.', 'group' => $existing));
        }
        
        // Add the verifed group   
        $groupId = GroupData::insertGroup(array(
            ":group" => $app->request->post('group'),
            ":slug" => $slug,
            ":desc" => $app->request->post('desc'),
            ":created_user_id" => APIAuth::getUserId(),
            ":last_updated_by" => APIAuth::getUserId()
        ));
        
        // Return success
        if($groupId) {
            GroupData::addPublicRoleToNewGroup($groupId);
            $group = GroupData::getGroup($groupId);
            return $app->render(200, array('group' => $group));
        } else {
            return $app->render(400,  array('msg' => 'Could not add group.'));
        }
    }
    
    static function saveGroup($app, $groupId) {
        // Validate parameters
        if(!v::intVal()->validate($groupId) || 
           !v::key('group', v::stringType())->validate($app->request->post()) || 
           !v::key('desc', v::stringType())->validate($app->request->post())) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Update failed. Check your parameters and try again.'));
        }
        
        // Verify a unique slug
        $slug = self::makeSlug($app->request->post('group'));
        $existing = GroupData::selectGroupBySlug($slug, $groupId);
        if ($existing) {
            return $app->render(400,  array('msg' => 'Could not update group. A group with that name already exists.', 'group' => $existing));
        }
        
        // Update the verifed group   
        $saved = GroupData::updateGroup(array(
            ':id' => $groupId,
            ":group" => $app->request->post('group'),
            ":slug" => $slug,
            ":desc" => $app->request->post('desc'),
            ":last_updated_by" => APIAuth::getUserId()
        ));
        
        // Return success
        if($saved) {
            $group = GroupData::getGroup($groupId);
            return $app->render(200, array('group' => $group));
        } else {
            return $app->render(400,  array('msg' => 'Could not update group.'));
        }
    }
    
    static function deleteGroup($app, $groupId) {
        if(!v::intVal()->validate($groupId)) {
            return $app->render(400,  array('msg' => 'Could not delete group. Check your parameters and try again.'));
        } else if(GroupData::deleteGroup($groupId)) {
            return $app->render(200,  array('msg' => 'Group has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete group.'));
        }
    }
    
    static function makeSlug($string) {
        return preg_replace('/[^a-zA-Z0-9-_.]/', '', str_replace(' ', '-', strtolower(trim($string))));
    }
    
    static function unassignRole($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not unassign role from group. Check your parameters and try again.'));
        } 
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':auth_role_id' => $app->request->post('roleId')
        );
        
        if(GroupData::deleteRoleAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been unassigned from group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not unassign role from group.'));
        }
    }
    
    static function assignRole($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('roleId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not assign role from group. Check your parameters and try again.'));
        }
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':auth_role_id' => $app->request->post('roleId'),
            ":created_user_id" => APIAuth::getUserId()
        );
        
        if(GroupData::insertRoleAssignment($data)) {
            return $app->render(200,  array('msg' => 'Role has been assigned from group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not assign role to group.'));
        }
    }
}
