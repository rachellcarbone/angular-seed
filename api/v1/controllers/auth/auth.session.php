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
    
    public function __construct() {
        session_start();
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
        setcookie($name, '', time()-3600);
        unset($_COOKIE[$name]);
        return true;
    }
    
    private function makeToken($string) {
        return hash('sha256', $string);
    }
    
    // Login Attempts
    
    public function getFailedLoginAttempts($email) {
        $cookie = array();
        if (filter_input(INPUT_COOKIE, $this->cookieLoginAttempts)) {
            $cookie = json_decode(filter_input(INPUT_COOKIE, $this->cookieLoginAttempts), true);
        }
        $attempts = (isset($cookie[$email])) ? intval($cookie[$email]) : 0;
        return $attempts;
    }
    
    public function loginAttemptFailed($email) {
        $attempts = $this->getFailedLoginAttempts($email);
        $attempts = $attempts + 1;
        $cookie = array($email => $attempts);
        setcookie($this->cookieLoginAttempts, json_encode($cookie), $this->getTimeout(5, 'min'));
        return $attempts;
    }
    
    public function clearLoginAttempts() {
        return $this->destroyCookie($this->cookieLoginAttempts);
    }
    
    // Active user login
    
    public function createLoggedInSession($user, $remember = false) {
        $this->clearLoggedInSession();
        
        $identifier = $this->makeToken(uniqid());
        $token = $this->makeToken(uniqid());
        
        $timeout = ($remember) ? $this->getTimeout(4, 'hour') : $this->getTimeout(3, 'day');
        
        setcookie($this->cookieAuthIdentifier, $identifier, $timeout);
        setcookie($this->cookieAuthToken, $token, $timeout);
        $_SESSION[$this->sessionActiveUser] = json_encode($user);
        
        return (object) array(
            'identifier' => $identifier,
            'token' => password_hash($token, PASSWORD_DEFAULT),
            'expires' => date('Y-m-d H:i:s', $timeout)
        );
    }
    
    public function clearLoggedInSession() {
        $this->destroyCookie($this->cookieAuthIdentifier);
        $this->destroyCookie($this->cookieAuthToken);
        session_unset();
        session_destroy();
        return true;
    }
    
    
    
    
    /*
    private function setSession($username) {
        $_SESSION[$this->sessionActiveUserId] = $username;
    }
    
    private function destroySession() {
        session_unset();
        session_destroy();
    }
    
    private function getUserByEmailFromSession() {
        if(isset($_SESSION[$this->sessionActiveUserId])) {
            $user = AuthData::selectUserByEmail($_SESSION[$this->sessionActiveUserId]);
            unset($user['password']);
            return $user;
        } else {
            return false;
        }
    }
    
    private function destroyCookie() {
        AuthData::deleteAuthToken(filter_input(INPUT_COOKIE, $this->cookieAuthToken));
        if (filter_input(INPUT_COOKIE, $this->cookieAuthToken)) {
            unset($_COOKIE[$this->cookieAuthToken]);
            setcookie($this->cookieAuthToken, null, -1, '/');
        }
        if (filter_input(INPUT_COOKIE, $this->cookieUserEmail)) {
            unset($_COOKIE[$this->cookieUserEmail]);
            setcookie($this->cookieUserEmail, null, -1, '/');
        }
    }
    
    private function getUserByEmailFromCookie() {
        if(filter_input(INPUT_COOKIE, $this->cookieAuthToken)) {
            return AuthData::selectUserByToken(filter_input(INPUT_COOKIE, $this->cookieAuthToken));
        } else {
            return false;
        }
    }
    */
}
