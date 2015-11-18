<?php namespace API;
require_once dirname(__FILE__) . '/auth.data.php';

class AuthSession {

    private function setSession($username) {
        $_SESSION['auth_active'] = $username;
    }
    
    private function destroySession() {
        session_unset();
        session_destroy();
    }
    
    private function getUserByEmailFromSession() {
        if(isset($_SESSION['auth_active'])) {
            $user = AuthData::selectUserByEmail($_SESSION['auth_active']);
            unset($user['password']);
            return $user;
        } else {
            return false;
        }
    }
    
    private function setRememberMeCookie($user) {
        $token = password_hash(uniqid(), PASSWORD_DEFAULT);
        if(AuthData::updateAuthToken($user['id'], $token)) {
            setcookie('auth_token', $token, time() + (86400 * 7), '/'); // Seven Days
            setcookie('email', $user['email'], time() + (86400 * 7), '/'); // Seven Days
        }
    }
    
    private function destroyCookie() {
        AuthData::deleteAuthToken(filter_input(INPUT_COOKIE, 'auth_token'));
        if (filter_input(INPUT_COOKIE, 'auth_token')) {
            unset($_COOKIE['auth_token']);
            setcookie('auth_token', null, -1, '/');
        }
        if (filter_input(INPUT_COOKIE, 'email')) {
            unset($_COOKIE['email']);
            setcookie('email', null, -1, '/');
        }
    }
    
    private function getUserByEmailFromCookie() {
        if(filter_input(INPUT_COOKIE, 'auth_token')) {
            return AuthData::selectUserByToken(filter_input(INPUT_COOKIE, 'auth_token'));
        } else {
            return false;
        }
    }
    
}
