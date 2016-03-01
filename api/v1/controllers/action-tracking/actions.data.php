<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class ActionData {
  
    static function insertAction($validAction) {
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "logs_action_tracking(`ip_address`, `code`, `action`, `http_referer`, `created_user_id`) "
                . "VALUES (:ip_address, :code, :action, :http_referer, :created_user_id);", $validAction);
    }
    
}
