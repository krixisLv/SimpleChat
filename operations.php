<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 1:16 AM
 */

require_once "handlers/SessionsHandler.php";
SessionsHandler::startSession();
SessionsHandler::checkAccess("operations.php");

require_once "handlers/UserHandler.php";
require_once "handlers/ChatHandler.php";

$action = $_GET["action"];

if($action == "get_active_users"){
    // get users that have been active in the last 30 seconds
    $data = UserHandler::getActiveUsers();
}
else if($action == "logout"){
    SessionsHandler::destroySession();
    SessionsHandler::redirect("login.php");
}
else if($action == "post"){
    if(isset($_GET["chat_id"]) && isset($_GET["msg"])) {
        $chat_id = $_GET["chat_id"];
        $message = $_GET["msg"];
        $data = ChatHandler::insertMessage(UserHandler::getCurrentUserId(), $chat_id, $message);
    }
}
else if($action == "get"){
    if(isset($_GET["chat_id"]) && isset($_GET["reload"])) {
        $chat_id = $_GET["chat_id"];
        $reload = $_GET["reload"];
        $data = ChatHandler::getLatestMessages(UserHandler::getCurrentUserId(), $chat_id, $reload);
    }
}
else if($action == "change"){
    if(isset($_GET["partner_id"])) {
        $partner_id = $_GET["partner_id"];
        if ($partner_id != ChatHandler::PUBLIC_CHAT_ID) {
            $data = ChatHandler::getPrivateChat(UserHandler::getCurrentUserId(), $partner_id);
        } else {
            $data = ChatHandler::PUBLIC_CHAT_ID;
        }
    }

}

echo (isset($data) ? json_encode($data) : "");
