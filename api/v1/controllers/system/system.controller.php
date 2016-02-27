<?php namespace API;
require_once dirname(__FILE__) . '/system.data.php';

use \Respect\Validation\Validator as v;

class SystemController {
    
    static function cronJob($app) {
        $data = array();
        $data['deleteExpiredAuthTokens'] = SystemData::deleteExpiredAuthTokens();
        $data['cleanLookupGroupRole'] = SystemData::cleanLookupGroupRole();
        $data['cleanLookupRoleField'] = SystemData::cleanLookupRoleField();
        $data['cleanLookupRoleField'] = SystemData::cleanLookupRoleField();
        return $app->render(200, array('msg' => "Cron complete.", 'results' => $data));
    }
    
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
