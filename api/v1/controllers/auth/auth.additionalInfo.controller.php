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
           v::key('triviaLove', v::stringType())->validate($app->request->post()) &&
           v::key('acceptTerms', v::stringType())->validate($app->request->post())) {
            
            $question = array(
                ':user_id' => $app->request->post('userId'),
                ':question' => "How comitted are you?",
                ':answer' => $app->request->post('triviaLove')
            );
            
            $acceptTerms = array(
                ':user_id' => $app->request->post('userId'),
                ':accepted_terms' => ($app->request->post('acceptTerms') === 1 || 
                        $app->request->post('acceptTerms') === '1' || 
                        $app->request->post('acceptTerms') === true || 
                        $app->request->post('acceptTerms') === 'true') ? 1 : 0
            );
            $a = InfoData::insertQuestion($question);
            $b = InfoData::saveTerms($acceptTerms);
            if($a && $b) {
                return $app->render(200, array('msg' => 'Additional info saved.'));
            } else {
                return $app->render(400, array('msg' => 'Additional info  could not be saved.'));
            }
        } else if(v::key('userId', v::stringType())->validate($app->request->post()) &&
           v::key('acceptTerms', v::stringType())->validate($app->request->post())) {
            
            $data = array(
                ':user_id' => $app->request->post('userId'),
                ':accepted_terms' => ($app->request->post('acceptTerms') === 1 || 
                        $app->request->post('acceptTerms') === '1' || 
                        $app->request->post('acceptTerms') === true || 
                        $app->request->post('acceptTerms') === 'true') ? 1 : 0
            );
            
            if(InfoData::saveTerms($data)) {
                return $app->render(200, array('msg' => 'Terms and conditions acceptance saved.'));
            } else {
                return $app->render(400, array('msg' => 'Terms and conditions acceptance could not be saved.'));
            }
            
        }
        
        return $app->render(400, array('msg' => 'Save failed. Check your parameters and try again.'));
            
    }
    
    static function quietlySaveAdditional($post, $userId = false) {
        $saved = false;
        
        $userId = (!$userId && v::key('userId', v::stringType())->validate($post)) ? $post['userId'] : $userId;
        
        if($userId && v::key('referrer', v::stringType()->length(1, 255))->validate($post)) {
            
            $data = array(
                ':user_id' => $userId,
                ':question' => "Where did you about from us?",
                ':answer' => $post['referrer']
            );
            
            $saved = InfoData::insertQuestion($data);
            
        } 
        
        if($userId && v::key('triviaLove', v::stringType()->length(1, 255))->validate($post)) {
            
            $data = array(
                ':user_id' => $userId,
                ':question' => "How comitted are you?",
                ':answer' => $post['triviaLove']
            );
            
            $saved = InfoData::insertQuestion($data);
        } 
        
        if($userId && v::key('acceptTerms', v::stringType())->validate($post)) {
            
            $acceptTerms = ($post['acceptTerms'] === 1 || 
                        $post['acceptTerms'] === '1' || 
                        $post['acceptTerms'] === true || 
                        $post['acceptTerms'] === 'true') ? 1 : 0;
            
            $data = array(
                ':user_id' => $userId,
                ':accepted_terms' => $acceptTerms
            );
            
            $saved = InfoData::saveTerms($data);
        } 
        
        return $saved;
            
    }
    
}
