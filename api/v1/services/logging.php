<?php namespace API\Data;
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
    function log($text) {
        // This is just a different way to call write
        $this->write($text);
    }
    
    /* 
     * Prepend a timestamp to a line of text and write it to the log file.
     */
    function write($text) {
        // Get the timestamp
        $timestamp = date("m d, Y, G:i:s T");
        // Concatenate log text with the timestamp
        $logItem = "{$timestamp} - {$text}\r\n";
        // Append the log item (string) to the log file 
        file_put_contents($this->logFile, $logItem, FILE_APPEND);
    }
    
    /* 
     * PHP Exception Handeling
     * http://php.net/manual/en/function.set-exception-handler.php
     */
    function loggingExceptionHandler() {
        
    }
    
    /* 
     * PHP Error Handeling
     * http://php.net/manual/en/function.set-error-handler.php
     */
    function loggingErrorHandler($errno, $errstr, $errfile, $errline) {
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }
        
        /* Log the message instead of printing */
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                $this->write("PHP ERROR: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n"
                    . "    PHP " . PHP_VERSION . " (" . PHP_OS . ")\r\n"
                    . "    Aborting...\r\n");
                exit(1);
                break;

            case E_WARNING:
            case E_USER_WARNING:
                $this->write("PHP WARNING: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;
            
            case E_NOTICE:
            case E_USER_NOTICE:
                $this->write("PHP NOTICE: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;

            default:
                $this->write("UNKNOWN PHP ERROR: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;
        }

        /* If the function returns FALSE then the normal error handler continues.
         * TRUE - If we are not in debug mode
         * FALSE - If we are in debug mode */
        return (!$this->debugMode);
    }
}
