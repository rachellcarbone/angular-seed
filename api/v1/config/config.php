<?php namespace API;

class APIConfig {

    function __construct() {
        $this->setAPIConfig(filter_input(INPUT_SERVER, 'SERVER_ADDR'));
    }

    function setAPIConfig($ip) {
        $default = array(
            'apiVersion' => 'v1',
            'debugMode' => true,
            'dbHost' => 'localhost',
            'db' => 'angular_seed',
            'dbUser' => 'root',
            'dbPass' => 'toot',
            'dbTablePrefix' => 'as_',
            'dirPublic' => 'public/',
            'dirSystem' => 'api/system/',
            'dirLogs' => 'api/system/logs/',
        );

        switch ($ip) {
            case '::1':
            default:
                // Localhost
                $this->config = array_merge($default, array(
                    'db' => 'angular_seed',
                    'dbUser' => 'angular_seed',
                    'dbPass' => 'angular_seed',
                    'systemPath' => 'C:/xampp/htdocs/webdev/angular-seed/',
                    'websiteUrl' => 'http://www.seed.dev/'
                ));
        }
    }

    function get($opt = false) {
        if ($opt !== false && isset($this->config[$opt])) {
            return $this->config[$opt];
        }
        return $this->config;
    }

}
