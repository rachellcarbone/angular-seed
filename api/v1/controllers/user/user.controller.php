<?php namespace API;
require_once dirname(__FILE__) . '/user.data.php';

use \Respect\Validation\Validator as v;


class UserController {

    static function selectUser($app, $userId) {
        if(!v::intVal()->validate($userId)) {
            return $app->render(400,  array('msg' => 'Could not find user. Check your parameters and try again.'));
        }
        $user = UserData::selectUserById($userId);
        if($user) {
            return $app->render(200, array('user' => $user ));
        } else {
            return $app->render(400,  array('msg' => 'User could not be found.'));
        }
    }
    
    static function insertUser($app) {
        if(!v::key('nameFirst', v::stringType()->length(0,255))->validate($app->request->post()) ||
            !v::key('nameLast', v::stringType()->length(0,255), false)->validate($app->request->post()) || 
            !v::key('email', v::email())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Invalid user. Check your parameters and try again.'));
        } else if(!self::validatePassword($app->request->post())) {
            return $app->render(400,  array('msg' => "Passwords must be at least 8 characters "
                    . "long, contain no whitespace, have at least one letter and one number. "
                    . "Check your parameters and try again."));
        } 
        
        $found = UserData::selectOtherUsersWithEmail($app->request->post('email'));
        
        if ($found && count($found) > 0) {
            return $app->render(400,  array('msg' => 'An account with that email already exists. No two users may have the same email address.'));
        } else {
            $data = array(
                ':name_first' => $app->request->post('nameFirst'),
                ':name_last' => $app->request->post('nameLast'),
                ':email' => $app->request->post('email'),
                ':password' => password_hash($app->request->post('password'), PASSWORD_DEFAULT)
            );
            $userId = UserData::insertUser($data);
            $user = UserData::selectUserById($userId);
            return $app->render(200, array('user' => $user ));
        }
    }
    
    private static function validatePassword($post, $key = 'password') {
        return (v::key($key, v::stringType()->length(8,255)->noWhitespace()->alnum('!@#$%^&*_+=-')->regex('/^(?=.*[a-zA-Z])(?=.*[0-9])/'))->validate($post));
    }

    static function updateUser($app, $userId) {
        $post = $app->request->post();
        if(!v::intVal()->validate($userId) ||
            !v::key('nameFirst', v::stringType()->length(0,255))->validate($post) ||
            !v::key('nameLast', v::stringType()->length(0,255), false)->validate($post) || 
            !v::key('phone', v::stringType()->length(0,20), false)->validate($post) || 
            !v::key('email', v::email())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid user. Check your parameters and try again.'));
        } 
        
        $found = UserData::selectOtherUsersWithEmail($post['email'], $userId);
        
        if ($found && count($found) > 0) {
            return $app->render(400,  array('msg' => 'An account with that email already exists. No two users may have the same email address.'));
        }
        
        $data = array(
            ':id' => $userId,
            ':name_first' => $post['nameFirst'],
            ':name_last' => $post['nameLast'],
            ':email' => $post['email'],
            ':phone' => $post['phone']
        );
        UserData::updateUser($data);
        
        if((v::key('disabled', v::stringType()->length(1,5))->validate($post)) && 
                ($post['disabled'] === true || $post['disabled'] === 'true')) {
            UserData::disableUser($userId);
        } else if((v::key('disabled', v::stringType()->length(1,5))->validate($post)) && 
                ($post['disabled'] === false || $post['disabled'] === 'false')) {
            UserData::enableUser($userId);
        }
        
        $user = UserData::selectUserById($userId);
        return $app->render(200, array('user' => $user));
    }

    // TODO: Delete user from any look up tables
    // TODO: Add hooks for events such as deleting  auser so I dont have to import other controllers
    static function deleteUser($app, $userId) {
        if(!v::intVal()->validate($userId)) {
            return $app->render(400,  array('msg' => 'Could not find user. Check your parameters and try again.'));
        }
        if(UserData::deleteUser($userId)) {
            return $app->render(200,  array('msg' => 'User has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete user.'));
        }
    }
    
    static function unassignGroup($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('userId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not unassign user from group. Check your parameters and try again.'));
        } 
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':user_id' => $app->request->post('userId')
        );
        
        if(UserData::deleteGroupAssignment($data)) {
            return $app->render(200,  array('msg' => 'User has been unassigned from group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not unassign user from group.'));
        }
    }
    
    static function assignGroup($app) {
        if(!v::key('groupId', v::stringType())->validate($app->request->post()) || 
           !v::key('userId', v::stringType())->validate($app->request->post())) {
            return $app->render(400,  array('msg' => 'Could not assign user from group. Check your parameters and try again.'));
        }
        
        $data = array (
            ':auth_group_id' => $app->request->post('groupId'),
            ':user_id' => $app->request->post('userId'),
            ":created_user_id" => APIAuth::getUserId()
        );
        
        if(UserData::insertGroupAssignment($data)) {
            return $app->render(200,  array('msg' => 'User has been assigned to group.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not assign user to group.', 'data' => $data));
        }
    }
}
