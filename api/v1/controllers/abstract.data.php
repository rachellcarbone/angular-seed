<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/services/api.dbconn.php';

abstract class PDOData {
    protected $db;
  
    public function __construct() {
        $this->db = new DBConn();
    }
}
