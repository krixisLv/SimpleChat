<?php
/**
 * Created by PhpStorm.
 * User: Kristaps
 * Date: 11/27/2015
 * Time: 1:15 AM
 */

require_once "handlers/SessionsHandler.php";
SessionsHandler::startSession();
SessionsHandler::checkAccess("chatroom.php");

require_once "handlers/DbHandler.php";
$conn = DbHandler::getConnection();

require_once "handlers/ChatHandler.php";
require_once "handlers/UserHandler.php";

$chatroom_name = UserHandler::getUsername(ChatHandler::PUBLIC_CHAT_ID);
$username = UserHandler::getCurrentUserName();

require_once "header.php";
?>
<div id="chat">
<div id="chat-content">
    <div style="header">
        <h1 id="topic">
            ChatRoom :: <span id="chat_name"><?= $chatroom_name; ?></span>
        </h1>
        click on user for private chat
        <div id="logout">
            <a href="operations.php?action=logout" class="button">
                <span class="glyphicon glyphicon-log-out"></span> Log out
            </a>
        </div>
    </div>


    <div id="chat-log">

    </div>

    <hr style="clear:both;" />
    <form id="form">
        <span id="username" style="font-weight: bold"><?= $username; ?></span> says <input type="text" id="msg" name="msg" value="" id="msg" size="80"/>
        <input type="submit" name="send" class="chat button" value="Send">
    </form>
</div>
<div id="chat-users">

</div>
</div>

<script src="js/chat.js"></script>
<script type="text/javascript">

    var user_id = <?= UserHandler::getCurrentUserId(); ?>;
    var chat_id = <?= ChatHandler::PUBLIC_CHAT_ID; ?>;
    var active_chat_partner_id = chat_id;

    requestReload();
    requestUsers();

    setInterval(requestUsers, 10000);
    setInterval(requestSimple, 3000);

</script>

<?php require_once "footer.php"; ?>
