<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';

class DBConn {
    private $pdo;
   
    public function __construct() {        
        $config = new APIConfig();
        $c = $config->get();
                
        try {
            $this->pdo = new \PDO("mysql:host={$c['dbHost']};dbname={$c['db']}", $c["dbUser"], $c["dbPass"]);
            $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function getLimit($limit = 20, $page = 1) {
        $p = (is_numeric($page) && intval($page) > 1) ? intval($page) : 1;
        $l = (is_numeric($limit) && intval($limit) > 0) ? intval($limit) : 20;
        $offset = ($p - 1) * $l;
        
        return "LIMIT {$offset}, {$l}";
    }
    
    public function select($query) {
        try {
            $q = $this->pdo->query($query);
            $q->setFetchMode(\PDO::FETCH_ASSOC);
            return $q->fetchAll();
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function selectOne($query) {
        try {
            $q = $this->pdo->query($query);
            $q->setFetchMode(\PDO::FETCH_ASSOC);
            $r = $q->fetch();
            return (isset($r[0])) ? $r[0] : $r;
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function query($query) {
        try {
            return $this->pdo->query($query);
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function prepairedQuery($query, $data) {
        try {
            $q = $this->pdo->prepare($query);
            return $q->execute($data);
        } catch (\PDOException $e) {
            return false;
        }
    }
    
    public function insertQuery($query, $data) {
        $q = $this->pdo->prepare($query);
        try {
            $e = $q->execute($data);
            if ($e === false) {
                return false;
            } else {
                $id = $this->pdo->lastInsertId();
                return ($id === false) ? $e : $id;
            }
        } catch (\PDOException $e) {
            return false;
        }
    }
}
