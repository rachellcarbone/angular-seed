<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . "/services/api.dbconn.php";

class SystemData {
    
    static function deleteExpiredAuthTokens() {
        return DBConn::delete("DELETE FROM " . DBConn::prefix() . "tokens_auth WHERE expires < NOW();");
    }
    
    static function cleanLookupGroupRole() {
        return DBConn::delete("DELETE " . DBConn::prefix() . "auth_lookup_group_role FROM " . DBConn::prefix() . "auth_lookup_group_role "
                . "LEFT JOIN " . DBConn::prefix() . "auth_groups AS g ON auth_group_id = g.id "
                . "LEFT JOIN " . DBConn::prefix() . "auth_roles AS r ON auth_role_id = r.id "
                . "WHERE g.id IS NULL OR r.id IS NULL");
    }
    
    static function cleanLookupRoleField() {
        return DBConn::delete("DELETE " . DBConn::prefix() . "auth_lookup_role_field FROM " . DBConn::prefix() . "auth_lookup_role_field "
                . "LEFT JOIN " . DBConn::prefix() . "auth_fields AS f ON auth_field_id = f.id "
                . "LEFT JOIN " . DBConn::prefix() . "auth_roles AS r ON auth_role_id = r.id "
                . "WHERE f.id IS NULL OR r.id IS NULL");
    }
    
    static function cleanLookupUserGroup() {
        return DBConn::delete("DELETE " . DBConn::prefix() . "auth_lookup_user_group FROM " . DBConn::prefix() . "auth_lookup_user_group "
                . "LEFT JOIN " . DBConn::prefix() . "auth_groups AS g ON auth_group_id = g.id "
                . "LEFT JOIN " . DBConn::prefix() . "auth_users AS u ON user_id = u.id "
                . "WHERE g.id IS NULL OR u.id IS NULL");
    }
    
}
