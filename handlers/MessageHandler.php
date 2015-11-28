<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/28/2015
 * Time: 8:57 AM
 */

require_once "handlers/DbHandler.php";
include_once "functions.php";

class MessageHandler {

    static public function getLatestMessages($user_id, $chat_id, $from_beginning){
        require_once "handlers/UserHandler.php";
        $conn = DbHandler::getConnection();

        $query = "SELECT (SELECT users.username FROM users WHERE id = user_id) AS username, message FROM messages WHERE chat_id = ". $conn->escapeString($chat_id);

        if(!$from_beginning){
            $query .= " AND insert_time >= (SELECT last_activity FROM user_chat_rel WHERE user_id = ".$conn->escapeString($user_id)." AND chat_id = ".$conn->escapeString($chat_id).") ORDER BY id ASC";
        }

        $result = $conn->query($query);

        UserHandler::setUserActivity();
        $data = array();
        if($result instanceof Sqlite3Result) {
            while ($row = $result->fetchArray()) {
                $data[] = array('msg' => $row['message'], 'username' => $row['username']);
            }
        }

        return $data;
    }

    static public function insertMessage($user_id, $chat_id, $message){
        $conn = DbHandler::getConnection();

        $query = "INSERT INTO messages (chat_id, user_id, message, insert_time) ".
                    "VALUES (".$conn->escapeString($chat_id).", ".$conn->escapeString($user_id).", '".$conn->escapeString($message)."', ".$conn->escapeString(getMicrotime()).")";

        $conn->exec($query);

        return true;
    }
}