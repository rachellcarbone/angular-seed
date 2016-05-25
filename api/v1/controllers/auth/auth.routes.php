<?php namespace API;
 require_once dirname(__FILE__) . '/auth.controller.php';
 require_once dirname(__FILE__) . '/auth.additionalInfo.controller.php';

class AuthRoutes {
    
    static function addRoutes($app, $authenticateForRole) {
        
        ///// 
        // System Admin
        // TODO: Create system functions route
        ///// 
        
        $app->map("/admin/auth/delete/expired-tokens/", $authenticateForRole('admin'), function () use ($app) {
            AuthController::deleteExpiredAuthTokens($app);
        })->via(['DELETE', 'POST']);
        
        /**
         * @api {post} /user/update/password Manage user password.
         * @apiName ChangeUserPassword
         * @apiGroup Auth
         *
         * @apiParam {String} apiKey User session key.
         * @apiParam {String} apiToken User session unhashed token.
         * 
         * @apiParam {Integer} userId optional but Required if the User Email was not provided. Used to select the user.
         * @apiParam {String} email optional but Required if the User ID was not provided. Used to select the user.
         * @apiParam {String} current Current user password for user authentication.
         * @apiParam {String} new New password for the user to change it too.
         *
         * @apiSuccessExample {json} Success-Response:
         *      HTTP/1.1 200: OK
         *      {
         *          "data": {
         *              "msg": "Password successfully changed."
         *          },
         *          "meta": {
         *              "error": false,
         *              "status": 200
         *          }
         *      }
         * 
         * @apiErrorExample {json} Error-Missing-Parameters:
         *      HTTP/1.1 400: Bad Request
         *      {
         *          "data": {
         *              "msg": "Password could not be changed. Check your parameters and try again."
         *          },
         *          "meta": {
         *              "error": true,
         *              "status": 400
         *          }
         *      }
         * 
         * @apiErrorExample {json} Error-Invalid-New-Password:
         *      HTTP/1.1 400: Bad Request
         *      {
         *          "data": {
         *              "msg": "Invalid Password. Check your parameters and try again."
         *          },
         *          "meta": {
         *              "error": true,
         *              "status": 400
         *          }
         *      }
         * 
         * @apiErrorExample {json} Error-User-Id-Not-Found:
         *      HTTP/1.1 400: Bad Request
         *      {
         *          "data": {
         *              "msg": "User not found. Check your parameters and try again."
         *          },
         *          "meta": {
         *              "error": true,
         *              "status": 400
         *          }
         *      }
         * 
         * @apiErrorExample {json} Error-Unauthorized:
         *      HTTP/1.1 401: Unauthorized
         *      {
         *          "data": {
         *              "msg": "Invalid user password. Unable to verify request."
         *          },
         *          "meta": {
         *              "error": true,
         *              "status": 401
         *          }
         *      }
         * 
         * @apiErrorExample {json} Error-Unknown-DB-Update:
         *      HTTP/1.1 400: Bad Request
         *      {
         *          "data": {
         *              "msg": "Password could not be changed. Try again later."
         *          },
         *          "meta": {
         *              "error": true,
         *              "status": 400
         *          }
         *      }
         */
        $app->post("/user/update/password/", $authenticateForRole('member'), function () use ($app) {
            AuthController::changeUserPassword($app);
        });
        
        
        //* /auth/ routes - publicly accessable        
            
        $app->group('/auth', $authenticateForRole('public'), function () use ($app) {
			
            
            /**
             * @api {post} /auth/authenticate Confirm api key and token pair represents an active user login session.
             * @apiName Authenticate
             * @apiGroup Auth
             *
             * @apiParam {String} apiKey User session key.
             * @apiParam {String} apiToken User session unhashed token.
             *
             * @apiSuccessExample {json} Success-Response:
             *      HTTP/1.1 200: OK
             *      {
             *          "data": {
             *              "authenticated": true,
             *              "sessionLifeHours": 1
             *              "user": {
             *                  "id": "28",
             *                  "nameFirst": "Rachel",
             *                  "nameLast": "Testing",
             *                  "email": "racheltest@testing.com",
             *                  "displayName": "Rachel",
             *                  "roles": ['3'],
             *                  "apiKey": "caf02551768a09e1aed8946ecacce3b01f253884a08bded1f1a76520b8f0c4e847914a1daea072ab957582a2c32beceacd62b5e6842f18ef2b21a3f13b16c374",
             *                  "apiToken": "c88e7640de8f34c18d7d07d6d0a26b0d9896f188766e445bac32a44cb275ba89"
             *              }
             *          },
             *          "meta": {
             *              "error": false,
             *              "status": 200
             *          }
             *      }
             * 
             * 
             * @apiErrorExample {json} Error-Missing-Parameters:
             *      HTTP/1.1 401: Unauthorized
             *      {
             *          "data": {
             *              "authenticated": false,
             *              "msg": "Unauthenticated: Invalid request. Check your parameters and try again."
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 401
             *          }
             *      }
             * 
             * 
             * @apiErrorExample {json} Error-Incorrect-Values:
             *      HTTP/1.1 401: Unauthorized
             *      {
             *          "data": {
             *              "authenticated": false,
             *              "msg": "Unauthenticated: No User"
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 401
             *          }
             *      }
             */
            $app->post("/authenticate/", function () use ($app) {
                AuthController::isAuthenticated($app);
            });
            
            
            /**
             * @api {post} /auth/signup Standard user signup.
             * @apiName Signup
             * @apiGroup Auth
             *
             * @apiParam {String} email User email address.
             * @apiParam {String} passowrd User unencrypted password.
             * @apiParam {String} nameFirst User first name.
             * @apiParam {String} nameLast User last name.
             * @apiParam {Integer} teamId optional Team to add the new player too.
             *
             * @apiSuccessExample {json} Success-Response:
             *      HTTP/1.1 200: OK
             *      {
             *          "data": {
             *              "registered": true,
             *              "sessionLifeHours": 1
             *              "user": {
             *                  "id": "28",
             *                  "nameFirst": "Rachel",
             *                  "nameLast": "Testing",
             *                  "email": "racheltest@testing.com",
             *                  "displayName": "Rachel",
             *                  "roles": ['3'],
             *                  "apiKey": "caf02551768a09e1aed8946ecacce3b01f253884a08bded1f1a76520b8f0c4e847914a1daea072ab957582a2c32beceacd62b5e6842f18ef2b21a3f13b16c374",
             *                  "apiToken": "c88e7640de8f34c18d7d07d6d0a26b0d9896f188766e445bac32a44cb275ba89"
             *              }
             *          },
             *          "meta": {
             *              "error": false,
             *              "status": 200
             *          }
             *      }
             * 
             * @apiErrorExample {json} Error-Missing-Parameters:
             *      HTTP/1.1 400: Bad Request
             *      {
             *          "data": {
             *              "registered": false,
             *              "msg": "Signup failed. Check your parameters and try again."
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 400
             *          }
             *      }
             * 
             * @apiErrorExample {json} Error-Duplicate-Email:
             *      HTTP/1.1 400: Bad Request
             *      {
             *          "data": {
             *              "registered": false,
             *              "msg": "Signup failed. A user with that email already exists."
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 400
             *          }
             *      }
             */
            $app->post("/signup/", function () use ($app) {
                AuthController::signup($app);
            });
            
            /* email, nameFirst, nameLast, facebookId, accessToken */
            
            $app->post("/signup/facebook/", function () use ($app) {
                AuthController::facebookSignup($app);
            });
            
            /* email, nameFirst, nameLast, password, venue, address, city, state, zip */
            /* OPTIONAL: addressb, phone, website, facebook, logo, hours, referralCode */
                    
            $app->post("/venue/signup/", function () use ($app) {
                AuthController::venueSignup($app);
            });

            /* email, nameFirst, nameLast, facebookId, accessToken, venue, address, city, state, zip */
            /* OPTIONAL: addressb, phone, website, facebook, logo, hours, referralCode */
                    
            $app->post("/venue/signup/facebook/", function () use ($app) {
                AuthController::venueFacebookSignup($app);
            });

            $app->post("/signup/additional/", function () use ($app) {
                InfoController::saveAdditional($app);
            });
            
            /**
             * @api {post} /auth/login Standard user login.
             * @apiName Login
             * @apiGroup Auth
             *
             * @apiParam {String} email User email address.
             * @apiParam {String} passowrd User unencrypted password.
             *
             * @apiSuccessExample {json} Success-Response:
             *      HTTP/1.1 200: OK
             *      {
             *          "data": {
             *              "authenticated": true,
             *              "sessionLifeHours": 1
             *              "user": {
             *                  "id": "28",
             *                  "nameFirst": "Rachel",
             *                  "nameLast": "Testing",
             *                  "email": "racheltest@testing.com",
             *                  "displayName": "Rachel",
             *                  "roles": ['3'],
             *                  "apiKey": "caf02551768a09e1aed8946ecacce3b01f253884a08bded1f1a76520b8f0c4e847914a1daea072ab957582a2c32beceacd62b5e6842f18ef2b21a3f13b16c374",
             *                  "apiToken": "c88e7640de8f34c18d7d07d6d0a26b0d9896f188766e445bac32a44cb275ba89"
             *              }
             *          },
             *          "meta": {
             *              "error": false,
             *              "status": 200
             *          }
             *      }
             * 
             * @apiErrorExample {json} Error-Missing-Parameters:
             *      HTTP/1.1 401: Unauthorized
             *      {
             *          "data": {
             *              "authenticated": false,
             *              "msg": "Login failed. Check your parameters and try again."
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 401
             *          }
             *      }
             * 
             * @apiErrorExample {json} Error-Unregistered-Email:
             *      HTTP/1.1 401: Unauthorized
             *      {
             *          "data": {
             *              "authenticated": false,
             *              "msg": "Unauthenticated: No User"
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 401
             *          }
             *      }
             * 
             * @apiErrorExample {json} Error-Incorrect-Password:
             *      HTTP/1.1 401: Unauthorized
             *      {
             *          "data": {
             *              "authenticated": false,
             *              "maxattempts": 6,
             *              "msg": "Login failed. Username and password combination did not match."
             *          },
             *          "meta": {
             *              "error": true,
             *              "status": 401
             *          }
             *      }
             */
			
            $app->post("/login/", function () use ($app) {
                AuthController::login($app);
            });
			
			 $app->post("/forgotpassword/", function () use ($app) {
				AuthController::forgotpassword($app);
            });
			 $app->post("/getforgotpasswordemail/", function () use ($app) {
				AuthController::getforgotpasswordemail($app);
            });
			 $app->post("/resetpassword/", function () use ($app) {
				AuthController::resetpassword($app);
            });
            
            /* email, nameFirst, nameLast, facebookId, accessToken */
            $app->post("/login/facebook/", function () use ($app) {
                AuthController::facebookLogin($app);
            });
            
            ///// 
            ///// Logout
            ///// 

            $app->post("/logout/", function () use ($app) {
                AuthController::logout($app);
            });
            
        });
        
    }
}