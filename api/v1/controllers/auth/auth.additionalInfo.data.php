<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class InfoData {
    
    public static function insertQuestion($data) {
        return DBConn::insert("INSERT INTO as_user_additional_info(user_id, question, answer) "
                . "VALUES (:user_id, :question, :answer);", $data);
    }
}
