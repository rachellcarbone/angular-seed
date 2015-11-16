<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/abstract.data.php';

class UserData extends PDOData {
  
    public function __construct() {
        parent::__construct();
    }
    
    public function db_selectUserById($id) {
        return $this->db->selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email, role_id as roleId "
                . "FROM users WHERE id = " . $id . " LIMIT 1;");
    }
}
