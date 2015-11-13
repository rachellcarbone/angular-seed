<?php namespace API\Data;
require_once 'api.config.php';

/* 
 * Config and PDO Connection Tester
 * 
 * Import the api config and use it to connect to the database via PDO.
 * Disply success or fail with data messages.
 */

$config = new APIConfig();
$c = $config->get();

echo "<pre><code>";

try {
    
    $pdo = new \PDO("mysql:host={$c['dbHost']};dbname={$c['db']}", $c["dbUser"], $c["dbPass"]);
    $pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    
    echo ("Success! <br/> <br/>");
    
} catch (\PDOException $e) {
    
    echo ("Fail! <br/> <br/>");
    echo ("Config:  <br/> <br/>");
    
    print_r($c);
    
    echo ("Exception: <br/> <br/>");
    
    print_r($e);
}