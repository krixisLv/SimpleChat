/**
 * Created by Kristaps on 11/27/2015.
 */

$('#form').submit(function (e) {
    e.preventDefault();
    put();
    send();
    $("#msg").val("");
});

function put(){
    var username = $('#username').html();
    var msg = $('#msg').val();

    if(msg == ""){
        return;
    }

    putMessage(username, msg);
}

function putMessage(username, msg){

    var div = document.createElement("div");
    var $msgDiv = $(div);

    $msgDiv.append('<span class="username">'+username+'</span><span class="msg">'+msg+'</span>');
    $("#chat-log").append($msgDiv);

    $('#chat-log').scrollTop(1E10);
}

function send(){
    var username = $('#username').html();
    var msg = $('#msg').val();

    if(msg == ""){
        return;
    }

    var link = "operations.php?action=post&chat_id="+chat_id+"&user_id="+user_id+"&msg="+msg;

    $.ajax({
        type: "GET",
        async: true,
        url: link,
        dataType : 'JSON',
        success: (function( data ){
            //alert(data);
        })
    });

}

function requestReload(){
    request(1);
}

function requestSimple(){
    request(0);
}

function request(reload){

    var link = "operations.php?action=get&chat_id="+chat_id+"&user_id="+user_id+"&reload="+reload;

    $.ajax({
        type: "GET",
        async: true,
        url: link,
        dataType : 'JSON',
        success: (function( data ){
            putData(data);
        })
    });
}

function putData(data){

    for(var i = 0; i < data.length; i++){
        putMessage(data[i].username, data[i].msg);
    }
}

function toggleActiveChat(chat){
    if($(chat).hasClass("active_chat")){
        return;
    }

    $(".active_chat").removeClass("active_chat");
    $(chat).addClass("active_chat");
    active_chat_partner_id = chat.id;

    changeChat(active_chat_partner_id);
}

function changeChat(chat_user){

    var link = "operations.php?action=change&partner_id="+chat_user;

    $.ajax({
        type: "GET",
        async: true,
        url: link,
        dataType : 'JSON',
        success: (function( data ){
            chat_id = parseInt(data);
            clearChat();
            setChatTitle();
            request(1);
        })
    });

}

function setChatTitle(){
    var title = $(".active_chat").html();

    $("#chat_name").html(title);
}

function clearChat(){
    $("#chat-log").html("");
}

function clearUsers(){
    $("#chat-users").html("");
}

function requestUsers(){

    var link = "operations.php?action=get_active_users&user_id="+user_id;

    $.ajax({
        type: "GET",
        async: true,
        url: link,
        dataType : 'JSON',
        success: (function( data ){
            putUserData(data);
        })
    });
}

function putUserData(data){
    clearUsers();

    for(var i = 0; i < data.length; i++){
        putUser(data[i].username, data[i].id);
    }

    $("#chat-users a#"+active_chat_partner_id).addClass("active_chat");
}

function putUser(username, id){

    var html = "<a class='chat-user' id="+id+" onclick='toggleActiveChat(this)'>"+username+"</a>";

    $("#chat-users").append(html);
}