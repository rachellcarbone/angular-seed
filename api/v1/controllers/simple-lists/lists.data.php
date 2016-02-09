<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class ListsData {
    
    static function selectUsers() {
        return DBConn::selectAll("SELECT u.id, CONCAT(u.name_last, ', ', u.name_first, '   (ID: ', u.id, ')') AS label "
                . "FROM " . DBConn::prefix() . "users AS u ORDER BY u.name_last;");
    }
    
    static function selectGroups() {
        return DBConn::selectAll("SELECT g.id, g.group AS label FROM " . DBConn::prefix() . "auth_groups AS g ORDER BY g.group;");
    }
    
    static function selectRoles() {
        return DBConn::selectAll("SELECT r.id, r.role AS label FROM " . DBConn::prefix() . "auth_roles AS r ORDER BY r.role;");
    }
    
    static function selectVisibilityFields() {
        return DBConn::selectAll("SELECT f.id, CONCAT('(', f.type, ') ', f.identifier) AS label "
                . "FROM " . DBConn::prefix() . "auth_fields AS f ORDER BY label;");
    }
    
}
