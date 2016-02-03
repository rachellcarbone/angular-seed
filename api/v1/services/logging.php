<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/config/config.php';

/* 
 * @author  Rachel Carbone
 */

class Logging {
    
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
        return ($this->debugMode) ? "{$e}\r\n" : $e->getMessage();
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
    function log($logItem) {
        // This is just a different way to call write
        $this->write($logItem);
    }
    
    /* 
     * Prepend a timestamp to a line of text and write it to the log file.
     */
    function write($logItem) {
        if(is_array($logItem)) {
            syslog(LOG_ERR, json_encode($logItem));
        } else {
            syslog(LOG_ERR, $logItem);
        }
        
        try {
            // Get the timestamp
            file_put_contents($this->logFile, date("m d, Y, G:i:s T"), FILE_APPEND);
            // Concatenate log text with the timestamp
            file_put_contents($this->logFile, $logItem, FILE_APPEND);
            // Append the log item (string) to the log file 
            file_put_contents($this->logFile, "\n\r", FILE_APPEND);
        } catch (\Exception $e) {
            syslog(LOG_ERR, $this->getExceptionString($e));
        }
    }
}
