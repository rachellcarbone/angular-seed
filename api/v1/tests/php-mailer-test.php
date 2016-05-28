<?php namespace API;

require_once dirname(dirname(__FILE__)) . "/vendor/autoload.php";   // Composer components
require_once dirname(dirname(__FILE__)) . "/services/api.mailer.php";   // Mailer Service


date_default_timezone_set('America/New_York');
$now = date('l jS \of F Y h:i:s A');

print_r(\API\ApiMailer::sendSystemTest("Timestamp: {$now}", "test-email@gmail.com", "RC AngularSeed Email-Test"));
