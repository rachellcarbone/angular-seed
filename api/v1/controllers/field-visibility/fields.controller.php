<?php namespace API;
require_once dirname(__FILE__) . '/fields.data.php';

use \Respect\Validation\Validator as v;


class FieldController {

    static function getField($app, $fieldId) {
        if(!v::intVal()->validate($fieldId)) {
            return $app->render(400,  array('msg' => 'Could not find field.'));
        }
        $field = FieldData::getField($fieldId);
        if($field) {
            return $app->render(200, array('field' => $field));
        } else {
            return $app->render(400,  array('msg' => 'Could not select field.'));
        }
    }
    
    static function addField($app) {
        $field = FieldData::insertField();
        if($field) {
            return $app->render(200, array('field' => $field));
        } else {
            return $app->render(400,  array('msg' => 'Could not select field.'));
        }
    }
    
    static function saveField($app) {
        $field = FieldData::updateField();
        if($field) {
            return $app->render(200, array('field' => $field));
        } else {
            return $app->render(400,  array('msg' => 'Could not select field.'));
        }
    }
    
    static function deleteField($app, $fieldId) {
        if(!v::intVal()->validate($fieldId)) {
            return $app->render(400,  array('msg' => 'Could not find field.'));
        } else if(FieldData::deleteField($fieldId)) {
            return $app->render(200,  array('msg' => 'Field has been deleted.'));
        } else {
            return $app->render(400,  array('msg' => 'Could not delete field. Check your parameters and try again.'));
        }
    }
}
