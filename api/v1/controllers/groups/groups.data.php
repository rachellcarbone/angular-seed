<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class GroupData {
    
    static function getGroup($id) {        
        $group = DBConn::selectOne("SELECT g.id, g.group, g.slug, g.desc, g.created, g.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "auth_groups AS g "
                        . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = g.created_user_id "
                        . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = g.last_updated_by WHERE g.id = :id;", array(':id' => $id));
        if($group) {
            $group->roles = DBConn::selectAll("SELECT r.id, r.role, r.desc "
                        . "FROM " . DBConn::prefix() . "auth_roles AS r "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON r.id = look.auth_role_id "
                        . "WHERE look.auth_group_id = :id ORDER BY r.role;", array(':id' => $id));
        }
        return $group;
    }
    
    // TODO: Double check that limit one is on all selectOne calls to speed up queries
    static function selectGroupBySlug($slug, $id = 0) {        
        $group = DBConn::selectOne("SELECT g.id, g.group, g.slug, g.desc, g.created, g.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "auth_groups AS g "
                        . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = g.created_user_id "
                        . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = g.last_updated_by "
                        . "WHERE g.slug = :slug AND g.id != :id LIMIT 1;", 
                        array(':slug' => $slug, ':id' => $id));
        if($group) {
            $group->roles = DBConn::selectAll("SELECT r.id, r.role, r.desc "
                        . "FROM " . DBConn::prefix() . "auth_roles AS r "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON r.id = look.auth_role_id "
                        . "WHERE look.auth_group_id = :id ORDER BY r.role;", array(':id' => $id));
        }
        return $group;
    }
    
    static function insertGroup($validGroup) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_groups(`group`, `desc`, `slug`, `created_user_id`, `last_updated_by`) "
                . "VALUES (:group, :desc, :slug, :created_user_id, :last_updated_by);", $validGroup);
    }
    
    static function updateGroup($validGroup) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "auth_groups SET `group`=:group, `slug`=:slug, `desc`=:desc, "
                . "`last_updated_by`=:last_updated_by WHERE `id`=:id;", $validGroup);
    }
    
    static function deleteGroup($id) {
        $users = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_user_group WHERE auth_group_id = :id;", array('id' => $id));
        $roles = DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_group_role WHERE auth_group_id = :id;", array('id' => $id));
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_groups WHERE id = :id LIMIT 1;", array('id' => $id));
    }
    
    static function addDefaultGroupToUser($userId) {
        // TODO: Hook up default group to config variable
        $groupId = DBConn::selectOne("SELECT g.id FROM " . DBConn::prefix() . "auth_groups AS g "
                . "WHERE g.slug = :slug;", array(':slug' => 'member'));
        
        if(!$groupId) {
            $groupId = DBConn::selectOne("SELECT g.id FROM " . DBConn::prefix() . "auth_groups AS g "
                            . "WHERE g.group LIKE :group;", array(':group' => 'public'));
        }
        
        if($groupId) {
            $validGroup = array(
                ':user_id' => $userId, 
                ':auth_group_id' => $groupId->id, 
                ':created_user_id' => $userId
            );
            return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_lookup_user_group(user_id, auth_group_id, created_user_id) "
                    . "VALUES (:user_id, :auth_group_id, :created_user_id);", $validGroup);
        }
        
        return false;
    }
    
    static function addNewRoleToAdminGroup($roleId) {
        // TODO: Hook up super-admin group to config variable
        $groupId = DBConn::selectOne("SELECT g.id FROM " . DBConn::prefix() . "auth_groups AS g "
                . "WHERE g.slug = :slug LIMIT 1;", array(':slug' => 'super-admin'));
        
        if($groupId) {
            $validGroup = array(
                ':auth_group_id' => $groupId->id, 
                ':auth_role_id' => $roleId,
                ':created_user_id' => APIAuth::getUserId()
            );
            return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_lookup_group_role(auth_group_id, auth_role_id, created_user_id) "
                    . "VALUES (:auth_group_id, :auth_role_id, :created_user_id);", $validGroup);
        }
        
        return false;
    }
    
    static function addPublicRoleToNewGroup($groupId) {
        // TODO: Hook up super-admin group to config variable
        $roleId = DBConn::selectOne("SELECT r.id FROM " . DBConn::prefix() . "auth_roles AS r "
                . "WHERE r.slug = :slug LIMIT 1;", array(':slug' => 'public'));
        
        if($roleId) {
            $validGroup = array(
                ':auth_group_id' => $groupId, 
                ':auth_role_id' => $roleId->id,
                ':created_user_id' => APIAuth::getUserId()
            );
            return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_lookup_group_role(auth_group_id, auth_role_id, created_user_id) "
                    . "VALUES (:auth_group_id, :auth_role_id, :created_user_id);", $validGroup);
        }
        
        return false;
    }
    
    static function deleteUserGroups($userId) {
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_user_group WHERE user_id = :user_id;", array(':user_id' => $userId));
    }
}
