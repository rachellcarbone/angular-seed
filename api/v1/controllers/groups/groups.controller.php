<?php namespace API;
require_once dirname(__FILE__) . '/groups.data.php';

use \Respect\Validation\Validator as v;


class GroupController {

    static function getGroup($app, $groupId) {
        $groups = GroupData::getGroup($groupId);
        if($groups) {
            return $app->render(200, array('groups' => $groups));
        } else {
            return $app->render(400,  array('msg' => 'Could not select group.'));
        }
    }
    
    static function addGroup($app) {
        $groups = GroupData::insertGroup();
        if($groups) {
            return $app->render(200, array('groups' => $groups));
        } else {
            return $app->render(400,  array('msg' => 'Could not select group.'));
        }
    }
    
    static function saveGroup($app) {
        $groups = GroupData::updateGroup();
        if($groups) {
            return $app->render(200, array('groups' => $groups));
        } else {
            return $app->render(400,  array('msg' => 'Could not select group.'));
        }
    }
    
    static function deleteGroup($app, $groupId) {
        if(GroupData::deleteGroup($groupId)) {
            return $app->render(200,  array('msg' => 'Group has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete group. Check your parameters and try again.'));
        }
    }
}
