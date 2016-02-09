<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class TestData {
    
    public static function userTableTestVal() {
        return DBConn::selectOne("SELECT u.id FROM " . DBConn::prefix() . "users AS u LIMIT 1;");
    }
}
 