<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';

class DBConn {
    /*
     * PDO Instance
     */
    static $pdo;
    
    /*
     * System Logger Instance
     */
    static $logger;
    
    /*
     * DB Table Prefix
     */
    static $dbTablePrefix;
    
    public static function prefix() {
        if(!self::$dbTablePrefix) {
            $config = new APIConfig();
            self::$dbTablePrefix = $config->get('dbTablePrefix');
        }
        return self::$dbTablePrefix;
    }
    
    /*
     * Create a PDO connection if one does not exist.
     */
    private static function connect() {
        // If a PDO instance does not already exist
        if(!self::$pdo) {
            // Get the system configuration file
            $config = new APIConfig();
            $c = $config->get();

            // Create a set of PDO options
            $options = array(
                /*
                 * Persistent connections are not closed at the end of the script, but 
                 * are cached and re-used when another script requests a connection 
                 * using the same credentials. The persistent connection cache allows 
                 * you to avoid the overhead of establishing a new connection every 
                 * time a script needs to talk to a database, resulting in a faster web 
                 * application.
                 * http://php.net/manual/en/pdo.connections.php
                 */
                \PDO::ATTR_PERSISTENT => true,
                // Error Mode: Throw Exceptions
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            );

            try {
                // Connect to an MySQL database using driver invocation
                self::$pdo = new \PDO("mysql:host={$c['dbHost']};dbname={$c['db']}", $c["dbUser"], $c["dbPass"], $options);
            } catch (\PDOException $e) {
                // If we cant connect to the database die
                die('Could not connect to the database:<br/>' . $e);
            }
        }
        
        // Return the cached instance of PDO
        return self::$pdo;
    }
    
    /*
     * Log the last PDO error
     */
    private static function logPDOError($pdo) {
        // If the logger hasnt been instantiated
        if(!self::$logger) {
            // Create a new instance of the system Logging class
            self::$logger = new Logging('pdo_exception');
        }
        // Write the error arry to the log file
        self::$logger->write($pdo->errorInfo());
    }
    
    /* 
     * Get select limit string for MySQL
     */
    public static function getLimit($limit = 20, $page = 1) {
        $p = (is_numeric($page) && intval($page) > 1) ? intval($page) : 1;
        $l = (is_numeric($limit) && intval($limit) > 0) ? intval($limit) : 20;
        $offset = ($p - 1) * $l;
        
        return "LIMIT {$offset}, {$l}";
    }
    
    
    
    public static function select($query, $data = array(), $style = \PDO::FETCH_OBJ) {
        $pdo = self::connect();
        
        try {            
            if ($data) {
                $q = $pdo->prepare($query);
                $q->execute($data);
            } else {
                $q = $pdo->query($query);
            }
            return $q->fetchAll($style);
        } catch (\PDOException $e) {
            self::logPDOError($pdo);
            return false;
        }
    }
    
    public static function selectOne($query, $data = array(), $style = \PDO::FETCH_OBJ) {
        $pdo = self::connect();
        
        try {
            if($data) {
                $q = $pdo->prepare($query);
                $q->execute($data);
            } else {
                $q = $pdo->query($query);
            }
            return $q->fetch($style);
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

    public static function preparedQuery($query, $data = array()) {
        $pdo = self::connect();
        try {
            return $pdo->prepare($query);
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
}
