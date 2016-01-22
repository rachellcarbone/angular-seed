<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class DatatablesData {
    
    public static function selectUsers() {
        $qUsers = DBConn::executeQuery("SELECT u.id, u.name_first AS firstName, u.name_last AS lastName, "
                . "u.email, u.email_verified AS verified, u.created, u.last_updated AS lastUpdated, "
                . "u.blocked, CONCAT(u1.name_first, ' ', u1.name_last) AS updatedBy "
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
        $query = 'SELECT id, group, desc, created_by, created_ts, last_updated_by, last_updated_ts '
                . 'FROM auth-group '
                . 'ORDER BY group;';
        $sthGroups = ExecuteSQLGetResultSet($query, array());
        $sthGroups->setFetchMode(PDO::FETCH_OBJ);

        $sthRoles = PrepareSQL('SELECT role.id, role.role, role.desc '
                . 'FROM auth-role AS role JOIN auth-lookup-group-role AS look '
                . 'ON role.id = look.auth_role_id '
                . 'WHERE look.auth_group_id = ? ORDER BY role.role;');

        $groups = Array();

        while($group = $sthGroups->fetch()) {
            $sthRoles->execute(array($group->id));
            $group->roles = $sthRoles->fetchAll(PDO::FETCH_OBJ);        
            array_push($groups, $group);
        }
        
        return Array();
    }
    
    public static function selectGroupRoles() {
        $query = 'SELECT id, role, desc, created_by, created_ts, last_updated_by, last_updated_ts '
                . 'FROM auth-role '
                . 'ORDER BY role;';
        $sthRoles = ExecuteSQLGetResultSet($query, array());
        $sthRoles->setFetchMode(PDO::FETCH_OBJ);

        $sthElements = PrepareSQL('SELECT elm.id, elm.identifier, elm.desc '
                . 'FROM auth-element AS elm JOIN auth-lookup-role-element AS look '
                . 'ON elm.id = look.auth_element_id '
                . 'WHERE look.auth_role_id = ? ORDER BY elm.identifier;');
        $sthGroups = PrepareSQL('SELECT gr.id, gr.group, gr.desc '
                    . 'FROM auth-group AS gr JOIN auth-lookup-group-role AS look '
                    . 'ON gr.id = look.auth_group_id '
                    . 'WHERE look.auth_role_id = ? ORDER BY group;');

        $roles = Array();

        while($role = $sthRoles->fetch()) {
            $sthGroups->execute(array($role->id));
            $role->groups = $sthGroups->fetchAll(PDO::FETCH_OBJ);   
            $sthElements->execute(array($role->id));  
            $role->elements = $sthElements->fetchAll(PDO::FETCH_OBJ);        
            array_push($roles, $role);
        }
        return Array();
    
    }
    
    public static function selectConfigVariables() {
        return DBConn::selectAll("SELECT c.id, c.name, c.value, c.created, c.last_updated AS lastUpdated, "
                . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                . "FROM " . DBConn::prefix() . "system_config AS c "
                . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = c.created_user_id "
                . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = c.last_updated_by ORDER BY c.name;");
    }
}
