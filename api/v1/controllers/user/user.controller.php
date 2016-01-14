<?php namespace API;
require_once dirname(dirname(__FILE__)) . '/auth/auth.controller.php';
require_once dirname(__FILE__) . '/user.data.php';

use \Respect\Validation\Validator as v;


class UserController {

    
    public static function getUsers($app) {
        $users = UserData::selectUsers();
        if($users) {
            return $app->render(200, array( 'users' => $users ));
        } else {
            return $app->render(400, array( 'msg' => 'Could not select users.' ));
        }
    }

    public static function getUser($app, $userId) {
        $user = UserData::selectUserById($userId);
        if($user) {
            return $app->render(200, array( 'user' => $user ));
        } else {
            return $app->render(400, array( 'msg' => 'User could not be found. Check your parameters and try again.' ));
        }
    }

    public static function addUser($app) {                    
        if(!v::key('first', v::string()->length(1,255))->validate($app->request->post()) ||
            !v::key('last', v::string()->length(0,255), false)->validate($app->request->post()) || 
            !v::key('email', v::string()->length(6,255))->validate($app->request->post())) {
            return $app->render(400, array( 'msg' => 'Invalid user. Check your parameters and try again.' ));
        } else if(!AuthController::validatePassword($app->request->post('password'))) {
            return $app->render(400, array( 'msg' => "Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number. "
                    . "Check your parameters and try again."));
        } 
        
        $found = UserData::selectOtherUsersWithEmail($app->request->post('email'));
        
        if ($found && count($found) > 0) {
            return $app->render(400, array( 'msg' => 'An account with that email already exists. No two users may have the same email address.' ));
        } else {
            $data = array(
                ':name_first' => $app->request->post('first'),
                ':name_last' => $app->request->post('last'),
                ':email' => $app->request->post('email'),
                ':password' => password_hash($app->request->post('password'), PASSWORD_DEFAULT)
            );
            $userId = UserData::insertUser($data);
            $user = UserData::selectUserById($userId);
            return $app->render(200, array( 'user' => $user ));
        }
    }

    public static function saveUser($app, $userId) {                   
        if(!v::key('first', v::string()->length(1,255))->validate($app->request->post()) ||
            !v::key('last', v::string()->length(0,255), false)->validate($app->request->post()) || 
            !v::key('email', v::string()->length(6,255))->validate($app->request->post())) {
            return $app->render(400, array( 'msg' => 'Invalid user. Check your parameters and try again.' ));
        } 
        
        $found = UserData::selectOtherUsersWithEmail($app->request->post('email'), $userId);
        
        if ($found && count($found) > 0) {
            return $app->render(400, array( 'msg' => 'An account with that email already exists. No two users may have the same email address.' ));
        } else {
            $data = array(
                ':id' => $userId,
                ':name_first' => $app->request->post('first'),
                ':name_last' => $app->request->post('last'),
                ':email' => $app->request->post('email'),
            );
            UserData::updateUser($data);
            $user = UserData::selectUserById($userId);
            return $app->render(200, array( 'user' => $user ));
        }
    }

    public static function deleteUser($app, $userId) {
        if(UserData::deleteUser($userId)) {
            return $app->render(200, array( 'msg' => 'User has been deleted.' ));
        } else {
            return $app->render(400, array( 'msg' => 'Could not delete user. Check your parameters and try again.' ));
        }
    }
}
