<?php namespace API;
 require_once dirname(dirname(__FILE__)) . '/services/api.dbconn.php';

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class APIConfig {
    static $dbConfig = false;
    static $config = false;

    static function setAPIConfig() {
        $default = array(
            'apiVersion' => 'v1',
            'debugMode' => true,
            'dbHost' => 'localhost',
            'dbUnixSocket' => false,
            'db' => 'seed',
            'dbUser' => 'angular_seed',
            'dbPass' => 'angular_seed',
            'dbTablePrefix' => 'as_',
            'systemPath' => 'C:/xampp/htdocs/webdev/angular-seed/',
            'dirPublic' => 'public/',
            'dirSystem' => 'api/system/',
            'dirLogs' => 'api/system/logs/'
        );
        
        if($_SERVER['HTTP_HOST'] === 'api.seed.dev') {
            // Localhost
            self::$config = $default;
        } else {
            self::$config = false;
	}
        
        if(self::$config !== false) {
            $dbConfig = self::selectSystemVariables();
            self::$config = array_merge(self::$config, $dbConfig);
        }
    }

    static function get($opt = false) {
        if(!self::$config) {
            self::setAPIConfig();
        }
        
        if ($opt !== false && isset(self::$config[$opt])) {
            return self::$config[$opt];
        }
        return self::$config;
    }

    private static function selectSystemVariables() {
        $qDBConfig = DBConn::executeQuery("SELECT name, value FROM " . DBConn::prefix() . "system_config WHERE disabled = 0;");
        
        $dbConfig = Array();
        while($var = $qDBConfig->fetch(\PDO::FETCH_OBJ)) {  
            $dbConfig[$var->name] = $var->value;
        }
        self::$dbConfig = $dbConfig;
        
        return self::$dbConfig;
    }
}