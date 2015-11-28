<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/26/2015
 * Time: 11:40 PM
 */

require_once "handlers/DbHandler.php";
include_once "functions.php";

class ChatHandler{

    const PUBLIC_CHAT_ID = 1;

    static function getPrivateChat($user_id_1, $user_id_2){

        $chat_id = static::getPrivateChatId($user_id_1, $user_id_2);

        if(!$chat_id) {
            $chat_id = static::createChat();

            static::addUserChatRel($user_id_1, $chat_id);
            static::addUserChatRel($user_id_2, $chat_id);
        }

        return $chat_id;
    }

    static public function insertMessage($user_id, $chat_id, $message){
        require_once "handlers/MessageHandler.php";

        if( MessageHandler::insertMessage($user_id, $chat_id, $message) ){
            static::updateChatActivity($user_id, $chat_id);
            return TRUE;
        }

        return FALSE;
    }

    static public function getLatestMessages($user_id, $chat_id, $from_beginning){
        require_once "handlers/MessageHandler.php";

        $data = MessageHandler::getLatestMessages($user_id, $chat_id, $from_beginning);

        static::updateChatActivity($user_id, $chat_id);

        return $data;
    }

    static public function addUserChatRel($user_id, $chat_id){
        $conn = DbHandler::getConnection();

        if(static::userChatRelExists($user_id, $chat_id)){
            return;
        }

        $query = "INSERT INTO user_chat_rel (chat_id, user_id, last_activity) VALUES (".$conn->escapeString($chat_id).", ".$conn->escapeString($user_id).",".$conn->escapeString(getMicrotime()).")";

        $conn->exec($query);

    }

    /* ============================ protected ============================= */


    static protected function createChat(){
        $conn = DbHandler::getConnection();

        $query = "INSERT INTO chats (last_activity) VALUES (".$conn->escapeString(getMicrotime()).")";
        $conn->exec($query);

        $chat_id = $conn->lastInsertRowID();

        return $chat_id;
    }

    static protected function getPrivateChatId($user_id_1, $user_id_2){
        $conn = DbHandler::getConnection();

        $query = "SELECT chat_id ".
            "FROM user_chat_rel ".
            "WHERE chat_id != ".$conn->escapeString(static::PUBLIC_CHAT_ID)." ".
            "AND user_id = ".$conn->escapeString($user_id_1)." ".
            "AND chat_id IN (SELECT chat_id FROM user_chat_rel WHERE user_id = ".$conn->escapeString($user_id_2).")";

        $chat_id = $conn->querySingle($query);

        return $chat_id;
    }

    static protected function chatExists($chat_id){
        $conn = DbHandler::getConnection();

        $query = "SELECT id FROM chats WHERE id = ".$conn->escapeString($chat_id);
        $id = $conn->querySingle($query);

        return (!is_null($id) ? TRUE : FALSE);
    }

    static protected function userChatRelExists($user_id, $chat_id){
        $conn = DbHandler::GetConnection();

        $query = "SELECT id FROM user_chat_rel WHERE chat_id = ".$conn->escapeString($chat_id)." ANd user_id = ".$conn->escapeString($user_id);
        $id = $conn->querySingle($query);

        return (!is_null($id) ? TRUE : FALSE);
    }

    static protected function updateChatActivity($user_id, $chat_id){
        $conn = DbHandler::getConnection();

        $query = "UPDATE user_chat_rel SET last_activity = ".$conn->escapeString(getMicrotime()). " ".
            "WHERE user_id = ".$conn->escapeString($user_id)." AND chat_id = ".$conn->escapeString($chat_id);

        $conn->exec($query);
    }

}