<?php namespace API;
require_once dirname(__FILE__) . '/emails.data.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.mailer.php';

use \Respect\Validation\Validator as v;


class EmailController {

    private static function makeInviteToken() {
        return hash('sha1', uniqid());
    }
    
    static function validateInviteToken($app, $token) {       
        $savedVist = EmailData::updateInviteLastVisited($token);
        if(!$savedVist) {
            return $app->render(400, array('msg' => 'Token is invalid.'));
        }
        
        $invite = EmailData::selectInviteByToken($token);
        if($invite) {
            return $app->render(200, array("invite" => $invite));
        } else {
            return $app->render(400, array('msg' => 'Token has expired.'));
        }
    }
    
    static function sendPlayerInviteEmail($app) {
        $post = $app->request->post();
        if(!v::key('email', v::email())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid email. Check your parameters and try again.'));
        }
        
        $foundId = EmailData::selectUserIdByEmail($post['email']);
        if($foundId) {
            return $app->render(400,  array('msg' => 'This email is already registered to a player.'));
        }
        
        $token = self::makeInviteToken();
        $firstName = (v::key('nameFirst', v::stringType())->validate($post)) ? $post['nameFirst'] : NULL;
        $lastName = (v::key('nameFirst', v::stringType())->validate($post)) ? $post['nameFirst'] : NULL;
        $saved = EmailData::insertPlayerInvite(array(
            ":token" => $token, 
            ":name_first" => $firstName, 
            ":name_last" => $lastName, 
            ":email" => $post['email'],
            ":phone" => (v::key('phone', v::stringType())->validate($post)) ? $post['phone'] : NULL, 
            ":created_user_id" => APIAuth::getUserId()
        ));
        if(!$saved) {
            return $app->render(400,  array('msg' => 'Could not create invite. Check your parameters and try again.'));
        }
                
        $isFirstSet = (is_null($firstName)) ? false : $firstName;
        $name = ($isFirstSet === false || is_null($lastName)) ? '' : "{$firstName} {$lastName}";
        $sent = ApiMailer::sendWebsiteSignupInvite($token, $post['email'], $name);
        if($sent) {
            return $app->render(200, array('msg' => "Player invite sent to '{$post['email']}'."));
        } else {
            return $app->render(400, array('msg' => 'Could not send player invite.'));
        }
    }
    
    static function sendTeamInviteEmail($app) {
        $post = $app->request->post();
        if((!v::key('email', v::email())->validate($post) && !v::key('userId', v::intVal())->validate($post)) ||
           !v::key('teamId', v::intVal())->validate($post) ||
           !v::key('teamName', v::stringType())->validate($post) ||
           !v::key('invitedById', v::intVal())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid email. Check your parameters and try again.'));
        }
            
        // Try to set the players user id if the email exists in the DB
        $foundId = (v::key('userId', v::intVal())->validate($post)) ? $post['userId'] :
                EmailData::selectUserIdByEmail($post['email']);
        $userId = (!$foundId) ? NULL : $foundId;
        
        
        $first = (!v::key('nameFirst', v::stringType())->validate($post)) ? NULL : $post['nameFirst'];
        $last = (!v::key('nameLast', v::stringType())->validate($post)) ? NULL : $post['nameLast'];
        $phone = (!v::key('phone', v::stringType())->validate($post)) ? NULL : $post['phone'];
        
        $token = self::makeInviteToken();
        $saved = EmailData::insertTeamInvite(array(
            ":token" => $token, 
            ":team_id" => $post['teamId'], 
            ":user_id" => $userId, 
            ":name_first" => $first, 
            ":name_last" => $last, 
            ":email" => $post['email'],
            ":phone" => $phone, 
            ":created_user_id" => $post['invitedById']
        ));
        
        if(!$saved) {
            return $app->render(400,  array('msg' =>  'Could not create invite. Check your parameters and try again.'));
        }
        
        $playerName = (is_null($first) || is_null($last)) ? '' : "{$first} {$last}";        
        
        $result = (is_null($userId)) ? ApiMailer::sendTeamInviteNewUser($token, $post['teamName'], $post['email'], $playerName) : 
            ApiMailer::sendTeamInviteRegisteredUser($token, $post['teamName'], $post['email'], $playerName);
        
        return ($result['error']) ? $app->render(400, $result) : $app->render(200, $result);
    }
    
    
    static function silentlySendTeamInviteEmail($teamId, $teamName, $playerEmail, $playerId = NULL, $playerName = '') {
        
        // Try to set the players user id if the email exists in the DB
        $foundId = (is_null($playerId)) ? EmailData::selectUserIdByEmail($playerEmail) : $playerId;
        $userId = (!$foundId) ? NULL : $foundId;
        
        $token = self::makeInviteToken();
        $saved = EmailData::insertTeamInvite(array(
            ":token" => $token, 
            ":team_id" => $teamId, 
            ":user_id" => $userId, 
            ":name_first" => NULL, 
            ":name_last" => NULL, 
            ":email" => $playerEmail,
            ":phone" => NULL, 
            ":created_user_id" => APIAuth::getUserId()
        ));
        
        if(!$saved) {
            return 'Could not create invite. Check your parameters and try again.';
        }
        
        if(is_null($userId)) {
            return ApiMailer::sendTeamInviteNewUser($token, $teamName, $playerEmail, $playerName);
        } else {
            return ApiMailer::sendTeamInviteRegisteredUser($token, $teamName, $playerEmail, $playerName);
        }
    }
    
    static function acceptTeamInvite($app) {
        $post = $app->request->post();
        if(!v::key('inviteToken', v::stringType())->validate($post) ||
            !v::key('userId', v::intVal())->validate($post) ||
            !v::key('teamId', v::intVal())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid token. Check your parameters and try again.'));
        }
        
        $sent = EmailData::updateAcceptTeamInvite(array(
            ':token' => $post['inviteToken'], 
            ':team_id' => $post['teamId'], 
            ':user_id' => $post['userId']
        ));
        if($sent) {
            return $app->render(200, array('msg' => "Team invitation has been accepted."));
        } else {
            return $app->render(400, array('msg' => 'Could not update team invite.'));
        }
    }
    
    static function declineTeamInvite($app) {
        $post = $app->request->post();
        if(!v::key('inviteToken', v::stringType())->validate($post) ||
            !v::key('userId', v::intVal())->validate($post) ||
            !v::key('teamId', v::intVal())->validate($post)) {
            return $app->render(400,  array('msg' => 'Invalid token. Check your parameters and try again.'));
        }
        
        $sent = EmailData::updateDeclineInvite(array(
            ':token' => $post['inviteToken'], 
            ':team_id' => $post['teamId'], 
            ':user_id' => $post['userId']
        ));
        if($sent) {
            return $app->render(200, array('msg' => "Team invitation has been declined."));
        } else {
            return $app->render(400, array('msg' => 'Could not update team invite.'));
        }
    }
}
