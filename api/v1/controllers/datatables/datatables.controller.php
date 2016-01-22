<?php namespace API;
require_once dirname(__FILE__) . '/datatables.data.php';

class DatatablesController {
    static function getUsers($app) {
        $data = DatatablesData::selectUsers();
        $table = ($data) ? $data : array();
        return $app->render(200, array('table' => $table ));
    }
    
    static function getUserGroups($app) {
        $data = DatatablesData::selectUserGroups();
        $table = ($data) ? $data : array();
        return $app->render(200, array('table' => $table ));
    }
    
    static function getGroupRoles($app) {
        $data = DatatablesData::selectGroupRoles();
        $table = ($data) ? $data : array();
        return $app->render(200, array('table' => $table ));
    }
    
    static function getConfigVariables($app) {
        $data = DatatablesData::selectConfigVariables();
        $table = ($data) ? $data : array();
        return $app->render(200, array('table' => $table ));
    }
    
}
