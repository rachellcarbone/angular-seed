<?php namespace API;
require_once dirname(__FILE__) . '/actions.data.php';

use \Respect\Validation\Validator as v;


class ActionController {
    
    static function addAction($app) {
        $post = $app->request->post();
        
        // Validate parameters
        // Must have one or the other, or both 'action' and 'code'
        if(!v::key('action', v::stringType())->validate($post) &&
           !v::key('code', v::stringType())->validate($post)) {
            // Validate input parameters
            return $app->render(400, array('msg' => 'Add action failed. Check your parameters and try again.'));
        }
        
        // Add the verifed action
        $newAction = array (
            ":action" => (v::key('action', v::stringType())->validate($post)) ? $app->request->post('action') : '',
            ":code" => (v::key('code', v::stringType())->validate($post)) ? $app->request->post('code') : '',
            ":http_referer" => $app->request->getReferrer(),
            ":ip_address" => $app->request->getIp(),
            ":created_user_id" => APIAuth::getUserId()
        );
        $actionId = ActionData::insertAction($newAction);
        
        if($actionId) {
            return $app->render(200, array('msg' => 'Action recorded.', 'action' => $actionId));
        } else {
            return $app->render(400,  array('msg' => 'Could not add new action.', 'action' => $newAction));
        }
    }
    
}
