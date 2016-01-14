<?php namespace API;
 require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class UserData {
    
    public static function selectUsers() {
        $qUsers = DBConn::preparedQuery("SELECT id, name_first, name_last, email, email_verified, password, created, last_updated "
                . "FROM " . DBConn::prefix() . "users;");
        $qUsers->execute();
        
        $qGroups = DBConn::preparedQuery("SELECT grp.id, grp.group, grp.desc "
                . "FROM " . DBConn::prefix() . "auth_groups AS grp "
                . "JOIN " . DBConn::prefix() . "auth_lookup_user_group AS look ON grp.id = look.auth_group_id "
                . "WHERE look.user_id = :id ORDER BY grp.group;");

        $users = Array();
        while($user = $qUsers->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $user->id));
            $user->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);        
            array_push($users, $user);
        }
        return $users;
    }
    
    public static function selectOtherUsersWithEmail($email, $id = 0) {
        return DBConn::select("SELECT id FROM " . DBConn::prefix() . "users WHERE email = :email AND id != :id;", 
                    array(':email' => $email, ':id' => $id), \PDO::FETCH_COLUMN);
    }
    
    public static function selectUserById($id) {
        $user = DBConn::selectOne("SELECT id, name_first as nameFirst, name_last as nameLast, email "
                . "FROM " . DBConn::prefix() . "users WHERE id = :id LIMIT 1;", array('id' => $id));
        if($user) {
            $user['displayName'] = $user['nameFirst'];
            $user['roles'] = DBConn::select("SELECT gr.auth_role_id FROM " . DBConn::prefix() . "auth_lookup_user_group AS ug "
                    . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS gr ON ug.auth_group_id = gr.auth_group_id "
                    . "WHERE ug.user_id = :id;", array('id' => $id), \PDO::FETCH_COLUMN);
        }
        return $user;
    }
  
    public static function insertUser($validUser) {
        return DBConn::insertQuery("INSERT INTO " . DBConn::prefix() . "users(name_first, name_last, email, password) "
                . "VALUES (:name_first, :name_last, :email, :password);", $validUser);
    }
    
    public static function updateUser($validUser) {
        return DBConn::preparedQuery("UPDATE " . DBConn::prefix() . "users SET name_first=:name_first, name_last=:name_last, email=:email WHERE id = :id;", $validUser);
    }
    
    public static function deleteUser($id) {
        return DBConn::preparedQuery("DELETE FROM " . DBConn::prefix() . "users WHERE id = :id LIMIT 1;", array('id' => $id));
    }
}
