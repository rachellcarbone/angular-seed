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

    /* email, nameFirst, nameLast, password */

    static function venueSignup($app) {
        $isValidVenue = self::addVenue($app, 0, true);
        $venue = false;
        if ($isValidVenue) {
            $result = AuthControllerNative::signup($app);
            if (!$result['registered']) {
                return $app->render(400, $result);
            }
            if (isset($result['user']->teams[0])) {
                ApiMailer::sendWebsiteSignupJoinTeamConfirmation($result['user']->teams[0]->name, $result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            } else {
                ApiMailer::sendWebsiteSignupConfirmation($result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            }
            $venue = self::addVenue($app, $result['user']->id);
        }
        if (!$venue) {
            return $app->render(400, "Could not add venue. Check your parameters and try again.");
        }
        $venue_reponse['venue'] = (object) [];
        $venue_reponse['venue']->id = $venue;
        AuthHooks::venue_signup($app, $venue_reponse);
        self::addVenueGroupToUser($result['user']->id);
        self::addVenueRole($result['user']->id, $venue, 'owner');
        return $app->render(200, $result);
    }

    static function venueFacebookSignup($app) {
        $isValidVenue = self::addVenue($app, 0, true);
        $venue = false;
        if ($isValidVenue) {
            $result = AuthControllerFacebook::signup($app);
            if (!$result['registered']) {
                return $app->render(400, $result);
            }
            if (isset($result['user']->teams[0])) {
                ApiMailer::sendWebsiteSignupJoinTeamConfirmation($result['user']->teams[0]->name, $result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            } else {
                ApiMailer::sendWebsiteSignupConfirmation($result['user']->email, "{$result['user']->nameFirst} {$result['user']->nameLast}");
            }
            $venue = self::addVenue($app, $result['user']->id);
        }
        if (!$venue) {
            return $app->render(400, "Could not add venue. Check your parameters and try again.");
        }
        $venue_reponse['venue'] = (object) [];
        $venue_reponse['venue']->id = $venue;
        AuthHooks::venue_signup($app, $venue_reponse);
        self::addVenueGroupToUser($result['user']->id);
        self::addVenueRole($result['user']->id, $venue, 'owner');
        return $app->render(200, $result);
    }

    static function addVenue($app, $userId, $onlyValidation = false) {
        $post = $app->request->post();

        if (!v::key('venue', v::stringType())->validate($post) ||
                !v::key('address', v::stringType())->validate($post) ||
                !v::key('city', v::stringType())->validate($post) ||
                !v::key('state', v::stringType())->validate($post) ||
                !v::key('zip', v::stringType())->validate($post)) {
            return false;
        }


        if (!v::url()->validate($post["website"])) {
            return $app->render(400, array('msg' => $post["website"] . ' is not valid URL.'));
        }

        if (!preg_match('/(?:https?:\/\/)?(?:www\.)?facebook\.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[\w\-]*\/)*([\w\-\.]*)/', $post["facebook"])) {
            return $app->render(400, array('msg' => $post["facebook"] . ' is not valid facebook URL.'));
        }


        $dayNames = array(
            'sunday',
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
        );

        if ($post['triviaDay'] != '') {
            $status = in_array(strtolower($post['triviaDay']), $dayNames);
            if (!$status) {
                return $app->render(400, array('msg' => 'Day is not corect.'));
            }
        }

        if ($post['triviaTime'] != '') {
            if (!preg_match('/^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM)$/i', $post['triviaTime'])) {
                return $app->render(400, array('msg' => 'Time is not corect.'));
            }
        }

        if (v::key('phone', v::stringType())->validate($post) && $post['phone'] != '') {
            if (!preg_match('/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{3})[\)\.\-\s]*([\d]{3})[\.\-\s]?([\d]{4})$/', $post["phone"])) {
                return $app->render(400, array('msg' => 'This is not valid US format number.'));
            }
        }


        if ($onlyValidation === false) {
            $venue = array(
                ':name' => $post['venue'],
                ':address' => $post['address'],
                ':address_b' => (v::key('addressb', v::stringType())->validate($post)) ? $post['addressb'] : '',
                ':city' => $post['city'],
                ':state' => $post['state'],
                ':zip' => $post['zip'],
                ':phone_extension' => (v::key('phone_extension', v::stringType())->validate($post)) ? $post['phone_extension'] : '',
                ':phone' => (v::key('phone', v::stringType())->validate($post)) ? $post['phone'] : '',
                ':website' => (v::key('website', v::stringType())->validate($post)) ? $post['website'] : '',
                ':facebook_url' => (v::key('facebook', v::stringType())->validate($post)) ? $post['facebook'] : '',
                ':logo' => (v::key('logo', v::stringType())->validate($post)) ? $post['logo'] : '',
                ':referral' => (v::key('referralCode', v::stringType())->validate($post)) ? $post['referralCode'] : '',
                ":created_user_id" => $userId,
                ":last_updated_by" => $userId
            );
            $venueId = VenueData::insertVenue($venue);
            if ($venueId) {
                if ($post['triviaDay'] != '' && $post['triviaTime'] != '') {
                    $venueScheduleId = VenueData::manageVenueTriviaShcedule(array(
                                ':trivia_day' => $post['triviaDay'],
                                ':trivia_time' => $post['triviaTime'],
                                ":created_user_id" => $userId,
                                ":last_updated_by" => $userId,
                                ':venue_id' => $venueId
                                    ), $venueId);
                }
            }
            return $venueId;
        } else {
            return true;
        }
    }

    static function addVenueGroupToUser($userId) {
        $groupId = GroupData::selectGroupIdBySlug('venue-admin');
        if (!$groupId) {
            return false;
        }
        return UserData::insertGroupAssignment(array(
                    ':auth_group_id' => $groupId,
                    ':user_id' => $userId,
                    ':created_user_id' => $userId
        ));
    }

    static function addVenueRole($userId, $venueId, $roleSlug) {
        $role = ($roleSlug === 'owner' || $roleSlug === 'manager' || $roleSlug === 'employee' || $roleSlug === 'guest');
        return VenueData::insertVenueRoleAssignment(array(
                    ':venue_id' => $venueId,
                    ':user_id' => $userId,
                    ':role' => $role
        ));
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
