<?php

namespace API;

require_once dirname(dirname(dirname(__FILE__))) . '/services/api.auth.php';
require_once dirname(dirname(dirname(__FILE__))) . '/services/api.mailer.php';
require_once dirname(__FILE__) . '/auth.data.php';
require_once dirname(__FILE__) . '/auth.additionalInfo.data.php';
require_once dirname(__FILE__) . '/auth.controller.native.php';
require_once dirname(__FILE__) . '/auth.controller.facebook.php';
require_once dirname(__FILE__) . '/auth.hooks.php';

use \Respect\Validation\Validator as v;

class AuthController {

    ///// 
    ///// Authentication
    ///// 

    /*
     * apiKey, apiToken
     */
    static function isAuthenticated($app) {
        $found = AuthControllerNative::isAuthenticated($app);
        if ($found['authenticated']) {
            return $app->render(200, $found);
        } else {
            return $app->render(401, $found);
        }
    }

    ///// 
    ///// Sign Up
    ///// 

    /* email, nameFirst, nameLast, password */

    static function signup($app) {
        $result = AuthControllerNative::signup($app);
        if ($result['registered']) {
            AuthHooks::signup($app, $result);
            if (isset($result['user']->teams[0])) {
                ApiMailer::sendWebsiteSignupJoinTeamConfirmation($result['user']->teams[0]->name, $result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            } else {
                ApiMailer::sendWebsiteSignupConfirmation($result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            }
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    /* email, nameFirst, nameLast, facebookId, accessToken */

    static function facebookSignup($app) {
        $result = AuthControllerFacebook::signup($app);
        if ($result['registered']) {
            AuthHooks::signup($app, $result);
            if (isset($result['user']->teams[0])) {
                ApiMailer::sendWebsiteSignupJoinTeamConfirmation($result['user']->teams[0]->name, $result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            } else {
                ApiMailer::sendWebsiteSignupConfirmation($result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            }
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    ///// 
    ///// Authentication
    ///// 

    /*
     * email, password, remember
     */
    static function login($app) {
        $result = AuthControllerNative::login($app);
        if ($result['authenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(401, $result);
        }
    }

    static function forgotpassword($app) {
        $result = AuthControllerNative::forgotpassword($app);
        if ($result['frgtauthenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    static function getforgotpasswordemail($app) {
        $result = AuthControllerNative::getforgotpasswordemail($app);
        if ($result['frgtauthenticatedemail']) {
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    static function resetpassword($app) {
        $result = AuthControllerNative::resetpassword($app);
        if ($result['resetpasswordauthenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(400, $result);
        }
    }

    static function facebookLogin($app) {
        $result = AuthControllerFacebook::login($app);
        if ($result['authenticated']) {
            return $app->render(200, $result);
        } else {
            return $app->render(401, $result);
        }
    }

    ///// 
    ///// Logout
    ///// 

    /*
     * logout (apiKey)
     */
    static function logout($app) {
        if (AuthControllerNative::logout($app)) {
            return $app->render(200, array('msg' => "User sucessfully logged out."));
        } else {
            return $app->render(400, array('msg' => "User could not be logged out. Check your parameters and try again."));
        }
    }

    static function changeUserPassword($app) {
        $post = $app->request->post();
        if ((!v::key('userId', v::stringType())->validate($post) && !v::key('email', v::stringType())->validate($post)) ||
                !v::key('current', v::stringType())->validate($post)) {
            return $app->render(400, array('msg' => "Password could not be changed. Check your parameters and try again."));
        } else if (!AuthControllerNative::validatePasswordRequirements($post, 'new')) {
            return $app->render(400, array('msg' => "Invalid Password. Check your parameters and try again."));
        }

        $savedPassword = (v::key('userId', v::stringType())->validate($post)) ? AuthData::selectUserPasswordById($post['userId']) :
                AuthData::selectUserPasswordByEmail($post['email']);

        if (!$savedPassword) {
            return $app->render(400, array('msg' => "User not found. Check your parameters and try again."));
        } else if (!password_verify($post['current'], $savedPassword)) {
            return $app->render(400, array('msg' => "Invalid user password. Unable to verify request."));
        } else {
            if (AuthData::updateUserPassword(array(':id' => $post['userId'], ':password' => password_hash($post['new'], PASSWORD_DEFAULT)))) {
                return $app->render(200, array('msg' => "Password successfully changed."));
            } else {
                return $app->render(400, array('msg' => "Password could not be changed. Try again later."));
            }
        }
    }

    ///// 
    // System Admin
    // TODO: Create system functions class
    ///// 
    // TODO: Add this to Cron Job
    static function deleteExpiredAuthTokens($app) {
        AuthData::deleteExpiredAuthTokens();
        return $app->render(200, array('msg' => "Deleted expired auth tokens."));
    }

}
