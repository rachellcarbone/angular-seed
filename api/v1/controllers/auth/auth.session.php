<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';

class AuthSession {
    /*
     * Cookie: Login Attempts
     * Holds a json object representing login attempts for various emails.
     * 
     * Example: {
     *      'joe@bob.com' : 1,
     *      'jaoe@bob.com' : 2,
     * }
     */
    private $cookieLoginAttempts = '_rcsessLIA';
    
    /*
     * Cookie: Auth Identifier
     * Used to look up the hashed token in the database.
     */
    private $cookieAuthIdentifier = '_rcsessAI';
    
    /*
     * Vookie: Auth Token
     * Holds a SHA256 Hash Token that is used together with the 
     * encrypted user email to validate a logged in user.
     */
    private $cookieAuthToken = '_rcsessAT';
    
    /*
     * Session: User Id
     * Holds the user id in a session object for faster lookup.
     */
    private $sessionActiveUser = '_rcsessAU';
    
    private $cookieDomain = 'http://www.seed.dev/';
            
    function __construct() {
        session_start();
    }
    
    // Login Attempts
    
    function getFailedLoginAttempts($email) {
        $cookie = array();
        if (filter_input(INPUT_COOKIE, $this->cookieLoginAttempts)) {
            $cookie = json_decode(filter_input(INPUT_COOKIE, $this->cookieLoginAttempts), true);
        }
        $attempts = (isset($cookie[$email])) ? intval($cookie[$email]) : 0;
        return $attempts;
    }
    
    function loginAttemptFailed($email) {
        $attempts = $this->getFailedLoginAttempts($email);
        $attempts = $attempts + 1;
        $cookie = array($email => $attempts);
        setcookie($this->cookieLoginAttempts, json_encode($cookie), $this->getTimeout(5, 'min'), $this->cookieDomain);
        return $attempts;
    }
    
    function clearLoginAttempts() {
        return $this->destroyCookie($this->cookieLoginAttempts);
    }    
    
    // User login
    
    function getLoggedInSession() {
        $tokens = $this->server_getTokenCookies();
        $user = $this->server_getUserSession();
        
            return (object) array_merge(array('$tokens' => $tokens), array('user' => $user));
            
        if(!$tokens && !$user) {
            return false;
        } else if(!$tokens && $user) {
            $this->server_removeUserSession();
            return false;
        } else {
            return (object) array_merge($tokens, array('user' => $user));
        }
    }
    
    function createLoggedInSession($user, $remember = false) {
        $identifier = $this->makeToken(uniqid());
        $token = $this->makeToken(uniqid());
        $timeout = ($remember) ? $this->getTimeout(4, 'hour') : $this->getTimeout(3, 'day');
        
        $this->server_saveTokenCookies($identifier, $token, $timeout);
        $this->server_saveUserSession($user);
        
        return (object) array(
            'identifier' => $identifier,
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'expires' => date('Y-m-d H:i:s', $timeout)
        );
    }
    
    function clearLoggedInSession() {
        $this->server_removeTokenCookies();
        $this->server_removeUserSession();
        return true;
    }
    
    // Cookie And Session Managment for Login
    
    function server_saveTokenCookies($identifier, $token, $timeout) {
        setcookie($this->cookieAuthIdentifier, $identifier, $timeout, $this->cookieDomain);
        setcookie($this->cookieAuthToken, $token, $timeout, $this->cookieDomain);
    }
    
    function server_saveUserSession($user) {
        $_SESSION[$this->sessionActiveUser] = json_encode($user);
    }
    
    function server_getTokenCookies() {
        if (filter_input(INPUT_COOKIE, $this->cookieAuthIdentifier)) {
            return array(
                    'identifier' => filter_input(INPUT_COOKIE, $this->cookieAuthIdentifier),
                    'token' => filter_input(INPUT_COOKIE, $this->cookieAuthToken)
            );
        }
        return false;
    }
    
    function server_getUserSession() {
        if(isset($_SESSION[$this->sessionActiveUser])) {
            return json_decode($_SESSION[$this->sessionActiveUser], true);
        }
        return false;
    }
    
    function server_removeTokenCookies() {
        $this->destroyCookie($this->cookieAuthIdentifier);
        $this->destroyCookie($this->cookieAuthToken);
        return true;
    }
    
    function server_removeUserSession() {
        if(isset($_SESSION[$this->sessionActiveUser])) {
            session_unset();
            session_destroy();
            session_start();
        }
        return true;
    }

    // Helpers 
    
    private function getTimeout($time, $measure = 'hour') {
        $timeout = time();
        switch ($measure) {
            case 'min':
                $timeout = $timeout + (60*$time);
                break;
            case 'day':
                $timeout = $timeout + (60*60*24*$time);
                break;
            case 'hour':
            default:
                $timeout = $timeout + (60*60*$time);
                break;
        }
        return $timeout;
    }
    
    private function destroyCookie($name) {
        setcookie($name, '', time()-3600, $this->cookieDomain);
        //setcookie($name, null, time()-3600, $this->cookieDomain);
        unset($_COOKIE[$name]);
        session_write_close();
        session_start();
        return true;
    }
    
    private function makeToken($string) {
        return hash('sha256', $string);
    }
}
