<?php namespace API;
require_once dirname(__FILE__) . '/config.data.php';

use \Respect\Validation\Validator as v;


class ConfigController {

    static function getConfig($app, $configId) {
        $config = ConfigData::getConfig($configId);
        if($config) {
            return $app->render(200, array('config' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select config.'));
        }
    }
    
    static function addConfig($app) {
        $config = ConfigData::insertConfig();
        if($config) {
            return $app->render(200, array('config' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select config.'));
        }
    }
    
    static function saveConfig($app) {
        $config = ConfigData::updateConfig();
        if($config) {
            return $app->render(200, array('config' => $config));
        } else {
            return $app->render(400,  array('msg' => 'Could not select config.'));
        }
    }
    
    static function deleteConfig($app, $configId) {
        if(ConfigData::deleteConfig($configId)) {
            return $app->render(200,  array('msg' => 'Config has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete config. Check your parameters and try again.'));
        }
    }
}
