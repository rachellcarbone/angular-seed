<?php namespace API;
require_once dirname(__FILE__) . '/user.data.php';

use \Respect\Validation\Validator as v;

class UserData {
  
    function __construct() {
        parent::__construct();
    }

    public function getUser($app, $userId) {
        $user = $this->db_selectUserById($userId);

        if ($user) {
            $app->render(200, $this->setResponse->success(array( 'user' => $user )));
        } else {
            $app->render(200, $this->setResponse->fail(array( 'user' => false )));
        }
    }
}
