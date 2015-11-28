<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 12:33 AM
 */

require_once "handlers/DbHandler.php";
include_once "functions.php";

class UserHandler{

    static public function login($username){
        require_once "handlers/SessionsHandler.php";

        $user_id = UserHandler::getUserId($username);

        if(!$user_id) {
            $user_id = static::createUser($username);
        }

        SessionsHandler::startSession();
        SessionsHandler::setSessionUser($user_id, $username);
    }


    static public function setUserActivity(){
        $conn = DbHandler::getConnection();

        $query = "UPDATE users SET last_activity = ".$conn->escapeString(getMicrotime())." WHERE id = ". $conn->escapeString(static::getCurrentUserId());
        $conn->exec($query);
    }

    static public function getCurrentUserName(){
        return $_SESSION["username"];
    }

    static public function getCurrentUserId(){
        return $_SESSION["user_id"];
    }

    // get users that have been active in the last 30 seconds
    static public function getActiveUsers(){
        $conn = DbHandler::getConnection();
        $query = "SELECT id, username FROM users ".
                    "WHERE id != ".$conn->escapeString(static::getCurrentUserId()).
                    " AND last_activity >= " . $conn->escapeString(getMicrotime() - 3000);

        $result = $conn->query($query);

        $data = array();
        if($result instanceof Sqlite3Result) {
            while ($row = $result->fetchArray()) {
                $data[] = array('id' => $row['id'], 'username' => $row['username']);
            }
        }

        return $data;
    }

    static public function getUsername($user_id){
        $conn = DbHandler::getConnection();
        $query = "SELECT username FROM users ".
            "WHERE id != ".$conn->escapeString($user_id);

        $username = $conn->querySingle($query);

        return $username;
    }

    /* ============================ protected ============================= */

    static protected function createUser($username){
        require_once "handlers/ChatHandler.php";
        $conn = DbHandler::getConnection();

        $query = "INSERT INTO users (username, last_activity) VALUES ( '".$conn->escapeString($username)."', ".$conn->escapeString(getMicrotime())." )";
        $conn->exec($query);

        $user_id = $conn->lastInsertRowID();

        // add user rel to public chat
        ChatHandler::addUserChatRel($user_id, ChatHandler::PUBLIC_CHAT_ID);

        return $user_id;
    }

    static protected function getUserId($username){
        $conn = DbHandler::getConnection();
        $query = "SELECT id FROM users WHERE username = '".$conn->escapeString($username)."'";
        $user_id = $conn->querySingle($query);

        return $user_id;
    }
}
