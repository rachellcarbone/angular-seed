<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class AuthData {
    
    public static function insertAuthToken($validToken) {
        return DBConn::insert('INSERT INTO ' . DBConn::prefix() . 'tokens_auth(identifier, token, user_id, expires) '
                . 'VALUES (:identifier, :token, :user_id, :expires);', $validToken);
    }
    
    public static function deleteAuthToken($identifier) {
        return DBConn::delete('DELETE FROM ' . DBConn::prefix() . 'tokens_auth WHERE identifier = :identifier;', $identifier);
    }
    
    public static function deleteExpiredAuthTokens() {
        return DBConn::executeQuery('DELETE FROM ' . DBConn::prefix() . 'tokens_auth WHERE expires < NOW();');
    }
}
