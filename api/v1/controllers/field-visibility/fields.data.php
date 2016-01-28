<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class FieldData {
  
    public static function getField($id) {
        $field = DBConn::selectOne("SELECT f.id, f.identifier, f.type, f.desc, f.initialized, f.created, f.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_fields AS f "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = f.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = f.last_updated_by "
                . "WHERE f.id = :id LIMIT 1;", array(':id' => $id));

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON r.id = look.auth_role_id "
                . "WHERE look.auth_field_id = :id ORDER BY r.role;");
        
        if($field) {          
            $qRoles->execute(array(':id' => $field->id));
            $field->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);
        }
        
        return $field;
    }
    
    public static function getByIdentifier($identifier, $fieldId = 0) {
        $field = DBConn::selectOne("SELECT f.id, f.identifier, f.type, f.desc, f.initialized, f.created, f.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_fields AS f "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = f.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = f.last_updated_by "
                . "WHERE f.identifier = :identifier AND f.id ! = :id "
                . "LIMIT 1;", array(':identifier' => $identifier, ':id' => $fieldId));

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON r.id = look.auth_role_id "
                . "WHERE look.auth_field_id = :id ORDER BY r.role;");
        
        if($field) {          
            $qRoles->execute(array(':id' => $field->id));
            $field->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);
        }
        
        return $field;
    }
  
    public static function insertField($validField) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_fields(`identifier`, `type`, `desc`, `created_user_id`, `last_updated_by`) "
                . "VALUES (:identifier, :type, :desc, :created_user_id, :last_updated_by)", $validField);
    }
    
    public static function updateFieldInitialize($validField) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_fields SET `initialized`=:initialized WHERE `id`=:id;", $validField);
    }
    
    public static function updateField($validField) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_fields SET `identifier`=:identifier, `type`=:type, "
                . "`desc`=:desc, `last_updated_by`=:last_updated_by, initialized=NULL WHERE `id`=:id;", $validField);
    }
    
    public static function deleteField($id) {
        $roles = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_role_field WHERE auth_field_id = :id;", array('id' => $id));
        
        return (!$roles)  ? false :
            DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_fields WHERE id = :id LIMIT 1;", array('id' => $id));
    }
}
