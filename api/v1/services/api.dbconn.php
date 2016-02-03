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
     * @return PDO Represents a connection between PHP and a database server.
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
    
    /*
     * Preform insert opperation with PDO connection.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * 
     * @return int|bool New row ID if success, FALSE if insert fails.
     */
    public static function insert($query, $data) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);
            $e = $q->execute($data);
            if ($e === false) {
                return false;
            } else {
                $id = $pdo->lastInsertId();
                return ($id === false) ? false : $id;
            }
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
    
    /*
     * Preform update opperation with PDO connection.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array|optional An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * 
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function update($query, $data = array()) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);            
            return $q->execute($data);
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
    
    /*
     * Preform fetchAll opperation with on a prepared PDO query.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array|optional An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * @param enum This value must be one of the \PDO::FETCH_* constants, 
     * defaulting to \PDO::FETCH_OBJ (php.net/manual/en/pdostatement.fetch.php)
     * 
     * @return array|bool returns an array containing all of the remaining rows in the 
     * result set. The array represents each row as either an array of column 
     * values or an object with properties corresponding to each column name. 
     * An empty array is returned if there are zero results to fetch, 
     * or FALSE on failure.
     */
    public static function selectAll($query, $data = array(), $style = \PDO::FETCH_OBJ) {
        $pdo = self::connect();
        
        try {
            $q = $pdo->prepare($query);
            $q->execute($data);
            return $q->fetchAll($style);
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
    
    /*
     * Preform fetchAll opperation with on a prepared PDO query.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array|optional An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * @param enum This value must be one of the \PDO::FETCH_* constants, 
     * defaulting to \PDO::FETCH_OBJ (php.net/manual/en/pdostatement.fetch.php)
     * 
     * @return array|bool returns a single column in the next row of a result set,
     * or FALSE if no results were found.
     */
    public static function selectOne($query, $data = array(), $style = \PDO::FETCH_OBJ) {
        $pdo = self::connect();
        
        try {
            $q = $pdo->prepare($query);
            $q->execute($data);
            return $q->fetch($style);
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }

    /*
     * Preform delete opperation with on a prepared PDO query.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * 
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function delete($query, $data) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);            
            $q->execute($data);
            $effected = $q->rowCount();
            return ($effected > 0);
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
    
    /*
     * Prepares a statement for execution and returns a statement object.
     * 
     * @param string A valid SQL statement template for the target database server.
     * 
     * @return PDOStatement PDO statement object to be executed later.
     */
    public static function preparedQuery($query) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);
            return $q;
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
    
    /*
     * Prepares and exicutes a query.
     * 
     * @param string A valid SQL statement template for the target database server.
     * @param array|optional An array of values with as many elements as there 
     * are bound parameters in the SQL statement being executed.
     * 
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public static function executeQuery($query, $data = array()) {
        $pdo = self::connect();
        try {
            $q = $pdo->prepare($query);            
            $q->execute($data);
            return $q;
        } catch (\PDOException $e) {
            self::logPDOError($q);
            return false;
        }
    }
}
