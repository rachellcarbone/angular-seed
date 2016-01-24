<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class DatatablesData {
    
    public static function selectUsers() {
        $qUsers = DBConn::executeQuery("SELECT u.id, u.name_first AS firstName, u.name_last AS lastName, "
                . "u.email, u.email_verified AS verified, u.created, u.last_updated AS lastUpdated, "
                . "u.disabled, CONCAT(u1.name_first, ' ', u1.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "users AS u "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = u.last_updated_by ORDER BY u.id;");
        
        $qGroups = DBConn::preparedQuery("SELECT grp.id, grp.group, grp.desc, look.created AS assigned "
                . "FROM " . DBConn::prefix() . "auth_groups AS grp "
                . "JOIN " . DBConn::prefix() . "auth_lookup_user_group AS look ON grp.id = look.auth_group_id "
                . "WHERE look.user_id = :id ORDER BY grp.group;");

        $users = Array();
        while($user = $qUsers->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $user->id));
            $user->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);        
            array_push($users, $user);
        }
        return $users;
    }
    
    public static function selectUserGroups() {
        $qGroups = DBConn::executeQuery("SELECT g.id, g.group, g.desc, g.created, g.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_groups AS g "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = g.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = g.last_updated_by ORDER BY g.group;");

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON r.id = look.auth_role_id "
                . "WHERE look.auth_group_id = :id ORDER BY r.role;");

        $groups = Array();

        while($group = $qGroups->fetch(\PDO::FETCH_OBJ)) {
            $qRoles->execute(array(':id' => $group->id));
            $group->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);        
            array_push($groups, $group);
        }
        
        return $groups;
    }
    
    public static function selectGroupRoles() {
        $qRoles = DBConn::executeQuery("SELECT r.id, r.role, r.desc, r.created, r.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = r.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = r.last_updated_by ORDER BY r.role;");

        $qElements = DBConn::preparedQuery("SELECT e.id, e.identifier, e.desc "
                . "FROM " . DBConn::prefix() . "auth_elements AS e "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_element AS look ON e.id = look.auth_element_id "
                . "WHERE look.auth_role_id = :id ORDER BY e.identifier;");
        
        $qGroups = DBConn::preparedQuery("SELECT g.id, g.group, g.desc "
                . "FROM " . DBConn::prefix() . "auth_groups AS g "
                . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON g.id = look.auth_group_id "
                . "WHERE look.auth_role_id = :id ORDER BY g.group;");
        
        $roles = Array();

        while($role = $qRoles->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $role->id));
            $role->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);
            
            $qElements->execute(array(':id' => $role->id));
            $role->elements = $qElements->fetchAll(\PDO::FETCH_OBJ);
            
            array_push($roles, $role);
        }
        
        return $roles;
    }
    
    public static function selectConfigVariables() {
        return DBConn::selectAll("SELECT c.id, c.name, c.value, c.created, c.last_updated AS lastUpdated, c.disabled, c.indestructable, c.locked, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "system_config AS c "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = c.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = c.last_updated_by ORDER BY c.name;");
    }
    
    public static function selectVisibilityElements() {
        $qTags = DBConn::executeQuery("SELECT e.id, e.identifier, e.type, e.desc, e.initialized, e.created, e.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "auth_elements AS e "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = e.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = e.last_updated_by ORDER BY e.identifier;");

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                . "FROM " . DBConn::prefix() . "auth_roles AS r "
                . "JOIN " . DBConn::prefix() . "auth_lookup_role_element AS look ON r.id = look.auth_role_id "
                . "WHERE look.auth_element_id = :id ORDER BY r.role;");
        
        $elements = Array();

        while($tag = $qTags->fetch(\PDO::FETCH_OBJ)) {            
            $qRoles->execute(array(':id' => $tag->id));
            $tag->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);
            
            array_push($elements, $tag);
        }
        
        return $elements;
    }
}
