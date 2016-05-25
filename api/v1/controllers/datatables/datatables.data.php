<?php

namespace API;

require_once dirname(dirname(dirname(__FILE__))) . '/services/api.dbconn.php';

class DatatablesData {

    static function selectUsers() {
        $qUsers = DBConn::executeQuery("SELECT u.id, u.name_first AS nameFirst, u.name_last AS nameLast, "
                        . "u.email, u.email_verified AS verified, u.phone, u.created, u.last_updated AS lastUpdated, "
                        . "u.disabled, CONCAT(u1.name_first, ' ', u1.name_last) AS updatedBy, t.name AS team, t.id AS teamId "
                        . "FROM " . DBConn::prefix() . "users AS u "
                        . "LEFT JOIN " . DBConn::prefix() . "team_members AS tm ON tm.user_id = u.id "
                        . "LEFT JOIN " . DBConn::prefix() . "teams AS t ON t.id = tm.team_id "
                        . "LEFT JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = u.last_updated_by ORDER BY u.id;");

        $qGroups = DBConn::preparedQuery("SELECT grp.id, grp.group, grp.desc, look.created AS assigned "
                        . "FROM " . DBConn::prefix() . "auth_groups AS grp "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_user_group AS look ON grp.id = look.auth_group_id "
                        . "WHERE look.user_id = :id GROUP BY grp.id ORDER BY grp.group;");

        $users = Array();
        while ($user = $qUsers->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $user->id));
            $user->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);
            array_push($users, $user);
        }
        return $users;
    }

    static function selectUserGroups() {
        $qGroups = DBConn::executeQuery("SELECT g.id, g.group, g.slug AS identifier, g.desc, g.created, g.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "auth_groups AS g "
                        . "LEFT JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = g.created_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = g.last_updated_by ORDER BY g.group;");

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                        . "FROM " . DBConn::prefix() . "auth_roles AS r "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON r.id = look.auth_role_id "
                        . "WHERE look.auth_group_id = :id GROUP BY r.id ORDER BY r.role;");

        $groups = Array();

        while ($group = $qGroups->fetch(\PDO::FETCH_OBJ)) {
            $qRoles->execute(array(':id' => $group->id));
            $group->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);
            array_push($groups, $group);
        }

        return $groups;
    }

    static function selectGroupRoles() {
        $qRoles = DBConn::executeQuery("SELECT r.id, r.role, r.slug AS identifier, r.desc, r.created, r.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "auth_roles AS r "
                        . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = r.created_user_id "
                        . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = r.last_updated_by ORDER BY r.role;");

        $qFields = DBConn::preparedQuery("SELECT e.id, e.identifier, e.desc "
                        . "FROM " . DBConn::prefix() . "auth_fields AS e "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON e.id = look.auth_field_id "
                        . "WHERE look.auth_role_id = :id GROUP BY e.id ORDER BY e.identifier;");

        $qGroups = DBConn::preparedQuery("SELECT g.id, g.group, g.desc "
                        . "FROM " . DBConn::prefix() . "auth_groups AS g "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_group_role AS look ON g.id = look.auth_group_id "
                        . "WHERE look.auth_role_id = :id GROUP BY g.id ORDER BY g.group;");

        $roles = Array();

        while ($role = $qRoles->fetch(\PDO::FETCH_OBJ)) {
            $qGroups->execute(array(':id' => $role->id));
            $role->groups = $qGroups->fetchAll(\PDO::FETCH_OBJ);

            $qFields->execute(array(':id' => $role->id));
            $role->elements = $qFields->fetchAll(\PDO::FETCH_OBJ);

            array_push($roles, $role);
        }

        return $roles;
    }

    static function selectConfigVariables() {
        return DBConn::selectAll("SELECT c.id, c.name, c.value, c.created, c.last_updated AS lastUpdated, c.disabled, c.indestructible, c.locked, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, "
                        . "CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "system_config AS c "
                        . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = c.created_user_id "
                        . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = c.last_updated_by ORDER BY c.name;");
    }

    static function selectVisibilityFields() {
        $qFields = DBConn::executeQuery("SELECT e.id, e.identifier, e.type, e.desc, e.initialized, e.created, e.last_updated AS lastUpdated, "
                        . "CONCAT(u1.name_first, ' ', u1.name_last) AS createdBy, CONCAT(u2.name_first, ' ', u2.name_last) AS updatedBy "
                        . "FROM " . DBConn::prefix() . "auth_fields AS e "
                        . "JOIN " . DBConn::prefix() . "users AS u1 ON u1.id = e.created_user_id "
                        . "JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = e.last_updated_by ORDER BY e.identifier;");

        $qRoles = DBConn::preparedQuery("SELECT r.id, r.role, r.desc "
                        . "FROM " . DBConn::prefix() . "auth_roles AS r "
                        . "JOIN " . DBConn::prefix() . "auth_lookup_role_field AS look ON r.id = look.auth_role_id "
                        . "WHERE look.auth_field_id = :id GROUP BY r.id ORDER BY r.role;");

        $elements = Array();

        while ($field = $qFields->fetch(\PDO::FETCH_OBJ)) {
            $qRoles->execute(array(':id' => $field->id));
            $field->roles = $qRoles->fetchAll(\PDO::FETCH_OBJ);

            array_push($elements, $field);
        }

        return $elements;
    }

    // Admin Trivia

    static function selectCurrentGames() {
        $qGames = DBConn::executeQuery("SELECT g.id, g.name, g.scheduled, g.venue_id AS venueId, g.host_user_id AS hostId, "
                        . "g.game_started AS started, g.game_ended AS ended, g.max_points maxPoints, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS host, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "games AS g LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = g.host_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "WHERE g.game_started >= NOW() - INTERVAL 1 WEEK;");
        return self::selectGameScoreboard($qGames);
    }

    static function selectTriviaTeams() {
        $qTeams = DBConn::executeQuery("SELECT t.id, t.name, t.created, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS createdBy, "
                        . "t.home_venue_id AS homeVenueId, hv.name AS homeVenue, "
                        . "g.id AS lastGameId, g.name AS lastGameName "
                        . "FROM " . DBConn::prefix() . "teams AS t "
                        . "LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = t.created_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS hv ON hv.id = t.home_venue_id "
                        . "LEFT JOIN " . DBConn::prefix() . "game_score_teams AS gt ON gt.team_id = t.id "
                        . "LEFT JOIN " . DBConn::prefix() . "games AS g ON gt.game_id = g.id GROUP BY t.id ORDER BY g.game_ended, t.name;");

        $qPlayers = DBConn::preparedQuery("SELECT u.id, CONCAT(u.name_first, ' ', u.name_last) AS name, m.joined "
                        . "FROM " . DBConn::prefix() . "team_members AS m JOIN " . DBConn::prefix() . "users AS u "
                        . "ON m.user_id = u.id WHERE m.team_id = :team_id ORDER BY name;");

        $elements = Array();

        while ($team = $qTeams->fetch(\PDO::FETCH_OBJ)) {
            $qPlayers->execute(array(':team_id' => $team->id));
            $team->players = $qPlayers->fetchAll(\PDO::FETCH_OBJ);

            array_push($elements, $team);
        }

        return $elements;
    }

    static function selectTriviaVenues() {
        return DBConn::selectAll("SELECT v.id, v.name AS venue, v.address, v.address_b AS addressb, v.city, v.state, v.zip, "
                . "v.phone, v.phone_extension AS phoneExtension, v.website, v.facebook_url as facebook, "
                . "v.logo, v.referral AS referralCode, v.created, v.disabled, "
                . "CONCAT(u.name_first, ' ', u.name_last) AS contactUser, u.id AS contactUserId, "
                . "CONCAT(u2.name_first, ' ', u2.name_last) AS createdBy, u2.id AS createdById, "
                . "vs.trivia_day AS triviaDay, vs.trivia_time AS triviaTime "
                . "FROM " . DBConn::prefix() . "venues AS v "
                . "LEFT JOIN " . DBConn::prefix() . "venue_roles AS vr ON vr.venue_id = v.id "
                . "LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = vr.user_id "
                . "LEFT JOIN " . DBConn::prefix() . "users AS u2 ON u2.id = v.created_user_id "
                . "LEFT JOIN " . DBConn::prefix() . "venues_trivia_schedules AS vs ON vs.venue_id = v.id ORDER BY v.name;");
    }

    // Games    

    static function selectGames() {
        $qGames = DBConn::executeQuery("SELECT g.id, g.name, g.scheduled, g.venue_id AS venueId, g.host_user_id AS hostId, "
                        . "g.game_started AS started, g.game_ended AS ended, g.max_points maxPoints, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS host, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "games AS g LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = g.host_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "WHERE g.game_started IS NOT NULL;");
        return self::selectGameScoreboard($qGames);
    }

    static function selectHostGames($hostId) {
        $qGames = DBConn::executeQuery("SELECT g.id, g.name, g.scheduled, g.venue_id AS venueId, g.host_user_id AS hostId, "
                        . "g.game_started AS started, g.game_ended AS ended, g.max_points maxPoints, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS host, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "games AS g LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = g.host_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "WHERE g.host_user_id = :host_user_id;", array(':host_user_id' => $hostId));
        return self::selectGameScoreboard($qGames);
    }

    static function selectVenueGames($venueId) {
        $qGames = DBConn::executeQuery("SELECT g.id, g.name, g.scheduled, g.venue_id AS venueId, g.host_user_id AS hostId, "
                        . "g.game_started AS started, g.game_ended AS ended, g.max_points maxPoints, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS host, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "games AS g LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = g.host_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "WHERE g.venue_id = :venue_id;", array(':venue_id' => $venueId));
        return self::selectGameScoreboard($qGames);
    }

    static function selectTeamGames($teamId) {
        $qGames = DBConn::executeQuery("SELECT g.id, g.name, g.scheduled, g.venue_id AS venueId, g.host_user_id AS hostId, "
                        . "g.game_started AS started, g.game_ended AS ended, g.max_points maxPoints, "
                        . "CONCAT(u.name_first, ' ', u.name_last) AS host, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "games AS g LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = g.host_user_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "JOIN " . DBConn::prefix() . "game_score_teams AS t ON t.game_id = g.id "
                        . "WHERE t.team_id = :team_id;", array(':team_id' => $teamId));
        return self::selectGameScoreboard($qGames);
    }

    static function selectTeamGameCheckins($teamId) {
        return DBConn::selectAll("SELECT c.status, c.created, CONCAT(u.name_first, ' ', u.name_last) AS createdBy, "
                        . "g.id AS gameId, g.name AS game, t.id AS teamId, t.name AS team, "
                        . "v.id As venueId, v.name AS venue "
                        . "FROM " . DBConn::prefix() . "logs_game_checkins AS c LEFT "
                        . "JOIN " . DBConn::prefix() . "teams AS t ON t.id = c.team_id "
                        . "LEFT JOIN " . DBConn::prefix() . "users AS u ON u.id = c.created_by "
                        . "LEFT JOIN " . DBConn::prefix() . "games AS g ON g.id = c.game_id "
                        . "LEFT JOIN " . DBConn::prefix() . "venues AS v ON v.id = g.venue_id "
                        . "WHERE c.team_id = :team_id ORDER BY c.created DESC;", array(':team_id' => $teamId));
    }

    private static function selectGameScoreboard($qGames) {
        $qScores = DBConn::preparedQuery("SELECT s.team_id AS teamId, s.score AS gameScore, "
                        . "s.game_rank AS gameRank, s.game_winner AS gameWinner, t.name AS teamName "
                        . "FROM " . DBConn::prefix() . "game_score_teams AS s "
                        . "LEFT JOIN " . DBConn::prefix() . "teams AS t ON s.team_id = t.id "
                        . "WHERE s.game_id = :game_id ORDER BY s.game_rank");

        $elements = Array();

        while ($game = $qGames->fetch(\PDO::FETCH_OBJ)) {
            $qScores->execute(array(':game_id' => $game->id));
            $game->scoreboard = $qScores->fetchAll(\PDO::FETCH_OBJ);

            array_push($elements, $game);
        }

        return $elements;
    }

    static function selectGameSimpleScoreboard($gameId, $roundNumber) {
        return DBConn::selectAll("SELECT s.team_id AS teamId, t.name AS team, "
                . "IFNULL(g.score, 0) AS gameScore, IFNULL(g.game_rank, 0) AS gameRank, g.game_winner AS gameWinner, "
                . "IFNULL(s.score, 0) AS roundScore, IFNULL(s.round_rank, 0) AS roundRank "
                . "FROM " . DBConn::prefix() . "game_rounds AS r "
                . "LEFT JOIN " . DBConn::prefix() . "game_score_rounds AS s ON s.round_id = r.id "
                . "LEFT JOIN " . DBConn::prefix() . "teams AS t ON t.id = s.team_id "
                . "LEFT JOIN " . DBConn::prefix() . "game_score_teams AS g ON g.game_id = r.game_id AND g.team_id = s.team_id "
                . "WHERE r.game_id = :game_id AND r.order = :order "
                . "ORDER BY gameRank ASC;", array(':game_id' => $gameId, ':order' => $roundNumber));
    }

}
