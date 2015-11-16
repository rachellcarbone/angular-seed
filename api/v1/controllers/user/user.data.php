<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class UserData {
    
    public static function selectUsers() {
        return DBConn::select('SELECT * FROM users ORDER BY name_last ASC;');
    }
    
    public static function selectUserById($id) {
        return DBConn::selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email, role_id as roleId "
                . "FROM users WHERE id = :id LIMIT 1;", array('id' => $id));
    }
  
    public static function insertUser($validUser) {
        return DBConn::preparedQuery("INSERT INTO users(name_first, name_last, email, password, role_id, plan_id) "
                . "VALUES (:name_first, :name_last, :email, :password, :role_id, :plan_id);", $validUser);
    }
    
    public static function updateUser($validUser) {
        return DBConn::preparedQuery("UPDATE users SET name_first=:name_first, name_last=:name_last, "
                . "email=:email, password=:password, role_id=:role_id, plan_id=:plan_id "
                . "WHERE id = :id;", $validUser);
    }
    
    public static function deleteUser($id) {
        return DBConn::preparedQuery("DELETE FROM users WHERE id = :id LIMIT 1;", array('id' => $id));
    }
}
