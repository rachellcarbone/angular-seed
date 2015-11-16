<?php namespace API;

class UserData {
  
    function __construct() {
    }
    
    public function db_selectUserById($id) {
        return $this->db->selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email, role_id as roleId, "
                . "FROM users WHERE id = " . $id . " LIMIT 1;");
    }
}
