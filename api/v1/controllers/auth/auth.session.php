<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';

class AuthSession {
    
    private $cookieUserEmail = '_rcsessUE';
    private $cookieAuthToken = '_rcsessAT';
    private $cookieLoginAttempts = '_rcsessLA';
    private $sessionActiveUserId = '_rcsessAUI';

    private function getTimeout($time, $measure = 'hour') {
        $timeout = time();
        switch ($measure) {
            case 'min':
                $timeout = $timeout * (60*$time);
                break;
            case 'day':
                $timeout = $timeout * (60*60*24*$time);
                break;
            case 'hour':
            default:
                $timeout = $timeout * (60*60*$time);
                break;
        }
        return $timeout;
    }
    
    private function destroyCookie($name) {
        setcookie($name, '', time()-3600);
        unset($_COOKIE[$name]);
        return true;
    }
    
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
    
    
    
    
    
    
    
    /*
    
    private function setRememberMeCookie($user) {
        $token = password_hash(uniqid(), PASSWORD_DEFAULT);
        if(AuthData::updateAuthToken($user['id'], $token)) {
            setcookie($this->cookieAuthToken, $token, time() + (86400 * 7), '/'); // Seven Days
            setcookie($this->cookieUserEmail, $user[$this->cookieUserEmail], time() + (86400 * 7), '/'); // Seven Days
        }
    }
    
    
    
    
    
    
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
