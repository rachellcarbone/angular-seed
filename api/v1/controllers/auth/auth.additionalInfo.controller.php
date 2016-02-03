<?php namespace API;
require_once dirname(__FILE__) . '/auth.additionalInfo.data.php';

use \Respect\Validation\Validator as v;

class InfoController {
    
    static function saveAdditional($app) {
        if(v::key('userId', v::stringType())->validate($app->request->post()) && 
           v::key('referrer', v::stringType())->validate($app->request->post())) {
            
            $data = array(
                ':user_id' => $app->request->post('userId'),
                ':question' => "Where did you about from us?",
                ':answer' => $app->request->post('referrer')
            );
            
            if(InfoData::insertQuestion($data)) {
                return $app->render(200, array('msg' => 'Referrer saved.'));
            } else {
                return $app->render(400, array('msg' => 'Referrer could not be saved.'));
            }
            
        } else if(v::key('userId', v::stringType())->validate($app->request->post()) &&
           v::key('triviaLove', v::stringType())->validate($app->request->post())) {
            
            $data = array(
                ':user_id' => $app->request->post('userId'),
                ':question' => "How comitted are you?",
                ':answer' => $app->request->post('triviaLove')
            );
            
            if(InfoData::insertQuestion($data)) {
                return $app->render(200, array('msg' => 'Love for trivia saved.'));
            } else {
                return $app->render(400, array('msg' => 'Love for trivia could not be saved.'));
            }
        }
        
        return $app->render(400, array('msg' => 'Save failed. Check your parameters and try again.'));
            
    }
    
}
