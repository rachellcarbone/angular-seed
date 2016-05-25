<?php

namespace API;

require_once dirname(__FILE__) . '/auth.data.php';

use \Respect\Validation\Validator as v;

class AuthHooks {

    static function signup($app, $apiResponse) {
        self::hookCallHotSalsa($app, $apiResponse);
    }

    private static function hookCallHotSalsa($app, $apiResponse) {
        $vars = self::data_hookConfigVars('HOT_SALSA_');

        if (!isset($vars['HOT_SALSA_PLAYER_REGISTRATION_ENABLED']) || ($vars['HOT_SALSA_PLAYER_REGISTRATION_ENABLED'] !== 'true' && $vars['HOT_SALSA_PLAYER_REGISTRATION_ENABLED'] !== '1')) {
            return;
        }

        if (!isset($vars['HOT_SALSA_PLAYER_REGISTRATION_URL']) ||
                !isset($vars['HOT_SALSA_APP_VERSION']) ||
                !isset($vars['HOT_SALSA_URL_CODE']) ||
                !isset($vars['HOT_SALSA_AUTH_KEY']) ||
                !isset($vars['HOT_SALSA_OS']) ||
                !isset($vars['HOT_SALSA_PACKAGE_CODE'])) {

            self::data_logHotSalsaError($apiResponse['user']->id, "Could not attempt call. The Hot Salsa signup hook is enabled but a system variable is disabled or missing.", $vars);
            return;
        }

        // Get Post Data
        $post = $app->request->post();
        $params = array(
            'email' => $post['email'],
            'firstName' => $post['nameFirst'],
            'lastName' => $post['nameLast'],
            'appVersion' => $vars['HOT_SALSA_APP_VERSION'],
            'code' => $vars['HOT_SALSA_URL_CODE'],
            'authKey' => $vars['HOT_SALSA_AUTH_KEY'],
            'os' => $vars['HOT_SALSA_OS'],
            'packageCode' => $vars['HOT_SALSA_PACKAGE_CODE']
        );
        // If it was standard signup
        if (isset($post['password'])) {
            $params['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }
        // If it was facebook signup
        if (isset($post['facebookId'])) {
            $params['facebookId'] = $post['facebookId'];
        }

        // create curl resource 
        $ch = curl_init();

        // set url 
        curl_setopt($ch, CURLOPT_URL, $vars['HOT_SALSA_PLAYER_REGISTRATION_URL']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string 
        $curlOutput = curl_exec($ch);

        if (!$curlOutput) {
            // No Results = Error
            $error = (curl_error($ch)) ? curl_error($ch) : 'ERROR: No results';
            $info = (curl_getinfo($ch)) ? json_encode(curl_getinfo($ch)) : 'ERROR: No Info';
            self::data_logHotSalsaError($apiResponse['user']->id, $error, $info);
        } else {
            // Results
            $curlResult = json_decode($curlOutput, true);
            if (!isset($curlResult['status']) || $curlResult['status'] === 'failed') {
                $error = (isset($curlResult['status'])) ? $curlResult['status'] : 'ERROR: Unknown error occured';
                self::data_logHotSalsaError($apiResponse['user']->id, $error, $curlOutput);
            } else {
                self::data_logHotSalsaResults($curlResult, $app, $apiResponse);
            }
        }

        // close curl resource to free up system resources 
        curl_close($ch);
    }

    private static function data_hookConfigVars($prefix) {
        $varsQuery = DBConn::executeQuery("SELECT name, `value` FROM " . DBConn::prefix() . "system_config "
                        . "WHERE name LIKE '{$prefix}%' AND disabled= 0;");

        $vars = Array();
        while ($var = $varsQuery->fetch(\PDO::FETCH_OBJ)) {
            $vars[$var->name] = $var->value;
        }
        return $vars;
    }

    private static function data_logHotSalsaResults($curlResult, $app, $apiResponse) {
        $logData = array(
            ':user_id' => $apiResponse['user']->id,
            ':salsa_call_status' => isset($curlResult['status']) ? $curlResult['status'] : 'No response',
            ':salsa_user_id' => (isset($curlResult['userData']) && isset($curlResult['userData']['userId'])) ? $curlResult['userData']['userId'] : NULL,
            ':salsa_user_data' => (isset($curlResult['userData'])) ? json_encode($curlResult['userData']) : NULL,
            ':salsa_error_message' => (isset($curlResult['errorMessage'])) ? $curlResult['errorMessage'] : NULL
        );
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "logs_hot_salsa_signup(user_id, salsa_call_status, salsa_user_id, salsa_user_data, salsa_error_message) "
                        . "VALUES (:user_id, :salsa_call_status, :salsa_user_id, :salsa_user_data, :salsa_error_message);", $logData);
    }

    private static function data_logHotSalsaError($userId, $errorMessage, $data) {
        $logData = array(
            ':user_id' => $userId,
            ':salsa_call_status' => $errorMessage,
            ':salsa_error_message' => json_encode($data)
        );

        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "logs_hot_salsa_signup(user_id, salsa_call_status, salsa_error_message) "
                        . "VALUES (:user_id, :salsa_call_status, :salsa_error_message);", $logData);
    }


    /* Custom */

    static function venue_signup($app, $apiResponse, $editFlag = null) {
        self::hookCallHotSalsaVenueRegister($app, $apiResponse, $editFlag);
    }

    private static function hookCallHotSalsaVenueRegister($app, $apiResponse, $editFlag) {
        $vars = self::data_hookConfigVars('HOT_SALSA_');

        if (!isset($vars['HOT_SALSA_VENUE_REGISTRATION_ENABLED']) || ($vars['HOT_SALSA_VENUE_REGISTRATION_ENABLED'] !== 'true' && $vars['HOT_SALSA_VENUE_REGISTRATION_ENABLED'] !== '1')) {
            return;
        }
        $url_var = $vars['HOT_SALSA_VENUE_REGISTRATION_URL'];
        if (!isset($url_var) ||
                !isset($vars['HOT_SALSA_APP_VERSION']) ||
                !isset($vars['HOT_SALSA_URL_CODE']) ||
                !isset($vars['HOT_SALSA_AUTH_KEY']) ||
                !isset($vars['HOT_SALSA_OS']) ||
                !isset($vars['HOT_SALSA_PACKAGE_CODE'])) {

            self::data_logHotSalsaVenueError($apiResponse['venue']->id, "Could not attempt call. The Hot Salsa signup hook is enabled but a system variable is disabled or missing.", $vars);
            return;
        }

        // Get Post Data
        $post = $app->request->post();
        $params = array(
            'name' => $post['venue'],
            'email' => (v::key('email', v::email())->validate($post)) ? $post['email'] : '',
            'firstName' => (v::key('nameFirst', v::stringType())->validate($post)) ? $post['nameFirst'] : '',
            'lastName' => (v::key('nameLast', v::stringType())->validate($post)) ? $post['nameLast'] : '',
            'password' => (v::key('password', v::stringType())->validate($post)) ? $post['password'] : '',
            'phoneNumber' => ((v::key('phone_extension', v::stringType())->validate($post)) ? $post['phone_extension'] : '') . ((v::key('phone', v::stringType())->validate($post)) ? $post['phone'] : ''),
            'address1' => $post['address'],
            'address2' => (v::key('addressb', v::stringType())->validate($post)) ? $post['addressb'] : '',
            'city' => $post['city'],
            'state' => $post['state'],
            'postalCode' => $post['zip'],
            'country' => 'US',
            'triviaDay' => $post['triviaDay'],
            'triviaTime' => $post['triviaTime'],
            'appVersion' => $vars['HOT_SALSA_APP_VERSION'],
            'code' => $vars['HOT_SALSA_URL_CODE'],
            'authKey' => $vars['HOT_SALSA_AUTH_KEY'],
            'os' => $vars['HOT_SALSA_OS'],
            'packageCode' => $vars['HOT_SALSA_PACKAGE_CODE'],
        );
        if ($editFlag == true) {
            $salsa_location_details = DBConn::selectOne("SELECT  salsa_location_id "
                            . "FROM " . DBConn::prefix() . "venues WHERE id = :id ORDER BY id Desc LIMIT 1;", array(':id' => $apiResponse['venue']->id));

            if (!empty($salsa_location_details) && $salsa_location_details->salsa_location_id > 0) {
                $params['locationId'] = $salsa_location_details->salsa_location_id;
            }
        }

        // If it was standard signup
        if (isset($post['password'])) {
            $params['password'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }
        // If it was facebook signup
        if (isset($post['facebookId'])) {
            $params['facebookId'] = $post['facebookId'];
        }

        // create curl resource 
        $ch = curl_init();

        // set url 
        curl_setopt($ch, CURLOPT_URL, $url_var);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        /* curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); */

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string 
        $curlOutput = curl_exec($ch);

        if (!$curlOutput) {
            // No Results = Error
            $error = (curl_error($ch)) ? curl_error($ch) : 'ERROR: No results';
            $info = (curl_getinfo($ch)) ? json_encode(curl_getinfo($ch)) : 'ERROR: No Info';
            self::data_logHotSalsaVenueError($apiResponse['venue']->id, $error, $info);
        } else {
            // Results
            $curlResult = json_decode($curlOutput, true);
            if (!isset($curlResult['status']) || $curlResult['status'] === 'failed') {
                $error = (isset($curlResult['status'])) ? $curlResult['status'] : 'ERROR: Unknown error occured';
                self::data_logHotSalsaVenueError($apiResponse['venue']->id, $error, $curlOutput);
            } else {
                self::data_logHotSalsaVenueResults($curlResult, $app, $apiResponse);
            }
        }

        // close curl resource to free up system resources 
        curl_close($ch);
    }

    private static function data_logHotSalsaVenueResults($curlResult, $app, $apiResponse) {
        $logData = array(
            ':venue_id' => $apiResponse['venue']->id,
            ':salsa_call_status' => isset($curlResult['status']) ? $curlResult['status'] : 'No response',
            ':salsa_location_id' => (isset($curlResult['locationId'])) ? $curlResult['locationId'] : NULL,
            ':salsa_location_data' => (isset($curlResult['locationData'])) ? json_encode($curlResult['locationData']) : NULL,
            ':salsa_error_message' => (isset($curlResult['errorMessage'])) ? $curlResult['errorMessage'] : NULL
        );
        if (isset($curlResult['locationId']) && $curlResult['locationId'] > 0) {
            $venuedata = array(':salsa_location_id' => $curlResult['locationId'], ":id" => $apiResponse['venue']->id);


            DBConn::update("UPDATE " . DBConn::prefix() . "venues SET salsa_location_id=:salsa_location_id"
                    . " WHERE id=:id;", $venuedata);
        }
        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "log_hot_salsa_venue_signup(venue_id, salsa_call_status, salsa_location_id, salsa_location_data, salsa_error_message) "
                        . "VALUES (:venue_id, :salsa_call_status, :salsa_location_id, :salsa_location_data, :salsa_error_message);", $logData);
    }

    private static function data_logHotSalsaVenueError($venueId, $errorMessage, $data) {
        $logData = array(
            ':venue_id' => $venueId,
            ':salsa_call_status' => $errorMessage,
            ':salsa_error_message' => json_encode($data)
        );

        return DBConn::insert("INSERT INTO " . DBConn::prefix() . "log_hot_salsa_venue_signup(venue_id, salsa_call_status, salsa_error_message) "
                        . "VALUES (:venue_id, :salsa_call_status, :salsa_error_message);", $logData);
    }

    /* Custom */
}
