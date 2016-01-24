<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class RoleData {
  
    public static function getRole($id) {
        $role = DBConn::selectOne("SELECT r.id, r.role, r.desc, r.created, r.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM as_auth_roles AS r "
                . "JOIN as_users AS u1 ON u1.id = r.created_user_id "
                . "JOIN as_users AS u2 ON u2.id = r.last_updated_by WHERE r.id = :id;", array(':id' => $id));

        $qFields = DBConn::preparedQuery("SELECT e.id, e.identifier, e.desc "
                . "FROM as_auth_fields AS e "
                . "JOIN as_auth_lookup_role_field AS look ON e.id = look.auth_field_id "
                . "WHERE look.auth_role_id = :id ORDER BY e.identifier;");
        
        $qGroups = DBConn::preparedQuery("SELECT g.id, g.group, g.desc "
                . "FROM as_auth_groups AS g "
                . "JOIN as_auth_lookup_group_role AS look ON g.id = look.auth_group_id "
                . "WHERE look.auth_role_id = :id ORDER BY g.group;");
        
        if($role) {
            $qGroups->execute(array(':id' => $id));
            $role->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);
            
            $qFields->execute(array(':id' => $id));
            $role->elements = $qFields->fetchAll(\PDO::FETCH_OBJ);
        }
        return $role;
    }
  
    public static function insertRole($validRole) {
        return DBConn::insert("INSERT INTO as_auth_roles(role, slug, desc, created_user_id, last_updated_by) "
                . "VALUES (:role, :slug, :desc, :created_user_id, :last_updated_by)", $validRole);
    }
    
    public static function updateRole($validRole) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_roles SET role=:role, slug=:slug, "
                . "desc=:desc, last_updated_by=:last_updated_by;", $validRole);
    }
    
    public static function deleteRole($id) {
        $fields = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_role_field WHERE auth_role_id = :id;", array('id' => $id));
        $groups = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_group_role WHERE auth_role_id = :id;", array('id' => $id));
        
        return (!$fields || !$groups)  ? false :
            DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_roles WHERE id = :id LIMIT 1;", array('id' => $id));
    }
}
