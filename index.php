<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/26/2015
 * Time: 11:39 PM
 */

require_once "handlers/SessionsHandler.php";
SessionsHandler::startSession();
require_once "header.php";

SessionsHandler::redirect("chatroom.php");

require_once "footer.php";
