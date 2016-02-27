<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . "/services/api.dbconn.php";

class SystemData {
    
    static function deleteExpiredAuthTokens() {
        return DBConn::delete("DELETE FROM as_tokens_auth WHERE expires < NOW();");
    }
    
    static function cleanLookupGroupRole() {
        return DBConn::delete("DELETE as_auth_lookup_group_role FROM as_auth_lookup_group_role "
                . "LEFT JOIN as_auth_groups AS g ON auth_group_id = g.id "
                . "LEFT JOIN as_auth_roles AS r ON auth_role_id = r.id "
                . "WHERE g.id IS NULL OR r.id IS NULL");
    }
    
    static function cleanLookupRoleField() {
        return DBConn::delete("DELETE as_auth_lookup_role_field FROM as_auth_lookup_role_field "
                . "LEFT JOIN as_auth_fields AS f ON auth_field_id = f.id "
                . "LEFT JOIN as_auth_roles AS r ON auth_role_id = r.id "
                . "WHERE f.id IS NULL OR r.id IS NULL");
    }
    
    static function cleanLookupUserGroup() {
        return DBConn::delete("DELETE as_auth_lookup_user_group FROM as_auth_lookup_user_group "
                . "LEFT JOIN as_auth_groups AS g ON auth_group_id = g.id "
                . "LEFT JOIN as_auth_users AS u ON user_id = u.id "
                . "WHERE g.id IS NULL OR u.id IS NULL");
    }
    
}
