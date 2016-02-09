<?php namespace API;
require_once dirname(__FILE__) . '/lists.data.php';

class ListsController {
    
    static function getUsersList($app) {
        $data = ListsData::selectUsers();
        $list = ($data) ? $data : array();
        return $app->render(200, array('list' => $list));
    }
    
    static function getGroupsList($app) {
        $data = ListsData::selectGroups();
        $list = ($data) ? $data : array();
        return $app->render(200, array('list' => $list));
    }
    
    static function getTolesList($app) {
        $data = ListsData::selectRoles();
        $list = ($data) ? $data : array();
        return $app->render(200, array('list' => $list));
    }
    
    static function getVisibilityFieldsList($app) {
        $data = ListsData::selectVisibilityFields();
        $list = ($data) ? $data : array();
        return $app->render(200, array('list' => $list));
    }
    
}