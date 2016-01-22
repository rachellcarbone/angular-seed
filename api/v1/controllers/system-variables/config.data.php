<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class ConfigData {
  
    public static function getConfig($id) {
        return false;
    }
  
    public static function insertConfig($validConfig) {
        return false;
    }
    
    public static function updateConfig($validConfig) {
        return false;
    }
    
    public static function deleteConfig($id) {
        $config = array(':id' => $id, ':marked_for_deletion' => $id);
        return DBConn::update("UPDATE " . DBConn::prefix() . "system_config SET marked_for_deletion=:marked_for_deletion WHERE id = :id;", $validUser);
    }
}
