<?php namespace API;
require_once dirname(__FILE__) . '/user.data.php';

use \Respect\Validation\Validator as v;

class UserController {

    public static function getUsers($app) {
        $data = UserData::selectUsers();
        $app->render(200, array( 'users' => $data ));
    }

    public static function getUser($app, $userId) {
        $data = UserData::selectUserById($userId);
        $app->render(200, array( 'user' => $data ));
    }

    public static function addUser($app) {
        $data = array(
            ':name_first' => $app->request->post('first'),
            ':name_last' => $app->request->post('last'),
            ':email' => $app->request->post('email'),
            ':password' => $app->request->post('password'),
            ':role_id' => $app->request->post('role_id'),
            ':plan_id' => $app->request->post('plan_id')
        );
        $id = UserData::insertUser($data);
        $data['id'] = $id;
        $app->render(200, array( 'user' => $data ));
    }

    public static function saveUser($app, $userId) {
        $data = array(
            ':id' => $userId,
            ':name_first' => $app->request->post('first'),
            ':name_last' => $app->request->post('last'),
            ':email' => $app->request->post('email'),
            ':password' => $app->request->post('password'),
            ':role_id' => $app->request->post('role_id'),
            ':plan_id' => $app->request->post('plan_id')
        );
        $data = UserData::updateUser($data);
        $app->render(200, array( 'user' => $data ));
    }

    public static function deleteUser($app, $userId) {
        $data = UserData::deleteUser($userId);
        $app->render(200, array( 'user' => $data ));
    }
}
