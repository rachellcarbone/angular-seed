<?php namespace API;
require_once dirname(__FILE__) . '/roles.data.php';

use \Respect\Validation\Validator as v;


class RoleController {

    static function getRole($app, $roleId) {
        $roles = RoleData::getRole($roleId);
        if($roles) {
            return $app->render(200, array('roles' => $roles));
        } else {
            return $app->render(400,  array('msg' => 'Could not select role.'));
        }
    }
    
    static function addRole($app) {
        $roles = RoleData::insertRole();
        if($roles) {
            return $app->render(200, array('roles' => $roles));
        } else {
            return $app->render(400,  array('msg' => 'Could not select role.'));
        }
    }
    
    static function saveRole($app) {
        $roles = RoleData::updateRole();
        if($roles) {
            return $app->render(200, array('roles' => $roles));
        } else {
            return $app->render(400,  array('msg' => 'Could not select role.'));
        }
    }
    
    static function deleteRole($app, $roleId) {
        if(RoleData::deleteRole($roleId)) {
            return $app->render(200,  array('msg' => 'Role has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete role. Check your parameters and try again.'));
        }
    }
}
