<?php namespace API;

/* @author  Rachel L Carbone <hello@rachellcarbone.com> */

class ApiConfig {

    /* \API\ApiLogging */
    private $ApiLogging = false;

    /* Array of key => value pairs */
    private $apiConfig = false;

    /**
     * Api Config variables Handler to manage the use of variables stored in the database
     * to be used throught the API.
     * 
     * $SystemVars = new ApiConfig( new \API\ApiLogging( 'error_log' ) );
     *
     * @param  \API\ApiLogging  $ApiLogging optional System Logging Helper Method
     *
     * @return Array
     */
    public function __construct($ApiLogging = false) {

        $this->ApiLogging = $ApiLogging;

        /* Select and set the api config variables */
        $this->setApiConfig();
    }

    /**
     * This method works two ways:
     * 
     * One - Select a api config variable by its name, is the variable exists its value (mixed)
     *      will be returned, or if the variable doesn't exist false (bool) will be returned. 
     *
     * Two - Return the api config variable key => value pairs (array), or if there was an 
     *      error selecting the variables on init, return false (bool).
     *
     * $allVariablesArray = $SystemVariables->get();
     * $oneVariableValue = $SystemVariables->get('UNIQUE_VARIABLE_IDENTIFIER');
     *
     * @param  String   $variableName optional Name of the api config variable requested.
     *
     * @return Mixed
     */
    public function get($variableName = false) {        
        /* If a variable name was sent to the get function, 
         * try to select just that variable. */
        if ($this->apiConfig && $variableName !== false) {
            return (isset($this->apiConfig[$variableName])) ? $this->apiConfig[$variableName] : false;
        }
        return $this->apiConfig;
    }

    /**
     * Manually run the setApiConfig method and refresh the saved apiConfig.
     *
     * $allVariablesArray = $ApiConfig->refresh();
     *
     * @return Array
     */
    public function refresh() {        
        /* Run the api config variable selection manually. */
        return $this->setApiConfig();
    }

    /**
     * Set the apiConfig array based on the current server address.
     *
     * @return Array
     */
    private function setApiConfig() {
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
            $this->apiConfig = $default;
        } else {
            // Log the failure to set the api config variables
            $this->log("Could not set the api config variables: " . json_encode($_SERVER));
            // Ensure this is false if it failed
            $this->apiConfig = false;
	    }
    }

    /**
     * Helper function to log to logger if it's set or syslog if it isn't.
     *
     * @return void
     */
    private function log($message) {
        if($this->ApiLogging) {
            $this->ApiLogging->log($message, 'error');
        } else {
            syslog(LOG_ERR, $message);
        }
    }
}