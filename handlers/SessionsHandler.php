<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 12:26 AM
 */

class SessionsHandler {

    static public function isSessionActive(){
        return isset($_SESSION['user_id']) ? TRUE : FALSE;
    }

    static public function startSession(){
        session_start();
    }

    static public function setSessionUser($user_id, $username){
        $_SESSION["user_id"] = $user_id;
        $_SESSION["username"] = $username;
    }

    static public function destroySession(){
        session_destroy();
    }

    static public function redirect($page){
        if(static::isSessionActive() === TRUE) {
            header('Location: '.$page);
        } else {
            header('Location: login.php');
            die();
        }
    }

    static public function checkAccess($page){
        if(static::isSessionActive() === FALSE){
            header('Location: login.php');
            die();
        }
    }

}