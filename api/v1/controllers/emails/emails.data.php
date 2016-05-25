<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class EmailData {
    
    static function updateInviteLastVisited($token) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "tokens_player_invites SET "
                . "last_visited=NOW() WHERE token = :token LIMIT 1;", array(':token' => $token));
    }
    
    static function selectInviteByToken($token) {
        return DBConn::selectOne("SELECT token, team_id AS teamId, user_id AS userId, "
                . "name_first AS nameFirst, name_last AS nameLast, email, phone, "
                . "created, created_user_id AS invitedBy, expires, last_visited AS lastVisited "
                . "FROM " . DBConn::prefix() . "tokens_player_invites "
                . "WHERE token = :token AND expires >= NOW() LIMIT 1;", array(':token' => $token));
    }
    
    static function insertTeamInvite($validInvite) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "tokens_player_invites"
                . "(token, team_id, user_id, name_first, name_last, email, "
                . "phone, created_user_id, expires) VALUES "
                . "(:token, :team_id, :user_id, :name_first, :name_last, :email, "
                . ":phone, :created_user_id, DATE_ADD( NOW(), INTERVAL 1 WEEK ));", $validInvite);
    }
    
    static function insertPlayerInvite($validInvite) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "tokens_player_invites"
                . "(token, name_first, name_last, email, phone, created_user_id, expires) VALUES "
                . "(:token, :name_first, :name_last, :email, :phone, :created_user_id, "
                . "DATE_ADD( NOW(), INTERVAL 4 WEEK ));", $validInvite);
    }
    
    static function selectUserIdByEmail($email) {
        return DBConn::selectColumn("SELECT id FROM " . DBConn::prefix() . "users "
                . "WHERE email = :email LIMIT 1;", array(':email' => $email));
    }
    
    static function updateAcceptTeamInvite($validInvite) {
        DBConn::delete("DELETE FROM " . DBConn::prefix() . "team_members WHERE user_id = :user_id;", 
                array(':user_id' => $validInvite[':user_id']));
        
        DBConn::insert("INSERT INTO " . DBConn::prefix() . "team_members(user_id, team_id, added_by) "
                . "VALUES (:user_id, :team_id, :added_by);", array(':user_id' => $validInvite[':user_id'], 
                    ':team_id' => $validInvite[':team_id'], ':added_by' => $validInvite[':user_id']));
        
        return DBConn::update("UPDATE " . DBConn::prefix() . "tokens_player_invites SET "
                . "response='accepted', last_visited=NOW() WHERE token = :token AND user_id = :user_id "
                . "AND team_id = :team_id AND expires > NOW() LIMIT 1;", $validInvite);
    }
    
    static function updateDeclineInvite($validInvite) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "tokens_player_invites SET "
                . "response='declined', last_visited=NOW() WHERE token = :token AND user_id = :user_id "
                . "AND team_id = :team_id AND expires > NOW() LIMIT 1;", $validInvite);
    }
}
