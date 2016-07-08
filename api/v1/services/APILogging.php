<?php namespace API;
require_once dirname(__FILE__) . '/APIConfig.php';

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class APILogging {
    
    // Path to the log file
    private $logFile;
    
    // Is the API in debug mode
    private $debugMode;
    
    function __construct($logName = 'log') {
        // Name of the log file, either "yourname_log" or "log"
        $type = ($logName === 'log') ? 'log' : $logName . '_log';
        
        // Get the API config
        $config = new APIConfig();
        $c = $config->get();
        
        //Is the app in debug mode
        $this->debugMode = $c["debugMode"];
        
        // month_year - 01_15
        $datestamp = date("m_y");
        
        // Example: C:/xampp/htdocs/angular-seed/api/system/logs/v1_11_15_error_log.txt
        $this->logFile = $c['systemPath'] . $c['dirLogs'] . $c['apiVersion'] . '_' . $datestamp . '_' . $type . '.txt';
    }
    
    private function getExceptionString($e) {
        // If the api is in debug mode return a more verbose message
        return ($this->debugMode) ? "{$e} \r\n" : $e->getMessage();
    }
    
    
    /* 
     * Change a Exception to a string and write it to the log file
     */
    function logException($e) {
        // Get the exception as a string
        $text = $this->getExceptionString($e);
        // Write the exception to the log file
        $this->write($text);
    }
    
    /* 
     * Alias for the write function
     */
    function log($logItem, $level = 'alert') {
        $this->write($logItem, $level);
    }
    
    /* 
     * Prepend a timestamp to a line of text and write it to the log file.
     */
    function write($logItem, $level = 'alert') {
        // Make sure the log item is in String format
        $text = (is_array($logItem)) ? json_encode($logItem) : $logItem;

        // Build a formatted error message
        $message = date("m d, Y, G:i:s T") . " \r\n" . $text . " \r\n";
        
        // Send it to the System Log
        syslog(LOG_ERR, $message);

        try {
            // Append the log item (string) to the log file 
            file_put_contents($this->logFile, $message, FILE_APPEND);
        } catch (\Exception $e) {
            syslog(LOG_ERR, $this->getExceptionString($e));
        }
    }
}
