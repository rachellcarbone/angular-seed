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
    private $cookieLoginAttemptLog = '_rcsessLIA';
    
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
    
    private $cookiePath = '/';
    
    private $cookieDomain = 'api.seed.dev';
    
    private $cookie;
            
    function __construct() {
        $this->cookie = new \CodeZero\Cookie\VanillaCookie();
    }
    
    // Login Attempts
    
    function getFailedLoginAttempts($email) {
        $attemptLog = $this->cookie->get($this->cookieLoginAttemptLog);
        $attempts = 0;
        if($attemptLog) {
            $log = json_decode(stripslashes($attemptLog), true);
            foreach ($log as $value) {
                if($value['eml'] === $email) {
                    $attempts = intval($value['atm']);
                }
            }
        }
        return $attempts;
    }
    
    function loginAttemptFailed($email) {
        $attempts = $this->getFailedLoginAttempts($email);
        $attempts = $attempts + 1;
        
        $minutes = 5;
        
        $log = json_encode(array(array('eml' => $email, 'atm' => $attempts)));
        
        // Dev: http://php.net/manual/en/function.setcookie.php
        $this->cookie->store($this->cookieLoginAttemptLog, $log, $minutes, $this->cookiePath, $this->cookieDomain, false, false);
        // Production: TEST on SSH.
        //$this->cookie->store($this->cookieLoginAttemptLog, $log, $minutes, '/v1/', $this->cookieDomain, true, true);
        
        return $attempts;
    }
    
    function clearLoginAttempts() {
        return $this->cookie->delete($this->cookieLoginAttemptLog);
    }    
    
    // User login
    
    function getLoggedInSession() {
        $tokens = $this->server_getTokenCookies();
        $user = $this->server_getUserSession();
            
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
        $identifier = hash('sha256', uniqid());
        $token = hash('sha256', uniqid());
        $hours = ($remember) ? 1 : 3 * 24; // 1 Hours or 3 days if remember was checked
        
        $this->server_saveTokenCookies($identifier, $token, $hours);
        $this->server_saveUserSession($user);
        
        return (object) array(
            'identifier' => $identifier,
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'expires' => date('Y-m-d H:i:s', time() + ($hours * 60 * 60))
        );
    }
    
    function clearLoggedInSession() {
        $this->server_removeTokenCookies();
        $this->server_removeUserSession();
        return true;
    }
    
    // Cookie And Session Managment for Login
    
    function server_saveTokenCookies($identifier, $token, $hours = 6) {
        $minutes = $hours * 30;
        
        // Dev: http://php.net/manual/en/function.setcookie.php
        $this->cookie->store($this->cookieAuthIdentifier, $identifier, $minutes, $this->cookiePath, $this->cookieDomain, false, false);
        $this->cookie->store($this->cookieAuthToken, $token, $minutes, $this->cookiePath, $this->cookieDomain, false, false);
        // Production: TEST on SSH.
        //$this->cookie->store($this->cookieAuthToken, json_encode(array('e' => $email, 'a' => $attempts)), $minutes, '/v1/', $this->cookieDomain, true, true);
    }
    
    function server_saveUserSession($user) {
        $_SESSION[$this->sessionActiveUser] = json_encode($user);
    }
    
    function server_getTokenCookies() {
        $savedCredentials = array(
            'identifier' => $this->cookie->delete($this->cookieAuthIdentifier),
            'token' => $this->cookie->delete($this->cookieAuthToken)
        );
        return ($savedCredentials->identifier) ? $savedCredentials : false;
    }
    
    function server_getUserSession() {
        if(isset($_SESSION[$this->sessionActiveUser])) {
            return json_decode($_SESSION[$this->sessionActiveUser], true);
        }
        return false;
    }
    
    function server_removeTokenCookies() {
        $this->cookie->delete($this->cookieAuthIdentifier);
        $this->cookie->delete($this->cookieAuthToken);
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
}
