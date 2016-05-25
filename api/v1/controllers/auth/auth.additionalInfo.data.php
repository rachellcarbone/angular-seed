<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class InfoData {
    
    public static function insertQuestion($data) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "user_additional_info(user_id, question, answer) "
                . "VALUES (:user_id, :question, :answer);", $data);
    }
    
    public static function saveTerms($data) {
        return DBConn::update("UPDATE " . DBConn::prefix() . "users SET accepted_terms = :accepted_terms "
                . "WHERE id = :user_id LIMIT 1;", $data);
    }
}
