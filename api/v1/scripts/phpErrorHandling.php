<?php

$debugMode = true;

// Only display errors when debug mode is on
ini_set('display_errors', $debugMode);
ini_set('display_startup_errors', $debugMode);

// Require dependencies after setting errors display incase 
// there are errors with any dependencies.
require_once dirname(dirname(__FILE__)) . '/services/ApiLogging.php';


class PhpErrorHandling {
    /* 
     * PHP Exception Handeling
     * http://php.net/manual/en/function.set-exception-handler.php
     */
    static function apiExceptionHandler($e) {

        /* Set PHP Error Handler to ApiLogging */
        $LogException = new \API\ApiLogging('php_exception');

        $LogException->logException($e);
    }

    /* 
     * PHP Error Handeling
     * http://php.net/manual/en/function.set-error-handler.php
     */
    static function apiErrorHandler($errno, $errstr, $errfile, $errline) {
        /* Set PHP Error Handler to ApiLogging */
        $Logger = new \API\ApiLogging('php_error');

        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting
            return;
        }

        /* Log the message instead of printing */
        switch ($errno) {
            case E_ERROR:
            case E_USER_ERROR:
                $Logger->write("PHP ERROR: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n"
                    . "    PHP " . PHP_VERSION . " (" . PHP_OS . ")\r\n"
                    . "    Aborting...\r\n");
                exit(1);
                break;

            case E_WARNING:
            case E_USER_WARNING:
                $Logger->write("PHP WARNING: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;

            case E_NOTICE:
            case E_USER_NOTICE:
                $Logger->write("PHP NOTICE: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;

            default:
                $Logger->write("UNKNOWN PHP ERROR: [{$errno}] {$errstr}\r\n"
                    . "    Line {$errline} in file {$errfile},\r\n");
                break;
        }

        /* If the function returns FALSE then the normal error handler continues.
         * TRUE - If we are not in debug mode
         * FALSE - If we are in debug mode */
        return false;// (!$this->debugMode);
    }
}

$error = new PhpErrorHandling();

// http://php.net/manual/en/function.set-error-handler.php
set_error_handler(array($error, 'apiErrorHandler'));

// http://php.net/manual/en/function.set-exception-handler.php
set_exception_handler(array($error, 'apiExceptionHandler'));