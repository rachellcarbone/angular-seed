<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class ConfigData {
  
    public static function getVariableByName($name, $id = 0) {
        return DBConn::selectAll("SELECT c.id, c.name, c.value, c.disabled, c.indestructible, c.locked "
                . "FROM " . DBConn::prefix() . "system_config AS c "
                . "WHERE c.name = :name AND c.id != :id;", array(':name' => $name, ':id' => $id));
    }
  
    public static function getVariableById($id) {
        return DBConn::selectOne("SELECT c.id, c.name, c.value, c.disabled, c.indestructible, c.locked "
                . "FROM " . DBConn::prefix() . "system_config AS c "
                . "WHERE c.id = :id;", array(':id' => $id));
    }
  
    public static function insertVariable($validConfig) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "system_config(name, value, created_user_id, last_updated_by) "
                . "VALUES (:name, :value, :created_user_id, :last_updated_by);", $validConfig);
    }
    
    public static function updateVariable($validConfig) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "system_config SET name=:name, value=:value, "
                . "last_updated_by=:last_updated_by, disabled=:disabled, indestructible=:indestructible, locked=:locked "
                . "WHERE id=:id AND locked != 1;", $validConfig);
    }
    
    public static function updateVariablePermissions($validConfig) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "system_config SET last_updated_by=:last_updated_by, "
                . "indestructible=:indestructible, locked=:locked WHERE id=:id;", $validConfig);
    }
    
    public static function deleteVariable($id) {
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "system_config WHERE id = :id AND locked != 1;",  array(':id' => $id));
    }
}
