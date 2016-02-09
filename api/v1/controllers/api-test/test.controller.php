<?php namespace API;
require_once dirname(__FILE__) . '/test.data.php';

class TestController {
    static function getApiStatus($app) {
        $data = TestData::userTableTestVal();
        
        if ($data) {
            return $app->render(200, array('test' => 'Database test query successful.'));
        } else {
            return $app->render(400, array('test' => 'Database test query failed.'));
        }
    }
    
}
