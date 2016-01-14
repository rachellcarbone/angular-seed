<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class AuthData {
    
    private function selectUserById($id) {
        return DBConn::selectOne("SELECT id, email FROM " . DBConn::prefix() . "users WHERE id = '{$id}' Limit 1;");
    }
    
    private function selectUserPasswordById($id) {
        return DBConn::selectOne("SELECT password FROM " . DBConn::prefix() . "users WHERE id = '{$id}' Limit 1;");
    }
    
    private function selectUserByEmail($email) {
        return DBConn::selectOne("SELECT id, name_first, name_last, email, password FROM " . DBConn::prefix() . "users WHERE email = '{$email}' Limit 1;");
    }
    
    private function selectResetTokenForUser($id) {
        return DBConn::select('SELECT id FROM ' . DBConn::prefix() . 'tokens_reset_password WHERE id = ' . $id . ';');
    }
   
    private function selectResetToken($token) {
        return DBConn::selectOne('SELECT id, token, id, expires FROM ' . DBConn::prefix() . 'tokens_reset_password WHERE token = "' . $token . '" OR token = "' . urlencode($token) . '"  OR token = "' . urldecode($token) . '" Limit 1;');
    }
    
    private function deleteResetToken($id) {
        return DBConn::query('DELETE FROM ' . DBConn::prefix() . 'tokens_reset_password WHERE id = ' . $id . ';');
    }
    
    private function deleteResetTokenForUser($id) {
        return DBConn::query('DELETE FROM ' . DBConn::prefix() . 'tokens_reset_password WHERE id = ' . $id . ';');
    }
    
    private function updateUserPassword($userId, $password) {
        $auth = array('id' => $userId, 'password' => password_hash($password, PASSWORD_DEFAULT));
        return DBConn::prepairedQuery('UPDATE ' . DBConn::prefix() . 'users SET password = :password WHERE id = :id;', $auth);
    }

    private function updatePasswordResetToken($id, $token, $expires) {
        $resetToken = array(':id' => $id, ':token' => $token, ':expires' => $expires);
        
        $tokens = self::selectResetTokenForUser($id);
            
        if(count($tokens) === 1) {
            return DBConn::prepairedQuery('UPDATE ' . DBConn::prefix() . 'tokens_reset_password SET token = :token, expires = :expires WHERE id = :id;', $resetToken);
        } else {
            self::deleteResetTokenForUser($id);
            return DBConn::prepairedQuery('INSERT INTO ' . DBConn::prefix() . 'tokens_reset_password (token, id, expires) '
                . 'VALUES (:token, :id, :expires);', $resetToken);
        }
    }
}
