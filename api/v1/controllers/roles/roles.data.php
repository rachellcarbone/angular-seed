<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class RoleData {
  
    // TODO: Add call that will verify that an admin role exists, 
    // that the adming group has the role, 
    // and the admin role is on every element. 
    
    public static function getRole($id) {
        $role = DBConn::selectOne("SELECT r.id, r.role, r.desc, r.slug, r.created, r.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = r.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = r.last_updated_by WHERE r.id = :id LIMIT 1;", array(':id' => $id));

        $qFields = DBConn::preparedQuery("SELECT e.id, e.identifier, e.type, e.desc "
                . "FROM " . DBConn::prefix() . "auth_fields AS e "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON e.id = look.auth_field_id "
                . "WHERE look.auth_role_id = :id ORDER BY e.identifier;");
        
        $qGroups = DBConn::preparedQuery("SELECT g.id, g.group, g.desc "
                . "FROM " . DBConn::prefix() . "auth_groups AS g "
                . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON g.id = look.auth_group_id "
                . "WHERE look.auth_role_id = :id ORDER BY g.group;");
        
        // TODDO: Add roles to this group query for a really robust view
        
        if($role) {
            $qGroups->execute(array(':id' => $id));
            $role->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);
            
            $qFields->execute(array(':id' => $id));
            $role->elements = $qFields->fetchAll(\PDO::FETCH_OBJ);
        }
        return $role;
    }
  
    public static function selectRoleBySlug($slug, $id = 0) {
        $role = DBConn::selectOne("SELECT r.id, r.role, r.desc, r.slug, r.created, r.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = r.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = r.last_updated_by "
                . "WHERE r.slug = :slug AND r.id != :id LIMIT 1;", 
                array(':slug' => $slug, ':id' => $id));

        $qFields = DBConn::preparedQuery("SELECT e.id, e.identifier, e.type, e.desc "
                . "FROM " . DBConn::prefix() . "auth_fields AS e "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON e.id = look.auth_field_id "
                . "WHERE look.auth_role_id = :id ORDER BY e.identifier;");
        
        $qGroups = DBConn::preparedQuery("SELECT g.id, g.group, g.desc "
                . "FROM " . DBConn::prefix() . "auth_groups AS g "
                . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON g.id = look.auth_group_id "
                . "WHERE look.auth_role_id = :id ORDER BY g.group;");
        
        // TODDO: Add roles to this group query for a really robust view
        
        if($role) {
            $qGroups->execute(array(':id' => $id));
            $role->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);
            
            $qFields->execute(array(':id' => $id));
            $role->elements = $qFields->fetchAll(\PDO::FETCH_OBJ);
        }
        return $role;
    }
  
    public static function insertRole($validRole) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_roles(`role`, `slug`, `desc`, `created_user_id`, `last_updated_by`) "
                . "VALUES (:role, :slug, :desc, :created_user_id, :last_updated_by);", $validRole);
    }
    
    public static function updateRole($validRole) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_roles SET `role`=:role, `slug`=:slug, "
                . "`desc`=:desc, `last_updated_by`=:last_updated_by WHERE `id`=:id;", $validRole);
    }
    
    public static function deleteRole($id) {
        $fields = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_role_field WHERE `auth_role_id`= :id;", array(':id' => $id));
        $groups = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_group_role WHERE `auth_role_id` = :id;", array(':id' => $id));
        
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_roles WHERE `id` = :id LIMIT 1;", array(':id' => $id));
    }
}
