<?php namespace API\Data;
require_once 'api.config.php';

class APIInit {
    private $config;
    
    function __construct() {
        $this->checkDirectories();
        
        $config = new APIConfig();
        $this->config = $config->get();
    }
    
    private function checkDirectories() {
        $dir = $this->config['systemPath'];
        try {
            // If the public directory does not exist
            if (!is_dir($dir . $this->config['dirPublic'])) {
                // Create the public uploads directory with 777 permissions
                mkdir($dir . $this->config['dirPublic'], 0777); 
            }
            
            // If the the system directory does not exist
            if (!is_dir($dir . $this->config['dirSystem'])) {
                // Create the system directory with 777 permissions
                mkdir($dir . $this->config['dirSystem'], 0777);
                
                // If the the system logs directory does not exist
                if (!is_dir($dir . $this->config['dirLogs'])) {
                    // Create the system logs directory with 777 permissions
                    mkdir($dir . $this->config['dirLogs'], 0777);         
                }       
            }
        } catch (Exception $e) {
            // Log Exceptions
            //$this->Logging->logException($e);
            return false;
        }
    }
    
}
