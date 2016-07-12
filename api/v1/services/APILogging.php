<?php namespace API;
require_once dirname(__FILE__) . '/ApiConfig.php';

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class ApiLogging {
    
    // Path to the log file
    private $defaultLogFile = '';
    
    // Is the API in debug mode
    private $ApiConfig;
    
    function __construct($ApiConfig, $logName = 'log') {
        // Get the API config
        $this->ApiConfig = new ApiConfig();
        $c = $this->ApiConfig->get();
        
        // Name of the log file, either "yourname_log" or "log"
        $name = ($logName === 'log') ? 'log' : $logName . '_log';
        
        // Example: C:/xampp/htdocs/angular-seed/api/system/logs/v1_16_11_error_log.txt
        $this->defaultLogFile = $this->buildLogFilePath($name);
    }

    private function buildLogFilePath($name) {
        if($this->ApiConfig->get('systemPath') && $this->ApiConfig->get('dirLogs') && $this->ApiConfig->get('apiVersion')) {
            $path = $this->ApiConfig->get('systemPath');
            $dir = $this->ApiConfig->get('dirLogs');
            $version = $this->ApiConfig->get('apiVersion');
            return  "{$path}{$dir}{$version}_" . date('y_m') . "_{$name}.txt";
        } else {
            openlog('api', LOG_NDELAY, LOG_USER);
            syslog(LOG_ERR, "Invalid ApiConfig Variable for System Logger. Missing `systemPath`, `dirLogs`, or `apiVersion`.");
            return false;
        }
    }
    
    private function getExceptionString($e) {
        // If the api is in debug mode return a more verbose message
        return "\r\n" . $e->getMessage() . "\r\n{$e}";
    }
    
    /* 
     * Change a Exception to a string and write it to the log file
     */
    public function logException($e, $level = 'error', $alternateLog = 'api_exceptions') {
        // Get the exception as a string
        $text = $this->getExceptionString($e);
        // Write the exception to the log file
        $this->write("{$text}\r\n", $level, $alternateLog);
    }
    
    /* 
     * Alias for the write function
     */
    public function log($logItem, $level = 'alert', $alternateLog = false) {
        $this->write($logItem, $level, $alternateLog);
    }
    
    /* 
     * Prepend a timestamp to a line of text and write it to the log file.
     */
    public function write($logItem, $level = 'alert', $alternateLog = false) {
        // Make sure the log item is in String format
        $text = (is_array($logItem)) ? json_encode($logItem) : $logItem;

        // If the alternate log file is defined
        $logFile = false;
        if($alternateLog) {
            $logFile = $this->buildLogFilePath($alternateLog);
        }
        // If the alternate log file is valid
        $logFile = ($logFile) ? $logFile : $this->defaultLogFile;

        // Build a formatted error message
        $this->writeToLog($text, $logFile);
    }

    private function writeToLog($text, $logFile = false) {
        $version = $this->ApiConfig->get('apiVersion');
        openlog("api_{$version}", LOG_NDELAY, LOG_USER);

        $message = date("m d, Y, G:i:s T") . "    {$text}\r\n";

        // Send it to the System Log
        syslog(LOG_ERR, $message);

        if($logFile) {
            // Write to the log file
            try {
                // Append the log item (string) to the log file 
                return file_put_contents($logFile, $message, FILE_APPEND);
            } catch (\Exception $e) {
                syslog(LOG_ERR, $this->getExceptionString($e));
                return false;
            }
        } else {
            // Send it to the System Log
            syslog(LOG_ERR, "Invalid log file: {$logFile}");
            return false;
        }
    }
}
