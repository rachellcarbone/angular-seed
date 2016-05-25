<?php namespace API;
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';
require_once dirname(dirname(__FILE__)) . '/groups/groups.data.php';

class UserData {
    
    static function selectUsers() {
        $qUsers = DBConn::executeQuery("SELECT u.id, u.name_first AS nameFirst, u.name_last AS nameLast, "
                . "u.email, u.email_verified AS verified, u.phone, u.created, u.last_updated AS lastUpdated, "
                . "u.disabled, CONCAT(u1.name_first, ' ', u1.name_last) AS updatedBy, t.name AS team, t.id AS teamId "
                . "FROM " . DBConn::prefix() . "users AS u "
                . "LEFT JOIN " . DBConn::prefix() . "team_members AS tm ON tm.user_id = u.id "
                . "LEFT JOIN " . DBConn::prefix() . "teams AS t ON t.id = tm.team_id "
                . "LEFT JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = u.last_updated_by ORDER BY u.id;");
        
        $qGroups = DBConn::preparedQuery("SELECT grp.id, grp.group, grp.desc, look.created AS assigned "
                . "FROM " . DBConn::prefix() . "auth_groups AS grp "
                . "JOIN " . DBConn::prefix() . "auth_lookup_user_group AS look ON grp.id = look.auth_group_id "
                . "WHERE look.user_id = :id GROUP BY grp.id ORDER BY grp.group;");

        $users = Array();
        while($user = $qUsers->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $user->id));
            $user->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);        
            array_push($users, $user);
        }
        return $users;
    }
    
    static function selectOtherUsersWithEmail($email, $id = 0) {
        return DBConn::selectAll("SELECT id FROM " . DBConn::prefix() . "users WHERE email = :email AND id != :id;", 
                    array(':email' => $email, ':id' => $id), \PDO::FETCH_COLUMN);
    }
    
    static function selectUserById($id) {
        $user = DBConn::selectOne("SELECT u.id, u.name_first AS nameFirst, u.name_last AS nameLast, "
                . "u.email, u.email_verified AS verified, u.phone, u.created, u.last_updated AS lastUpdated, "
                . "u.disabled, CONCAT(u1.name_first, ' ', u1.name_last) AS updatedBy, t.name AS team, t.id AS teamId "
                . "FROM " . DBConn::prefix() . "users AS u "
                . "LEFT JOIN " . DBConn::prefix() . "team_members AS tm ON tm.user_id = u.id "
                . "LEFT JOIN " . DBConn::prefix() . "teams AS t ON t.id = tm.team_id "
                . "LEFT JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = u.last_updated_by "
                . "WHERE u.id = :id LIMIT 1;", array(':id' => $id));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                . "WHERE ug.user_id = :id;", array(':id' => $id), \PDO::FETCH_COLUMN);
            
            $user->groups = DBConn::selectAll("SELECT grp.id, grp.group, grp.desc, look.created AS assigned "
                . "FROM " . DBConn::prefix() . "auth_groups AS grp "
                . "JOIN " . DBConn::prefix() . "auth_lookup_user_group AS look ON grp.id = look.auth_group_id "
                . "WHERE look.user_id = :id GROUP BY grp.id ORDER BY grp.group;", array(':id' => $id));
        }
        return $user;
    }
    
    static function selectUserByIdentifierToken($identifier) {
        $user = DBConn::selectOne("SELECT u.id, name_first AS nameFirst, name_last AS nameLast, email, phone, token AS apiToken, identifier AS apiKey "
                . "FROM " . DBConn::prefix() . "tokens_auth AS t "
                . "JOIN " . DBConn::prefix() . "users AS u ON u.id = t.user_id "
                . "WHERE identifier = :identifier AND t.expires > NOW() "
                . "AND u.disabled IS NULL;", array(':identifier' => $identifier));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT DISTINCT(gr.auth_role_id) FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id ORDER BY gr.auth_role_id;", array(':id' => $user->id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
  
    static function insertUser($validUser) {
        $userId = DBConn::insert("INSERT INTO " . DBConn::prefix() . "users(name_first, name_last, email, password) "
                . "VALUES (:name_first, :name_last, :email, :password);", $validUser);
        if($userId) {
            GroupData::addDefaultGroupToUser($userId);
        }
        return $userId;
    }
    
    static function updateUser($validUser) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "users SET name_first=:name_first, "
                . "name_last=:name_last, email=:email, phone=:phone WHERE id = :id;", $validUser);
    }
    
    static function disableUser($userId) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "users SET disabled=NOW() WHERE id = :id AND disabled IS NULL;", array(':id' => $userId));
    }
    
    static function enableUser($userId) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "users SET disabled=NULL WHERE id = :id AND disabled IS NOT NULL;", array(':id' => $userId));
    }
    
    static function deleteUser($userId) {
        $deleted = GroupData::deleteUserGroups($userId);
        return (!$deleted) ? false :
            DBConn::delete("DELETE FROM " . DBConn::prefix() . "users WHERE id = :id LIMIT 1;", array(':id' => $userId));
    }
    
    static function insertGroupAssignment($data) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "auth_lookup_user_group(`auth_group_id`, `user_id`, `created_user_id`) "
                . "VALUES (:auth_group_id, :user_id, :created_user_id);", $data);
    }
                    
    static function deleteGroupAssignment($data) {
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "auth_lookup_user_group "
                . "WHERE user_id = :user_id AND auth_group_id = :auth_group_id;", $data);
    }
}
