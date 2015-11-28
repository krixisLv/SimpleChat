<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 12:01 AM
 */

require_once "handlers/UserHandler.php";

if( isset($_POST['username']) && $_POST['username'] != ""){
    UserHandler::login($_POST['username']);
    SessionsHandler::redirect('chatroom.php');
}

require_once "header.php";
?>

<div class="login-card">
    <h1>Log-in</h1><br>
    <form id="login_form" action="login.php" method="post">
        <input type="text" name="username" placeholder="Username">
        <input type="submit" name="login" class="login button" value="login">
    </form>

    <div class="login-help">
        There is no password
    </div>
</div>

<?php require_once "footer.php"; ?>