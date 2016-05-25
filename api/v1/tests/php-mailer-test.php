<?php namespace API;

require_once dirname(dirname(__FILE__)) . "/vendor/autoload.php";   // Composer components
require_once dirname(dirname(__FILE__)) . "/services/api.mailer.php";   // Mailer Service


date_default_timezone_set('America/New_York');
$now = date('l jS \of F Y h:i:s A');

//Set who the message is to be sent to
print_r(\API\ApiMailer::sendSystemTest("Timestamp: {$now}", "test@gmail.com", "AngularSeed Email-Test"));