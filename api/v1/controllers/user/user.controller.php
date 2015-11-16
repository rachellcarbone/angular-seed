<?php namespace API;
require_once dirname(__FILE__) . '/user.data.php';

use \Respect\Validation\Validator as v;

class UserController extends UserData {
  
    public function __construct() {
        parent::__construct();
    }

    public function getUser($app, $userId) {
        $user = $this->db_selectUserById($userId);
        $app->render(200, array( 'user' => $user ));
    }
}
