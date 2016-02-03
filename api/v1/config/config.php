<?php namespace API;

class APIConfig {
    static $config = false;

    static function setAPIConfig() {
        $default = array(
            'apiVersion' => 'v1',
            'debugMode' => true,
            
            'dbHost' => 'localhost',
            'db' => 'angular_seed',
            'dbUser' => 'root',
            'dbPass' => 'toot',
            'dbTablePrefix' => 'as_',
            
            'systemPath' => 'C:/xampp/htdocs/webdev/angular-seed/',
            'dirPublic' => 'public/',
            'dirSystem' => 'api/system/',
            'dirLogs' => 'api/system/logs/',
            
            'websiteUrl' => 'http://www.seed.dev/'
        );

        if(filter_input(INPUT_SERVER, 'HTTP_HOST') == 'api.seed.dev' ||
            filter_input(INPUT_SERVER, 'HTTP_HOST') == 'localhost' ||
            filter_input(INPUT_SERVER, 'SERVER_ADDR') == '127.0.0.1') {
            // Localhost
            self::$config = array_merge($default, array(
                'dbHost' => 'localhost',
                'db' => 'angular_seed',
                'dbUser' => 'angular_seed',
                'dbPass' => 'angular_seed',
                'dbTablePrefix' => 'as_',
                'systemPath' => 'C:/xampp/htdocs/webdev/angular-seed/',
                'websiteUrl' => 'http://www.seed.dev/'
            ));
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

}
