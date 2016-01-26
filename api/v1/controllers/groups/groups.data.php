<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class GroupData {
    
    static function getGroup($id) {        
        $group = DBConn::selectOne("SELECT g.id, g.group, g.desc, g.created, g.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM as_auth_groups AS g "
                        . "JOIN as_users AS u1 ON u1.id = g.created_user_id "
                        . "JOIN as_users AS u2 ON u2.id = g.last_updated_by WHERE g.id = :id;", array(':id' => $id));
        if($group) {
            $group->roles = DBConn::selectAll("SELECT r.id, r.role, r.desc "
                        . "FROM as_auth_roles AS r "
                        . "JOIN as_auth_lookup_group_role AS look ON r.id = look.auth_role_id "
                        . "WHERE look.auth_group_id = :id ORDER BY r.role;", array(':id' => $id));
        }
        return $group;
    }
    
    static function insertGroup($validGroup) {
        return DBConn::insert("INSERT INTO as_auth_groups(group, desc, created_user_id, last_updated_by) 
            VALUES (:group, :desc, :created_user_id, :last_updated_by);", $validGroup);
    }
    
    static function updateGroup($validGroup) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_groups SET group=:group, desc=:desc, "
                . "last_updated_by=:last_updated_by;", $validGroup);
    }
    
    static function deleteGroup($id) {
        $users = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_user_group WHERE auth_group_id = :id;", array('id' => $id));
        $roles = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_group_role WHERE auth_group_id = :id;", array('id' => $id));
        
        return (!$users || !$roles)  ? false :
            DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_groups WHERE id = :id LIMIT 1;", array('id' => $id));
    }
    
    static function addDefaultGroupToUser($userId) {
        $groupId = DBConn::selectOne("SELECT g.id FROM " . DBConn::prefix() . "auth_groups AS g "
                . "WHERE g.group LIKE :group;", array(':group' => 'member'));
        
        if(!$groupId) {
            $groupId = DBConn::selectOne("SELECT g.id FROM " . DBConn::prefix() . "auth_groups AS g "
                            . "WHERE g.group LIKE :group;", array(':group' => 'public'));
        }
        
        if($groupId) {
            $validGroup = array(
                ':user_id' => $userId, 
                ':auth_group_id' => $groupId->id, 
                ':created_user_id' => $userId, 
                ':last_updated_by' => $userId
            );
            return DBConn::insert("INSERT INTO as_auth_lookup_user_group(user_id, auth_group_id, created_user_id, last_updated_by) "
                    . "VALUES (:user_id, :auth_group_id, :created_user_id, :last_updated_by);", $validGroup);
        }
        
        return false;
    }
}
