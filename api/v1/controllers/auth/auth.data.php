<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class AuthData {
    
  
    static function insertUser($validUser) {
        $userId = DBConn::insert("INSERT INTO " . DBConn::prefix() . "users(name_first, name_last, email, password) "
                . "VALUES (:name_first, :name_last, :email, :password);", $validUser);
        if($userId) {
            GroupData::addDefaultGroupToUser($userId);
        }
        return $userId;
    }
  
    static function insertFacebookUser($validUser) {
        $userId = DBConn::insert("INSERT INTO " . DBConn::prefix() . "users(name_first, name_last, email, facebook_id) "
                . "VALUES (:name_first, :name_last, :email, :facebook_id);", $validUser);
        if($userId) {
            GroupData::addDefaultGroupToUser($userId);
        }
        return $userId;
    }
    
    static function insertAuthToken($validToken) {
        return DBConn::insert('INSERT INTO ' . DBConn::prefix() . 'tokens_auth(identifier, token, user_id, expires, ip_address, user_agent) '
                . 'VALUES (:identifier, :token, :user_id, :expires, :ip_address, :user_agent);', $validToken);
    }
    
    static function insertLoginLocation($validLog) {
        return DBConn::insert('INSERT INTO ' . DBConn::prefix() . 'logs_login_location(user_id, ip_address, user_agent) '
                . 'VALUES (:user_id, :ip_address, :user_agent);', $validLog);
    }
    
    static function deleteAuthToken($identifier) {
        return DBConn::delete('DELETE FROM ' . DBConn::prefix() . 'tokens_auth WHERE identifier = :identifier;', $identifier);
    }
    
    static function deleteExpiredAuthTokens() {
        return DBConn::executeQuery('DELETE FROM ' . DBConn::prefix() . 'tokens_auth WHERE expires < NOW();');
    }
    
    static function updateUserFacebookId($validUser) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "users SET facebook_id = :facebook_id WHERE id = :id;", $validUser);
    }
    
    static function selectUserById($id) {
        $user = DBConn::selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email "
                . "FROM " . DBConn::prefix() . "users WHERE id = :id LIMIT 1;", array(':id' => $id));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id;", array(':id' => $id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
    
    static function selectUserByFacebookId($facebookId) {
        $user = DBConn::selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email "
                . "FROM " . DBConn::prefix() . "users WHERE facebook_id = :facebook_id LIMIT 1;", array(':facebook_id' => $facebookId));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id;", array(':id' => $user->id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
    
    static function selectUserByEmail($email) {
        $user = DBConn::selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email, password "
                . "FROM " . DBConn::prefix() . "users WHERE email = :email LIMIT 1;", array(':email' => $email));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id;", array(':id' => $user->id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
    
    static function selectUserByIdentifierToken($identifier) {
        $user = DBConn::selectOne("SELECT u.id, name_first AS nameFirst, name_last AS nameLast, email, token AS apiToken, identifier AS apiKey "
                . "FROM " . DBConn::prefix() . "tokens_auth AS t "
                . "JOIN " . DBConn::prefix() . "users AS u ON u.id = t.user_id "
                . "WHERE identifier = :identifier AND t.expires > NOW() "
                . "AND u.disabled IS NULL;", array(':identifier' => $identifier));
        if($user) {
            $user->displayName = $user->nameFirst;
            $user->roles = DBConn::selectAll("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id;", array(':id' => $user->id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
}
