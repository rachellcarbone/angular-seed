<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';

class DBConn {
    static $pdo;
    static $logger;
    
    private static function connect() {
        if(self::$pdo) {
            return self::$pdo;
        }
        
        $config = new APIConfig();
        $c = $config->get();

        // Set options
        $options = array(
            \PDO::ATTR_PERSISTENT => true, 
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        );

        try {
            self::$pdo = new \PDO("mysql:host={$c['dbHost']};dbname={$c['db']}", $c["dbUser"], $c["dbPass"], $options);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return self::$pdo;
        } catch (\PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
    }
    
    private static function logPDOError($pdo) {
        if(!self::$logger) {
            self::$logger = new Logging('pdo_exception');
        }
        self::$logger->write($pdo->errorInfo());
    }
    
    public static function getLimit($limit = 20, $page = 1) {
        $p = (is_numeric($page) && intval($page) > 1) ? intval($page) : 1;
        $l = (is_numeric($limit) && intval($limit) > 0) ? intval($limit) : 20;
        $offset = ($p - 1) * $l;
        
        return "LIMIT {$offset}, {$l}";
    }
    
    public static function select($query) {
        $pdo = self::connect();
        try {
            $q = $pdo->query($query);
            return $q->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
    }
    
    public static function selectOne($query, $data = false) {
        $pdo = self::connect();
        
        try {
            if($data) {
                $q = $pdo->prepare($query);
                $q->execute($data);
                return $q->fetch(\PDO::FETCH_ASSOC);
            } else {
                $q = $pdo->query($query);
                return $q->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
        
    }
    
    public static function query($query) {
        $pdo = self::connect();
        try {
            return $pdo->query($query);
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
    }

    public static function preparedQuery($query, $data) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);
            return $q->execute($data);
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
    }
    
    public static function insertQuery($query, $data) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);
            $e = $q->execute($data);
            if ($e === false) {
                return false;
            } else {
                $id = $pdo->lastInsertId();
                return ($id === false) ? $e : $id;
            }
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
    }
    
    public function rowCount() {
        //return $this->stmt->rowCount();
    }
    
    public function lastInsertId(){
        $pdo = self::connect();
        return $pdo->lastInsertId();
    }
}
