<?php namespace API;
require_once dirname(__FILE__) . '/system.data.php';

use \Respect\Validation\Validator as v;

class SystemController {
    
    ///// 
    ///// Authentication
    ///// 
    
    static function deleteExpiredAuthTokens($app) {
        $rows = SystemData::deleteExpiredAuthTokens();
        return $app->render(200, array('msg' => "Deleted expired auth tokens. {$rows} rows effected." ));
    }
    
    static function deleteBrokenLookupEntries($app) {
        $r1 = SystemData::cleanLookupGroupRole();
        $r2 = SystemData::cleanLookupRoleField();
        $r3 = SystemData::cleanLookupRoleField();
        $rows = $r1 + $r2 + $r3;
        return $app->render(200, array('msg' => "Deleted invalid auth lookup entries. {$rows} rows effected."));
    }
    
}
